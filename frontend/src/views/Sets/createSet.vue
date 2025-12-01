<script setup>
import axios from 'axios';
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

let router = useRouter()

let createForm = reactive({
    name: "",
    number: "",
    date: ""
});

let isVisibleErrors = reactive({
    name: false,
    number: false,
    date: false
})

let errors = reactive({
    name: "",
    number: "",
    date: ""
});

function testName() {
    if (createForm.name.length > 3) {
        if (/^[A-Za-zА-Яа-я0-9 _-]+$/.test(createForm.name) || createForm.name == "") {
            isVisibleErrors.name = false;
            errors.name = "";
            return true;
        }
        else {
            isVisibleErrors.name = true;
            errors.name = "Название содержит только латинские буквы и цифры";
        }
    }
    else {
        isVisibleErrors.name = true;
        errors.name = "Название содержит хотя бы 4 символа";
    }
    return false;
}

function testCardsNumber() {
    if (!isNaN(Number(createForm.number) && Number(createForm.number) > 0)) {
        isVisibleErrors.description = false;
        errors.description = "";
        return true;
    }
    else {
        isVisibleErrors.description = true;
        errors.description = "Количество цифрой более 0";
    }
    return false;
}

let date = ref('');

function testDate() {
    let arrayDate = date.value.split('.');
    if (arrayDate.length < 3
        || !Number(arrayDate[0]) || arrayDate[0].toString().length != 2 || arrayDate[0] < 0
        || !Number(arrayDate[1]) || arrayDate[1].toString().length != 2 || arrayDate[1] < 0
        || !Number(arrayDate[2]) || arrayDate[2].toString().length != 4 || arrayDate[2] < 0
    ) {
        isVisibleErrors.date = true;
        errors.date = "Неверный формат даты";
        return false;
    }
    else {
        createForm.date = arrayDate[2] + "-" + arrayDate[1] + "-" + arrayDate[0];
        isVisibleErrors.date = false;
        errors.date = "";
        return true;
    }
}


function submitFunction(event) {
    event.preventDefault(event);
    if (!testName()) return;
    if (!testCardsNumber()) return;
    if (!testDate()) return;
    axios.post('http://localhost:8000/Set', {
        "set_name": createForm.name,
        "number_of_cards": createForm.number,
        "date_of_release": createForm.date,
    })
        .then(result => {
            addMesage('Сет успешно создано')
            setTimeout(function () {
                router.push(`/Sets`)
            }, 1000);
        })
        .catch(error => {
            addMesage(error.response.data.message)
        })
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
        <h2>Создать сет</h2>
        <form @submit="submitFunction" class="inputForm">

            <input class="input" type="text" placeholder="Название сета" v-model="createForm.name"
                @blur="testName" @input="testName">
            <div class="red" v-show="isVisibleErrors.name">{{ errors.name }}</div>

            <input class="input" placeholder="Количетсво карт" v-model="createForm.number"
                @blur="testCardsNumber" @input="testCardsNumber">
            <div class="red" v-show="isVisibleErrors.number">{{ errors.number }}</div>

            <input class="input" type="text" placeholder="Дата выхода сета (чч.мм.гггг)" v-model="date" @blur="testDate"
                @input="testDate">
            <div class="red" v-show="isVisibleErrors.date">{{ errors.date }}</div>

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