<script setup>
import headerComponent from "../components/headerComponent.vue";
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let globalErrors = ref([]);
let visibleError = ref(false);

function addMessage(message) {
    globalErrors.value.push(message);
    visibleError.value = true;
    setTimeout(function () {
        visibleError.value = false;
        globalErrors.value.pop();
    }, 3000)
}

let decks = ref([]);

let route = useRoute()
let id = computed(() => Number(route.query.user))

function loadDecks() {
    axios.get(`http://localhost:8000/Decks/${id.value}?page=${page.value}`)
        .then(result => {
            decks.value = result.data.decks.data;
            checkPages();
        })
        .catch(error => addMessage('Не удалось загрузить колоды'));
}


let page = computed(() => Number(route.query.page) || 1);
let firstPage = ref(true);
let lastPage = ref(true);

function checkPages() {
    if (decks.value.length < 8) {
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
    loadDecks()
})

watch(page, (newPage) => {
    loadDecks()
})


let search = ref('');

function searchForDecks() {
    axios.get(`http://localhost:8000/MyDecks?search=${search.value}`)
        .then(result => {
            decks.value = result.data.decks.data
            checkPages();
        })
        .catch(error => console.log(error));
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Мои колоды</h2>
        <input id="searchCards" class="input" type="text" placeholder="Поиск по своим колодам" v-model="search"
            @input="searchForDecks">
        <div class="cards">
            <div v-for="deck in decks" class="deck">
                <h3>Название: {{ deck.deck_name }}</h3>
                <h3>Формат: {{ deck.format_name }}</h3>
                <p>
                    <RouterLink class="hrefToDeck" :to="{ path: '/Deck', query: { id: deck.deck_id } }">Просмотр</RouterLink>
                </p>
            </div>
        </div>
        <div class="pagination">
            <RouterLink v-show="firstPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page - 1 } }">← Предыдущая</RouterLink>
            <span>Страница: {{ page }}</span>
            <RouterLink v-show="lastPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page + 1 } }">Следующая →</RouterLink>
        </div>
        <div class="error" v-show="visibleError">{{ globalErrors.toString() }}</div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.hrefToDeck {
    text-decoration: none;
    color:#C93814;
}

.deck {
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
    position: absolute;
    height: 400px;
    border: 3px solid black;
    background-color: white;
}

.delete-button {
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
    border-radius: 20px;
    margin: 20px;
}

.error {
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