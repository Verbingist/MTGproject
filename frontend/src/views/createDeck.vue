<script setup>
import axios from 'axios';
import headerComponent from '../components/headerComponent.vue';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

let router = useRouter()

let globalMessages = ref([]);
let visibleMessages = ref(false);

function addMesage(message) {
    globalMessages.value.push(message)
    visibleMessages.value = true;
    setTimeout(function () {
        visibleMessages.value = false;
        globalMessages.value.pop();
    }, 3000)
}

let confirmData = ref(false);

let createDeckForm = reactive({
    deck_name: "",
    format_name: "",
    power_level: "",
});

let isVisibleErrors = reactive({
    deck_name: false,
    format_name: false,
    power_level: false,
})

let errors = reactive({
    deck_nameError: "",
    format_nameError: "",
    power_levelError: "",
});

function testDeckName() {
    if (/^[A-Za-zА-Яа-я0-9 _-]+$/.test(createDeckForm.deck_name) || createDeckForm.deck_name == "") {
        if (createDeckForm.deck_name.length > 3 || createDeckForm.deck_name == "") {
            isVisibleErrors.deck_name = false;
            errors.deck_nameError = "";
        }
        else {
            isVisibleErrors.deck_name = true;
            errors.deck_nameError = "Логин содержит хотя бы 4 символа";
        }
    }
    else {
        isVisibleErrors.deck_name = true;
        errors.deck_nameError = "Логин содержит только латинские буквы и цифры";
    }
}

function submitFunction() {
    event.preventDefault(event);
    if (/^[A-Za-z0-9]+$/.test(registrationForm.login)) {
        if (registrationForm.login.length > 3) {
            if (/^[A-Za-z0-9]+$/.test(registrationForm.password)) {
                if (registrationForm.password.length > 3) {
                    confirmData.value = true;
                }
            }
        }
    }
    axios.post('http://localhost:8000/Deck', {
        "deck_name": createDeckForm.deck_name,
        "format_name": createDeckForm.format_name,
        "power_level": createDeckForm.power_level,
    })
        .then(result => {
            addMesage('Колода успешно создана')
            setTimeout(function () {
                router.push('/')
            }, 1000)
        })
        .catch(error => addMesage('Неудачная попытка входа'))
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Создать колоду</h2>
        <form @submit="submitFunction" class="inputForm">

            <input class="input" type="text" placeholder="Название колоды" v-model="createDeckForm.deck_name"
                @blur="testDeckName" @input="testDeckName">
            <div class="red" v-show="isVisibleErrors.deck_name">{{ errors.deck_nameError }}</div>

            <input class="input" type="text" placeholder="Формат колоды" v-model="createDeckForm.format_name"
                @blur="passwordTest" @input="passwordTest">
            <div class="red" v-show="isVisibleErrors.password">{{ errors.passwordError }}</div>

            <button id="Submit" class="input backButton" type="submit">Подтвердить</button>
        </form>
        <div v-show="confirmData" class="confirm">
            <button class="submitbutton" @click="reloadPage">Подтвердить</button>
        </div>
        <div class="message" v-show="visibleError">{{ globalMessages.toString() }}</div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.inputForm {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.input {
    padding: 0px 10px;
    margin: 20px;
    height: 40px;
    width: 500px;
}

.confirm {
    display: flex;
    flex-direction: column;
    justify-content: start;
    position: absolute;
    width: 550px;
    height: 300px;
    border: 2px solid #C93814;
    background-color: white;
    border-radius: 20px;
    z-index: 2;
}

.red {
    font-weight: bold;
    color: black;
}

#blur {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 1;
}

.submitDot {
    margin: 10px;
}

.loginClass {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0px 10px;
    margin: 20px;
    height: 40px;
    width: 500px;
    border-radius: 10px;
}

.registrationButton {
    color: #C93814;
    text-decoration: none;
    margin: 0px 0px 0px 20px;
}

.submitheaderbox {
    display: flex;
    flex-direction: row;
    justify-content: center;
}

.submitbuttonbox {
    margin: 20px;
    display: flex;
    justify-content: center;
}

.submitbutton {
    margin: 0px 20px;
    width: 200px;
}

button {
    background: #C93814;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    color: white;
}

.input,
select,
button {
    border-radius: 10px;
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