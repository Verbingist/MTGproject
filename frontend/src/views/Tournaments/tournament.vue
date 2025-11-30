<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let route = useRoute()
let tournament_id = computed(() => Number(route.query.id))

let tournament = ref([]);

function loadTournament() {
    axios.get(`http://localhost:8000/Tournament/${tournament_id.value}`)
        .then(result => {
            tournament.value = result.data.tournament
        })
        .catch(error => addMessage("Не удалось загрузить турнир"));
}

let users = ref([])
let userCount = computed(() => users.value.length)

function loadUsers() {
    axios.get(`http://localhost:8000/UsersInTournament/${tournament_id.value}`)
        .then(result => {
            users.value = result.data.users
        })
        .catch(error => addMessage("Не удалось загрузить пользователей"));
}

onMounted(() => {
    loadTournament();
    loadUsers();
})

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
        <h2 class="logo">Турнир {{ tournament.tournament_name }}</h2>
        <div class="info">
            <p>Описание: {{ tournament.tournament_description ?? "нет" }}</p>
            <p>Дата: {{ tournament.tournament_date }}</p>
            <p>Статус: {{ tournament.status }}</p>
        </div>
        <div class="users">
            <p>Записанные участники <span v-show="!userCount">отсутствуют</span></p>
            <p v-for="user in users">
                <RouterLink class="user" :to="{ path: '/UserProfile', query: { user: user.id } }">
                    {{ user.login }}
                </RouterLink>
            </p>
            <p>Общее число - {{ userCount }}</p>
        </div>
        <div class="message" v-show="visibleMessage">{{ globalMessage.toString() }}</div>
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

.info {
    padding: 0px 15%;
    width: 50%;
    min-height: 300px;
}

.users {
    width: 50%;
    min-height: 300px;
}

.user {
    color: #C93814;
    text-decoration: none;
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