<script setup>
import headerComponent from "../components/headerComponent.vue";
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'


let isAuth = ref(false);

function authCheck() {
    axios.get(`http://localhost:8000/IsAuth`)
        .then(result => {
            isAuth.value = result.data.auth
            checkPages();
        })
        .catch(error => addMessage("Не удалось аутентифицировать пользователя"));
}


let errors = ref([]);
let visibleError = ref(false);

let route = useRoute();

let firstPage = ref(true);
let lastPage = ref(true);
let page = computed(() => Number(route.query.page) || 1);
let tournaments = ref([]);

function loadTournaments() {
    axios.get(`http://localhost:8000/Tournaments?page=${page.value}`)
        .then(result => {
            tournaments.value = result.data.tournaments.data
            checkPages();
        })
        .catch(error => {
            addMessage('Не удалось загрузить турниры');
        });
}

function checkPages() {
    if (tournaments.value.length < 8) {
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
    authCheck()
    loadTournaments()
})

watch(page, (newPage) => {
    loadTournaments(newPage)
})

function signUpTournament(id) {
    axios.post(`http://localhost:8000/signUpTournament/${id}`)
        .then(result => loadTournaments())
        .catch(error => addMessage('Не удалось записаться на турнир'))
}

function signDownTournament(id) {
    axios.delete(`http://localhost:8000/signDownTournament/${id}`)
        .then(result => loadTournaments())
        .catch(error => addMessage('Не удалось удалить запись'))
}

function addMessage(message) {
    errors.value.push(message)
    visibleError.value = true;
    setTimeout(function () {
        visibleError.value = false;
        errors.value.pop();
    }, 3000)
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <div class="logo">
            <h2>Турниры</h2>
        </div>
        <div v-for="tournament in tournaments" :key="tournament.tournament_id" class="tournament">
            <h3 class="name">Название: {{ tournament.tournament_name }}</h3>
            <p>Формат: {{ tournament.format_name }}</p>
            <p>Статус: {{ tournament.status }}</p>
            <p>Дата: {{ tournament.tournament_date }}</p>
            <p>Описание: {{ tournament.tournament_description ? tournament.tournament_description : "Нет" }}</p>
            <p class="name" v-show="isAuth">Запись: {{ tournament.signed }}</p>
            <button v-show="tournament.signed == 'Не записан' && isAuth" class="button"
                @click="signUpTournament(tournament.tournament_id)">Записаться</button>
            <button v-show="tournament.signed == 'Записан' && isAuth" class="button"
                @click="signDownTournament(tournament.tournament_id)">Удалить запись</button>
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
    justify-content: center;
}

.logo {
    display: flex;
    justify-content: center;
    width: 100%;
}

.tournament {
    width: 40%;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 40px;
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
</style>