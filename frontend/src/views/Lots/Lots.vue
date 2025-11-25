<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let lots = ref([]);

let route = useRoute()

function loadLots() {
    axios.get(`http://localhost:8000/Lots?page=${page.value}`)
        .then(result => {
            lots.value = result.data.lots.data
            checkPages();
        })
        .catch(error => addMessage('Не удалось загрузить коллекцию'));
}

let page = computed(() => Number(route.query.page) || 1)
let firstPage = ref(true);
let lastPage = ref(true);

function checkPages() {
    if (lots.value.length < 9) {
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
    loadLots()
})

watch(page, (newPage) => {
    loadLots()
})


let search = ref('');

function searchForLots() {
    axios.get(`http://localhost:8000/Lots?search=${search.value}`)
        .then(result => {
            lots.value = result.data.lots.data
            checkPages();
        })
        .catch(error => addMessage('Не удалось загрузить коллекцию'));
}

let globalMessage = ref([]);
let visibleMessage = ref(false);

function addMessage(message) {
    globalMessage.value.push(message);
    visibleMessage.value = true;
    setTimeout(function () {
        visibleMessage.value = false;
        globalMessage.value.pop();
    }, 3000);
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Объявления</h2>
        <input class="input" type="text" placeholder="Поиск лотов" v-model="search" @input="searchForLots">
        <div class="lots">
            <div v-for="lot in lots" class="lot">
                <h3>{{ lot.lot_name }}</h3>
                <p>{{ lot.lot_description }}</p>
                <p>
                    <RouterLink class="hrefToLot" :to="{ path: '/Lot', query: { id: lot.lot_id } }">Просмотр
                    </RouterLink>
                </p>
                <p>
                    <RouterLink class="author" :to="{ path: '/UserProfile', query: { user: lot.user_id } }">Владелец
                    </RouterLink>
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

.author {
    text-decoration: none;
    color: #C93814;
}

.lot {
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

.lots {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: start;
}

.hrefToLot {
    text-decoration: none;
    color: #C93814;
}

.added-cards {
    width: 70%;
    border-radius: 20px;
    top: 220px;
    position: absolute;
    height: 400px;
    border: 3px solid black;
    background-color: white;
    padding: 10px;
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