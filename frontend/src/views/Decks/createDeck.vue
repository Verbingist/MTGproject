<script setup>
import axios from 'axios';
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

let confirmData = ref(false);

let createDeckForm = reactive({
    deck_name: "",
    format_name: "",
    power_level: "",
    commander: "",
});

let isVisibleErrors = reactive({
    deck_name: false,
})

let errors = reactive({
    deck_nameError: "",
});

function testDeckName() {
    if (createDeckForm.deck_name.length > 3) {
        if (/^[A-Za-zА-Яа-я0-9 _-]+$/.test(createDeckForm.deck_name)) {
            isVisibleErrors.deck_name = false;
            errors.deck_nameError = "";
            return true;
        }
        else {
            isVisibleErrors.deck_name = true;
            errors.deck_nameError = "Название содержит только латинские буквы и цифры";
        }
    }
    else {
        isVisibleErrors.deck_name = true;
        errors.deck_nameError = "Название содержит хотя бы 4 символа";
    }
    return false;
}

function submitFunction(event) {
    event.preventDefault(event);
    if (!testDeckName()) return;
    axios.post('http://localhost:8000/Deck', {
        "deck_name": createDeckForm.deck_name,
        "format_name": createDeckForm.format_name,
        "power_level": createDeckForm.power_level,
        "commander_card_name": createDeckForm.commander
    })
        .then(result => {
            addMesage('Колода успешно создана')
            setTimeout(function () {
                router.push(`/Deck?id=${result.data.deck_id}`)
            }, 1000);
        })
        .catch(error => addMesage('Неудачная попытка создания колоды'))
}

let formats = ref([]);

function loadFormats() {
    axios.get('http://localhost:8000/Formats')
        .then(result => formats.value = result.data.formats)
        .catch(error => addMesage('Не удалось загрузить форматы'))
}

let power_levels = ref([1, 2, 3, 4, 5]);

let cards = ref([]);
let isSearch = ref(false);

function searchForCards() {
    if (createDeckForm.commander < 3) {
        isSearch.value = false;
        return;
    }
    isSearch.value = true;
    axios.get(`http://localhost:8000/Cards?search=${createDeckForm.commander}`)
        .then(result => {
            cards.value = result.data.cards
        })
        .catch(error => addMessage("Не удалось найти карты"));
}

function chooseCommander(card) {
    createDeckForm.commander = card.card_name;
}

function closeSearch() {
    isSearch.value = false;
}

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

onMounted(() => {
    loadFormats()
})
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

            <select class="input" v-model="createDeckForm.format_name">
                <option disabled value="">Формат колоды</option>
                <option :value="format.format_name" v-for="format in formats">{{ format.format_name }}</option>
            </select>

            <select class="input" v-model="createDeckForm.power_level">
                <option disabled value="">Уровень силы (не обязательно)</option>
                <option :value="level" v-for="level in power_levels">{{ level }}</option>
            </select>

            <input class="input" type="text" placeholder="Коммандир (не обязательно)" v-model="createDeckForm.commander"
                @input="searchForCards" @blur="closeSearch">
            <div v-show="isSearch" class="search-for-commander">
                <div v-for="card in cards">
                    <div @mousedown="chooseCommander(card.card)">{{ card.card.card_name }}</div>
                </div>
            </div>

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