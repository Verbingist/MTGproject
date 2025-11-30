<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let auth = ref(false)

function authCheck() {
    axios.get('http://localhost:8000/IsAuth')
        .then(result => {
            if (result.data.role != 4) {
                auth.value = true;
            }
        })
        .catch(error => addMessage('Не удалось проверить авторизацию'))
}

let cards = ref([]);
let format = computed(() => route.query.format)

function loadCards() {
    axios.get(`http://localhost:8000/RestrictedCards/${format.value}?page=${page.value}`)
        .then(result => {
            console.log(result.data.cards.data)
            cards.value = result.data.cards.data
            checkPages();
        })
        .catch(error => addMessage("Не удалось загрузить карты"));
}


let route = useRoute()
let page = computed(() => Number(route.query.page) || 1)
let firstPage = ref(true);
let lastPage = ref(true);

function checkPages() {
    if (cards.value.length < 6) {
        lastPage.value = false;
    }
    else {
        lastPage.value = true;
    }
    if (page.value == 1) {
        firstPage.value = false;
    }
    else {
        firstPage.value = true;
    }
}

onMounted(() => {
    loadCards()
    authCheck()
})

watch(page, (newPage) => {
    loadCards()
})


function addCardToCollection(card) {
    axios.post(`http://localhost:8000/addCardToCollection`, {
        "card_name": card.card.card_name
    })
        .then(result => {
            addMessage("Карта успешно добавлена в коллекцию")
        })
        .catch(error => addMessage("Не удалось добавить карту в коллекцию"));
}

let globalMessage = ref([]);
let visibleMessage = ref(false);

function addMessage(message) {
    globalMessage.value.push(message);
    visibleMessage.value = true;
    setTimeout(function () {
        visibleMessage.value = false;
        globalMessage.value.pop();
    }, 3000)
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Ограниченные карты в формате {{ format }}</h2>
        <div class="cards">
            <div v-for="card in cards" class="card">
                <h3>{{ card.card_name }}</h3>
                <p>Тип ограничения: {{ card.restriction_type }}</p>
                <p>Причина: {{ card.restriction_description }}</p>
                <p>Дата ограничения: {{ card.date_of_restriction }}</p>
                <img class="card-image" :src="card.image_href">
                <p>
                    <RouterLink class="cardInfo" :to="{ path: '/Card', query: { id: card.card_id } }">Подробное описание
                    </RouterLink>
                </p>
                <button v-show="auth" class="add-button" @click="addCardToCollection(card)">Добавить в
                    коллекцию</button>
            </div>
        </div>
        <div class="pagination">
            <RouterLink v-show="firstPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page - 1 } }">← Предыдущая</RouterLink>
            <span>Страница: {{ page }}</span>
            <RouterLink v-show="lastPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page + 1 } }">Следующая →</RouterLink>
        </div>
        <div class="message" v-show="visibleMessage">{{ globalMessage.toString() }}</div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.cardInfo {
    text-decoration: none;
    color: #C93814;
}

.card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 30%;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 20px;
}

.cards {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: start;
}

.added-cards {
    width: 70%;
    border-radius: 20px;
    top: 220px;
    padding: 10px;
    position: absolute;
    height: 400px;
    border: 3px solid black;
    background-color: white;
}

.add-button {
    margin: 20px;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    color: white;
    border-radius: 20px;
}

.card-image {
    width: 300px;
}

.pagination {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 50px;
}

.pagination-button {
    text-decoration: none;
    color: #C93814;
    margin: 20px 20px;
}

.input {
    width: 70%;
    padding: 10px;
    border-radius: 20px;
    margin: 20px;
}

.message {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    width: 50%;
    height: 25%;
    border: 3px solid black;
    top: 70%;
    left: 25%;
    background-color: white;
    border-radius: 20px;
}
</style>