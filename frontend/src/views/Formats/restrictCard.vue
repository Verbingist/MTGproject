<script setup>
import axios from 'axios';
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

let formats = ref([]);

function loadFormats() {
    axios.get('http://localhost:8000/Formats')
        .then(result => formats.value = result.data.formats)
        .catch(error => addMesage('Не удалось загрузить форматы'))
}

let cards = ref([]);
let isSearch = ref(false);

function searchForCards() {
    if (createForm.card_name < 3) {
        isSearch.value = false;
        return;
    }
    isSearch.value = true;
    axios.get(`http://localhost:8000/Cards?search=${createForm.card_name}`)
        .then(result => {
            cards.value = result.data.cards
        })
        .catch(error => addMessage("Не удалось найти карты"));
}

function chooseCard(card) {
    createForm.card_name = card.card_name;
}

function closeSearch() {
    isSearch.value = false;
}

let confirmData = ref(false);
let router = useRouter()

let createForm = reactive({
    card_name: "",
    format_name: "",
    rest_type: "",
    description: "",
});

function submitFunction(event) {
    event.preventDefault(event);
    axios.post('http://localhost:8000/RestrictedCard', {
        "card_name": createForm.card_name,
        "format_name": createForm.format_name,
        'restriction_type': createForm.rest_type,
        "restriction_description": createForm.description || null,
    })
        .then(result => {
            addMesage('Карта успешно ограничена')
            setTimeout(function () {
                router.push(`/restrictedCards?format=${createForm.format_name}`)
            }, 1000);
        })
        .catch(error => {
            addMesage('Неудачная попытка ограничения')
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

onMounted(() => {
    loadFormats()
})
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Создать ограничение карты в формате</h2>
        <form @submit="submitFunction" class="inputForm">

            <input class="input" type="text" placeholder="Название карты" v-model="createForm.card_name"
                @input="searchForCards" @blur="closeSearch">
            <div v-show="isSearch" class="search-for-cards">
                <div v-for="card in cards">
                    <div @mousedown="chooseCard(card.card)">{{ card.card.card_name }}</div>
                </div>
            </div>

            <select class="input" v-model="createForm.format_name">
                <option disabled value="">Название формата</option>
                <option :value="format.format_name" v-for="format in formats">{{ format.format_name }}</option>
            </select>

            <select class="input" v-model="createForm.rest_type">
                <option disabled value="">Тип ограничения</option>
                <option value="Banned">Banned</option>
                <option value="Restricted">Restricted</option>
                <option value="Not legal">Not legal</option>
            </select>

            <textarea class="input" placeholder="Причина ограничения (не обязательно)"
                v-model="createForm.description"></textarea>

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

.search-for-cards {
    width: 500px;
    border-radius: 20px;
    top: 212px;
    padding: 10px;
    position: absolute;
    height: 200px;
    border: 3px solid black;
    background-color: white;
    overflow: scroll;
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