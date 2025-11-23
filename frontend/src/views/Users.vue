<script setup>
import headerComponent from "../components/headerComponent.vue";
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'


let errors = ref([]);
let visibleError = ref(false);

let route = useRoute();

let firstPage = ref(true);
let lastPage = ref(true);
let page = computed(() => Number(route.query.page) || 1);
let users = ref([]);

function loadUsers() {
    axios.get(`http://localhost:8000/Users?page=${page.value}`)
        .then(result => {
            users.value = result.data.logins.data
            checkPages();
        })
        .catch(error => {
            addMessage('Не удалось загрузить пользователей');
        });
}

function checkPages() {
    if (users.value.length < 8) {
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
    loadUsers()
})

watch(page, (newPage) => {
    loadUsers()
})

function addMessage(message) {
    errors.value.push(message)
    visibleError.value = true;
    setTimeout(function () {
        visibleError.value = false;
        errors.value.pop();
    }, 3000)
}


let search = ref('');

function searchForUsers() {
    axios.get(`http://localhost:8000/Users?search=${search.value}`)
        .then(result => {
            users.value = result.data.logins.data
            checkPages();
        })
        .catch(error => addMessage('Не удалось найти пользователей'));
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <div class="search">
            <h2>Пользователи</h2>
            <input id="searchCards" class="input" type="text" placeholder="Поиск по пользователям" v-model="search"
                @input="searchForUsers">
        </div>
        <div v-for="user in users" class="users">
            <RouterLink :to="{ path: '/UserProfile', query: { user: user.id } }" class="name">{{ user.login }}</RouterLink>
        </div>
        <div class="pagination">
            <RouterLink v-show="firstPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page - 1 } }">← Предыдущая</RouterLink>
            <span>Страница: {{ page }}</span>
            <RouterLink v-show="lastPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page + 1 } }">Следующая →</RouterLink>
        </div>
        <div class="error" v-show="visibleError">{{ errors.toString() }}</div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
    justify-content: start;
}

.users {
    display: flex;
    justify-content: center;
    width: 30%;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 20px;
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

.name {
    color: #C93814;
    text-decoration: none;
}

.button {
    margin: 20px;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    color: white;
    border-radius: 20px;
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

.input {
    width: 70%;
    border-radius: 20px;
    margin: 20px;
}

.search {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>