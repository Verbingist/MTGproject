<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let auth = ref(false)
let isAdmin = ref(false)

function authCheck() {
    axios.get('http://localhost:8000/IsAuth')
        .then(result => {
            if (result.data.role != 4) {
                auth.value = true;
            }
            if (result.data.role == 1) {
                isAdmin = true;
            }
        })
        .catch(error => addMessage('Не удалось проверить авторизацию'))
}

let route = useRoute()
let set_id = computed(() => Number(route.query.id))

let cards = ref([]);
let set = ref([]);

function loadSet() {
    axios.get(`http://localhost:8000/Set/${set_id.value}`)
        .then(result => {
            set.value = result.data.set
        })
        .catch(error => addMessage(error.response.data.message));
}


function loadCards() {
    axios.get(`http://localhost:8000/CardsInSet/${set_id.value}`)
        .then(result => {
            cards.value = result.data.cards
        })
        .catch(error => addMessage(error.response.data.message));
}

onMounted(() => {
    loadCards()
    loadSet()
    authCheck()
})

function addCardToCollection(card) {
    axios.post(`http://localhost:8000/addCardToCollection`, {
        "card_name": card.card_name
    })
        .then(result => addMessage("Успешно добавлено в коллекцию"))
        .catch(error => addMessage(error.response.data.message))
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


let newCard = ref('');
let isSearchForAdd = ref(false);
let foundCardsForAdd = ref([]);

function startAdding() {
    if (newCard.value.length < 3) {
        isSearchForAdd.value = false;
    }
    else {
        isSearchForAdd.value = true
        axios.get(`http://localhost:8000/Cards?search=${newCard.value}`)
            .then(result => {
                foundCardsForAdd.value = result.data.cards
            })
            .catch(error => addMessage("Ошибка при поиске карт"))
    }
}

function addCardToSet(card) {
    axios.post(`http://localhost:8000/addCardToSet/${set_id.value}`, {
        "card_name": card.card.card_name
    })
        .then(result => {
            loadCards();
        })
        .catch(error => addMessage(error.response.data.message))
}

function removeCardFromSet(card) {
    axios.delete(`http://localhost:8000/removeCardFromSet/${set_id.value}`, {
        data: {
            "card_name": card.card_name
        }
    })
        .then(result => {
            loadCards();
        })
        .catch(error => addMessage(error.response.data.message))
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Сет {{ set.set_name }}</h2>
        <div v-show="isAdmin" class="add-cards-to-set">
            <input class="input" type="text" placeholder="Добавить карту в сет" v-model="newCard" @input="startAdding">
            <div v-show="isSearchForAdd" class="added-cards">
                <div v-for="card in foundCardsForAdd">
                    <div @click="addCardToSet(card)">{{ card.card.card_name }}</div>
                </div>
            </div>
        </div>
        <div class="cards">
            <div v-for="card in cards" class="card">
                <h3>{{ card.card_name }}</h3>
                <img class="card-image" :src="card.image_href">
                <p>
                    <RouterLink class="cardInfo" :to="{ path: '/Card', query: { id: card.card_id } }">Подробное
                        описание</RouterLink>
                </p>
                <button v-show="auth" class="add-button" @click="addCardToCollection(card)">Добавить в
                    коллекцию</button>
                <button v-show="isAdmin" class="add-button" @click="removeCardFromSet(card)">Удалить карту из
                    сета</button>
            </div>
        </div>
        <div class=" message" v-show="visibleMessage">{{ globalMessage.toString() }}
        </div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.added-cards {
    width: 70%;
    border-radius: 20px;
    top: 285px;
    position: absolute;
    height: 200px;
    padding: 10px;
    border: 3px solid black;
    background-color: white;
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
    top: 225px;
    position: absolute;
    height: 200px;
    padding: 10px;
    border: 3px solid black;
    background-color: white;
}

.add-button {
    margin: 20px;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    color: white;
    border-radius: 20px;
}

.add-cards-to-set {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
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
    border-radius: 20px;
    margin: 20px;
    padding: 10px;
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