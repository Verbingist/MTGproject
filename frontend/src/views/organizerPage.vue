<script setup>
import headerComponent from '../components/headerComponent.vue';
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
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2 class="logo">Страница для организатора</h2>
        <div class="href-column">
            <RouterLink v-show="isOrganizer || isAdmin" class="sub-navigation" to="/createFormat">Создать формат
            </RouterLink>
        </div>
        <div class="href-column">
            <RouterLink v-show="isOrganizer || isAdmin" class="sub-navigation" to="/createTournament">Создать турнир
            </RouterLink>
        </div>
        <div class="href-column">
            <RouterLink v-show="isOrganizer || isAdmin" class="sub-navigation" to="/restrictCard">Ограничить карту в формате
            </RouterLink>
        </div>
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

.href-column {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 30px;
}

.sub-navigation {
    margin: 20px 0px;
    text-decoration: none;
    color: #C93814;
}
</style>