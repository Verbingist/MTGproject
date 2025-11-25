<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let collection = ref([]);

function loadCollection() {
    axios.get(`http://localhost:8000/Collection?page=${page.value}`)
        .then(result => {
            collection.value = result.data.data.data
            checkPages();
        })
        .catch(error => console.log(error));
}


let route = useRoute()
let page = computed(() => Number(route.query.page) || 1)
let firstPage = ref(true);
let lastPage = ref(true);

function checkPages() {
    if (collection.value.length < 9) {
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
    loadCollection(page.value)
})

watch(page, (newPage) => {
    loadCollection(newPage)
})


let newCard = ref('');
let isSearchForAdd = ref(false);
let foundCardsForAdd = ref([]);

function startAdding() {
    if (newCard.value.length < 3) {
        isSearchForAdd.value = false;
    }
    else {
        isSearchForAdd.value = true
        axios.get(`http://localhost:8000/Cards?search=${newCard.value}&page=${page.value}`)
            .then(result => {
                foundCardsForAdd.value = result.data.cards
            })
            .catch(error => console.log(error))
    }
}

function addCardToCollection(card) {
    axios.post(`http://localhost:8000/addCardToCollection`, {
        "card_name": card.card.card_name
    })
        .then(result => {
            console.log(result)
            loadCollection();
        })
        .catch(error => console.log(error))
}

function removeCardFromCollection(card) {
    axios.delete(`http://localhost:8000/removeCardFromCollection`, {
        data: {
            "card_name": card.card_name
        }
    })
        .then(result => {
            loadCollection();
        })
        .catch(error => console.log(error))
}


let search = ref('');

function searchForCards() {
    axios.get(`http://localhost:8000/Collection?search=${search.value}`)
        .then(result => {
            collection.value = result.data.data.data
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
        <h2>Моя коллекция</h2>
        <input id="addCards" class="input" type="text" placeholder="Добавить карту в коллекцию" v-model="newCard"
            @input="startAdding">
        <div v-show="isSearchForAdd" class="added-cards">
            <div v-for="card in foundCardsForAdd">
                <div @click="addCardToCollection(card)">{{ card.card.card_name }}</div>
            </div>
        </div>
        <input id="searchCards" class="input" type="text" placeholder="Поиск по коллекции" v-model="search"
            @input="searchForCards">
        <div class="cards">
            <div v-for="card in collection" class="card">
                <h3>{{ card.card_name }}</h3>
                <img class="card-image" :src="card.image_href">
                <button class="delete-button" @click="removeCardFromCollection(card)">Удалить карту</button>
            </div>
        </div>
        <div class="pagination">
            <RouterLink v-show="firstPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page - 1 } }">← Предыдущая</RouterLink>
            <span>Страница: {{ page }}</span>
            <RouterLink v-show="lastPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page + 1 } }">Следующая →</RouterLink>
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
    top: 290px;
    padding: 10px;
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
    padding: 10px;
    border-radius: 20px;
    margin: 20px;
}
</style>