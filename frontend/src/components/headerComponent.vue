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

let burgerShow = ref(false);

function revealBurger() {
    if (burgerShow.value) {
        burgerShow.value = false;
    }
    else {
        burgerShow.value = true;
    }
}

</script>

<template>
    <header>
        <nav class="headerWrapper">
            <div class="main-hrefs">
                <div class="burger-menu pointer" @click="revealBurger">
                    <div class="burger-sub-menu"></div>
                    <div class="burger-sub-menu"></div>
                    <div class="burger-sub-menu"></div>
                </div>
                <div>
                    <div class="href-line">
                        <RouterLink class="sub-navigation" to="/">Главная</RouterLink>
                        <RouterLink class="sub-navigation" to="/Users">Пользователи</RouterLink>
                        <RouterLink class="sub-navigation" to="/Lots">Лоты</RouterLink>
                        <RouterLink class="sub-navigation" to="/Cards">Карты</RouterLink>
                        <RouterLink class="sub-navigation" to="/Decks">Колоды</RouterLink>
                    </div>
                </div>
            </div>
            <div>
                <RouterLink v-show="!isLog" class="sub-navigation" to="/Registration">Вход/Регистрация</RouterLink>
                <RouterLink v-show="isLog" class="sub-navigation" to="/Profile">Профиль</RouterLink>
            </div>
            <div class="drop-down-menu" v-show="burgerShow">
                <div class="close-burger">
                    <div class="close-burger-sub">Меню</div>
                    <div @click="revealBurger" class="close-burger-sub pointer">☓</div>
                </div>
                <div class="burger-hrefs">
                    <RouterLink v-show="isLog" class="sub-navigation" to="/createDeck">Создать колоду</RouterLink>
                    <RouterLink v-show="isLog" class="sub-navigation" to="/createLot">Создать объявление</RouterLink>
                    <RouterLink v-show="isLog" class="sub-navigation" to="/MyDecks">Мои колоды</RouterLink>
                    <RouterLink v-show="isLog" class="sub-navigation" to="/MyLots">Мои объявления</RouterLink>
                    <RouterLink v-show="isLog" class="sub-navigation" to="/MyCards">Моя коллекция</RouterLink>
                    <RouterLink v-show="isOrganizer" class="sub-navigation" to="/">Страница для организатора
                    </RouterLink>
                    <RouterLink v-show="isAdmin" class="sub-navigation" to="/">Страница для админа</RouterLink>
                </div>
            </div>
        </nav>
    </header>
</template>

<style scoped>
header {
    font-size: 26px;
    background: #C93814;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    padding: 10px;
    display: flex;
    align-items: center;
}

.main-hrefs {
    display: flex;
    flex-direction: row;
    justify-content: start;
    align-items: center;
}

.burger-menu {
    width: 50px;
}

.burger-sub-menu {
    background-color: white;
    height: 2px;
    margin: 8px 0px;
    width: 40px;
}

.headerWrapper {
    display: flex;
    width: 100%;
    flex-direction: row;
    justify-content: space-between;
}

.sub-navigation {
    font-size: 16px;
    margin: 20px 20px;
    text-decoration: none;
    color: rgb(255, 255, 255);
}

.drop-down-menu {
    position: fixed;
    top: 0;
    left: 0;
    width: 300px;
    height: 100%;
    background: #C93814;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 100%);
}

.burger-hrefs {
    display: flex;
    flex-direction: column;
}

.close-burger {
    display: flex;
    width: 100%;
    flex-direction: row;
    justify-content: space-between;
}

.close-burger-sub {
    margin: 20px;
    color: white;
}

.pointer {
    cursor: pointer;
}
</style>