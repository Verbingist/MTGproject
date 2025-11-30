<script setup>
import axios from 'axios';
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

let confirmData = ref(false);
let router = useRouter()

let createLotForm = reactive({
    lot_name: "",
    lot_description: "",
    price: ""
});

let isVisibleErrors = reactive({
    lot_name: false,
    lot_description: false,
    price: false
})

let errors = reactive({
    lot_name: "",
    lot_description: "",
    price: ""
});

function testLotName() {
    if (createLotForm.lot_name.length > 3) {
        if (/^[A-Za-zА-Яа-я0-9 _-]+$/.test(createLotForm.lot_name)) {
            isVisibleErrors.lot_name = false;
            errors.lot_name = "";
            return true;
        }
        else {
            isVisibleErrors.lot_name = true;
            errors.lot_name = "Название содержит только латинские буквы и цифры";
        }
    }
    else {
        isVisibleErrors.lot_name = true;
        errors.lot_name = "Название содержит хотя бы 4 символа";
    }
    return false;
}

function testLotDescription() {
    if (/^[A-Za-zА-Яа-я0-9 _-]+$/.test(createLotForm.lot_description) || createLotForm.lot_description == "") {
        isVisibleErrors.lot_description = false;
        errors.lot_description = "";
        return true;
    }
    else {
        isVisibleErrors.lot_description = true;
        errors.lot_description = "Описание содержит только латинские буквы и цифры";
    }
    return false;
}

function testLotPrice() {
    if (/^\d+([.]\d+)?$/.test(createLotForm.price) || createLotForm.price == "") {
        isVisibleErrors.price = false;
        errors.price = "";
        return true;
    }
    else {
        isVisibleErrors.price = true;
        errors.price = "Ценой должно быть число";
    }
    return false;
}


function submitFunction(event) {
    event.preventDefault(event);
    if (!testLotName()) return;
    if (!testLotDescription()) return;
    if (!testLotPrice()) return;
    axios.post('http://localhost:8000/Lot', {
        "lot_name": createLotForm.lot_name,
        "lot_description": createLotForm.lot_description,
        "price": Number(createLotForm.price) || null
    })
        .then(result => {
            addMesage('Объявление успешно создано')
            setTimeout(function () {
                router.push(`/MyLots`)
            }, 1000);
        })
        .catch(error => addMesage('Неудачная попытка создания объявления'))
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
        <h2>Создать лот</h2>
        <form @submit="submitFunction" class="inputForm">

            <input class="input" type="text" placeholder="Название объявления" v-model="createLotForm.lot_name"
                @blur="testLotName" @input="testLotName">
            <div class="red" v-show="isVisibleErrors.lot_name">{{ errors.lot_name }}</div>

            <textarea class="input" placeholder="Описание объявления (не обязательно)"
                v-model="createLotForm.lot_description" @blur="testLotDescription"
                @input="testLotDescription"></textarea>
            <div class="red" v-show="isVisibleErrors.lot_description">{{ errors.lot_description }}</div>

            <input class="input" type="text" placeholder="Цена (не обязательно)" v-model="createLotForm.price"
                @blur="testLotPrice" @input="testLotPrice">
            <div class="red" v-show="isVisibleErrors.price">{{ errors.price }}</div>

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