<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'
import { useRouter } from 'vue-router';

let router = useRouter();

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

let user = ref([]);

axios.get(`http://localhost:8000/User`)
    .then(result => user.value = result.data.user)
    .catch(error => addMessage("Не удалось загрузить данные о пользователе"))

function logout() {
    axios.get('http://localhost:8000/auth/logout')
        .then(result => {
            addMessage('Успешный выход')
            setTimeout(function () {
                router.push('/')
            }, 1000)
        })
        .catch(error => addMessage("Не удалось выйти"))
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <div class="logo">
            <h2>Профиль</h2>
        </div>
        <div class="info">
            <p>Имя: {{ user.first_name }}</p>
            <p>Фамилия: {{ user.last_name }}</p>
            <p>Возраст: {{ user.age ? user.age : "Не указан" }}</p>
            <p>Почта: {{ user.email }}</p>
            <p>Логин: {{ user.login }}</p>
        </div>
        <div class="hrefs">
            <p>
                <RouterLink class="locallink" to="/MyCards">Моя коллекция</RouterLink>
            </p>
            <p>
                <RouterLink class="locallink" to="/MyDecks">Мои колоды</RouterLink>
            </p>
            <p>
                <RouterLink class="locallink" to="/MyLots">Мои объявления</RouterLink>
            </p>
            <p>
                <button class="locallink" @click="logout">Выйти из аккаунта</button>
            </p>
        </div>
        <div class="message" v-show="visibleError">{{ globalErrors.toString() }}</div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.logo {
    display: flex;
    justify-content: center;
    width: 100%;
}

.hrefs {
    width: 50%;
    display: flex;
    flex-direction: column;
    align-items: start;
    justify-content: center;
}

button {
    background-color: white;
    border: 0px;
}

button:hover {
    cursor: pointer;
}

.locallink {
    text-decoration: none;
    color: #C93814;
}

.info {
    padding: 0px 100px;
    width: 50%;
    display: flex;
    flex-direction: column;
    align-items: start;
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