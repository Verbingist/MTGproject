<script setup>
import headerComponent from "../components/headerComponent.vue";
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let route = useRoute()

let newCard = ref('');
let search = ref('');

let firstPage = true;
let lastPage = true;
let page = computed(() => Number(route.query.page) || 1)
let collection = ref([]);

function loadCollection() {
    axios.get(`http://localhost:8000/Collection?page=${page.value}`)
        .then(result => {
            console.log(result.data.data.data)
            collection.value = result.data.data.data
            checkPages();
        })
        .catch(error => console.log(error));
}

function checkPages() {
    if (collection.value.length < 8) {
        lastPage = false;
    }
    else {
        lastPage = true;
    }
    if (page.value == 1) {
        firstPage = false;
    }
    else {
        firstPage = true;
    }
}

onMounted(() => {
    loadCollection(page.value)
})

watch(page, (newPage) => {
    loadCollection(newPage)
})

</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Моя коллекция</h2>
        <input id="addCards" class="input" type="text" placeholder="Добавить карту в коллекцию" v-model="newCard">
        <input id="searchCards" class="input" type="text" placeholder="Поиск по коллекции" v-model="search">
        <div v-for="card in collection" class="card">
            <h3>{{ card.card_name }}</h3>
            <img class="card-image" :src="card.image_href">
            <button class="delete-button">Удалить карту</button>
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
    width: 70%;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 20px;
}

.delete-button {
    margin: 20px;
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
</style>