<script setup>
import axios from 'axios';
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

let confirmData = ref(false);
let router = useRouter()

let createForm = reactive({
    format_name: "",
    min_cards_in_deck: "",
    max_cards_in_deck: "",
    format_description: ""
});

let isVisibleErrors = reactive({
    format_name: false,
    min_cards_in_deck: false,
    max_cards_in_deck: false,
    format_description: false
})

let errors = reactive({
    format_name: "",
    min_cards_in_deck: "",
    max_cards_in_deck: "",
    format_description: ""
});

function testName() {
    if (createForm.format_name.length > 3) {
        if (/^[A-Za-zА-Яа-я0-9 _-]+$/.test(createForm.format_name)) {
            isVisibleErrors.format_name = false;
            errors.format_name = "";
            return true;
        }
        else {
            isVisibleErrors.format_name_name = true;
            errors.format_name = "Название содержит только латинские буквы и цифры";
        }
    }
    else {
        isVisibleErrors.format_name = true;
        errors.format_name = "Название содержит хотя бы 4 символа";
    }
    return false;
}

function testDescription() {
    if (/^[A-Za-zА-Яа-я0-9 _-]+$/.test(createForm.format_description)) {
        isVisibleErrors.format_description = false;
        errors.format_description = "";
        return true;
    }
    else {
        isVisibleErrors.format_description = true;
        errors.format_description = "Описание содержит только латинские буквы и цифры";
    }
    return false;
}

function testMax() {
    if (createForm.max_cards_in_deck == "" || Number(createForm.max_cards_in_deck)) {
        isVisibleErrors.max_cards_in_deck = false;
        errors.max_cards_in_deck = "";
        return true;
    }
    else {
        isVisibleErrors.max_cards_in_deck = true;
        errors.max_cards_in_deck = "Введите число";
    }
    return false;
}

function testMin() {
    if ((Number(createForm.min_cards_in_deck) && Number(createForm.min_cards_in_deck) > 40)) {
        isVisibleErrors.min_cards_in_deck = false;
        errors.min_cards_in_deck = "";
        return true;
    }
    else {
        isVisibleErrors.min_cards_in_deck = true;
        errors.min_cards_in_deck = "Введите число больше 40";
    }
    return false;
}


function submitFunction(event) {
    event.preventDefault(event);
    if (!testName()) return;
    if (!testDescription()) return;
    if (!testMax()) return;
    if (!testMin()) return;
    axios.post('http://localhost:8000/Format', {
        "format_name": createForm.format_name,
        "format_description": createForm.format_description,
        "min_cards_in_deck": Number(createForm.min_cards_in_deck),
        "max_cards_in_deck": Number(createForm.max_cards_in_deck) || null,
    })
        .then(result => {
            addMesage('Формат успешно создан')
            setTimeout(function () {
                router.push(`/Formats`)
            }, 1000);
        })
        .catch(error => addMesage('Неудачная попытка создания формата'))
}

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
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Создать формат</h2>
        <form @submit="submitFunction" class="inputForm">

            <input class="input" type="text" placeholder="Название формата" v-model="createForm.format_name"
                @blur="testName" @input="testName">
            <div class="red" v-show="isVisibleErrors.format_name">{{ errors.format_name }}</div>

            <textarea class="input" placeholder="Описание формата" v-model="createForm.format_description"
                @blur="testDescription" @input="testDescription"></textarea>
            <div class="red" v-show="isVisibleErrors.format_description">{{ errors.format_description }}</div>

            <input class="input" type="text" placeholder="Мин количество карт в колоде"
                v-model="createForm.min_cards_in_deck" @blur="testMin" @input="testMin">
            <div class="red" v-show="isVisibleErrors.min_cards_in_deck">{{ errors.min_cards_in_deck }}</div>

            <input class="input" type="text" placeholder="Макс количество карт в колоде (не обязательно)"
                v-model="createForm.max_cards_in_deck" @blur="testMax" @input="testMax">
            <div class="red" v-show="isVisibleErrors.max_cards_in_deck">{{ errors.max_cards_in_deck }}</div>

            <button class="input backButton" type="submit">Подтвердить</button>
        </form>
        <div class="message" v-show="visibleMessages">{{ globalMessages.toString() }}</div>
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

select {
    background-color: white;
}

textarea.input {
    height: 150px;
    resize: none;
    overflow: scroll;
}

.search-for-commander {
    width: 500px;
    border-radius: 20px;
    top: 540px;
    padding: 10px;
    position: absolute;
    height: 200px;
    border: 3px solid black;
    background-color: white;
    overflow: scroll;
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