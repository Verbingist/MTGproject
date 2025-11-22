<script setup>
import { ref } from 'vue';
import { RouterLink } from 'vue-router'
import axios from 'axios';

let isLog = ref(false);
let isOrganizer = ref(false);
let isAdmin = ref(false);


axios.get('http://localhost:8000/IsAuth')
    .then(result => checkAuthorization(result.data))
    .catch(error => console.log(error))


function checkAuthorization(authData) {
    switch (authData.role) {
        case 1: {
            isLog.value = true;
            isOrganizer.value = true;
            isAdmin.value = true;
            break;
        }
        case 2: {
            isLog.value = true;
            isOrganizer.value = true;
            isAdmin.value = false;
            break;
        }
        case 3: {
            isLog.value = true;
            isOrganizer.value = false;
            isAdmin.value = false;
            break;
        }
        default: {
            isLog.value = false;
            isOrganizer.value = false;
            isAdmin.value = false;
        }
    }
}

</script>

<template>
    <header>
        <nav class="headerWrapper">
            <div>
                <RouterLink class="sub-navigation" to="/">Главная</RouterLink>
                <RouterLink class="sub-navigation" to="/Users">Пользователи</RouterLink>
                <RouterLink class="sub-navigation" to="/Decks">Мои колоды</RouterLink>
                <RouterLink class="sub-navigation" to="/Cards">Моя коллекция</RouterLink>
                <RouterLink v-show="isOrganizer" class="sub-navigation" to="/">Страница для организатора</RouterLink>
                <RouterLink v-show="isAdmin" class="sub-navigation" to="/">Страница для админа</RouterLink>
            </div>
            <div>
                <RouterLink v-show="!isLog" class="sub-navigation" to="/Registration">Вход/Регистрация</RouterLink>
                <RouterLink v-show="isLog" class="sub-navigation" to="/">Профиль</RouterLink>
            </div>
        </nav>
    </header>
</template>

<style scoped>
header {
    font-size: 23px;
    background: #C93814;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    padding: 10px;
    height: 70px;
    display: flex;
    align-items: center;
}

.headerWrapper {
    display: flex;
    width: 100%;
    justify-content: space-between;
}

.navigation {
    display: flex;
    justify-content: space-evenly;
    flex-direction: row;
}

.sub-navigation {
    font-size: 16px;
    margin: 20px 20px;
    text-decoration: none;
    color: rgb(255, 255, 255);
}
</style>