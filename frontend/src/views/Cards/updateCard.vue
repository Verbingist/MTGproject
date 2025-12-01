<script setup>
import axios from 'axios';
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

let confirmData = ref(false);
let router = useRouter();

let createForm = reactive({
    card_name: "",
    price: "",
    text_rules: "",
    illustration_author: "",
    flavor_text: "",
    image_href: "",
    power: "",
    thoughtness: "",
    keywords: [],
    types: [],
    subtypes: [],
    supertypes: [],
    white_mana: "",
    blue_mana: "",
    black_mana: "",
    red_mana: "",
    green_mana: "",
    colorless_mana: "",
});

function submitFunction(event) {
    event.preventDefault(event);
    axios.put(`http://localhost:8000/Card/${updated_card_id.value}`, {
        "card_name": createForm.card_name || null,
        "price": Number(createForm.price) || null,
        "text_rules": createForm.text_rules || null,
        "illustration_author": createForm.illustration_author || null,
        "flavor_text": createForm.flavor_text || null,
        "image_href": createForm.image_href || null,
        "power": Number(createForm.power) || null,
        "thoughtness": Number(createForm.thoughtness) || null,
        "keywords": createForm.keywords.length ? createForm.keywords : null,
        "types": createForm.types.length ? createForm.types : null,
        "subtypes": createForm.subtypes.length ? createForm.subtypes : null,
        "supertypes": createForm.supertypes.length ? createForm.supertypes : null,
        "white_mana": Number(createForm.white_mana) || null,
        "blue_mana": Number(createForm.blue_mana) || null,
        "black_mana": Number(createForm.black_mana) || null,
        "red_mana": Number(createForm.red_mana) || null,
        "green_mana": Number(createForm.green_mana) || null,
        "colorless_mana": Number(createForm.colorless_mana) || null,
    })
        .then(result => {
            addMesage('Карта успешно обновлена');
        })
        .catch(error => {
            addMesage(error.response.data.message);
        });
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

let keywords = ref([]);
let types = ref([]);
let subtypes = ref([]);
let supertypes = ref([]);

function loadKeywords() {
    axios.get('http://localhost:8000/Keywords')
        .then(result => keywords.value = result.data.keywords)
        .catch(error => addMesage(error.response.data.message))
}

function loadTypes() {
    axios.get('http://localhost:8000/Types')
        .then(result => types.value = result.data.types)
        .catch(error => addMesage(error.response.data.message))
}

function loadSubtypes() {
    axios.get('http://localhost:8000/Subtypes')
        .then(result => subtypes.value = result.data.subtypes)
        .catch(error => addMesage(error.response.data.message))
}

function loadSupertypes() {
    axios.get('http://localhost:8000/Supertypes')
        .then(result => supertypes.value = result.data.supertypes)
        .catch(error => addMesage(error.response.data.message))
}

onMounted(() => {
    loadSubtypes()
    loadSupertypes()
    loadTypes()
    loadKeywords()
})

let currentType = ref("");
let currentSubtype = ref("");
let currentSupertype = ref("");
let currentKeyword = ref("");

function addType() {
    if (createForm.types.includes(currentType.value)) return;
    createForm.types.push(currentType.value);
}

function addSupertype() {
    if (createForm.supertypes.includes(currentSupertype.value)) return;
    createForm.supertypes.push(currentSupertype.value);
}

function addSubtype() {
    if (createForm.subtypes.includes(currentSubtype.value)) return;
    createForm.subtypes.push(currentSubtype.value);
}

function addKeyword() {
    if (createForm.keywords.includes(currentKeyword.value)) return;
    createForm.keywords.push(currentKeyword.value);
}

let updated_card = ref('');
let updated_card_id = ref(0);
let isSearch = ref(false);
let foundCards = ref([]);

function startSearch() {
    if (updated_card.value.length < 3) {
        isSearch.value = false;
    }
    else {
        isSearch.value = true
        axios.get(`http://localhost:8000/Cards?search=${updated_card.value}`)
            .then(result => {
                foundCards.value = result.data.cards
            })
            .catch(error => addMesage(error.response.data.message))
    }
}

function chooseCard(card) {
    updated_card.value = card.card.card_name;
    isSearch.value = false;
    updated_card_id.value = card.card.card_id
}

function closeSearch() {
    isSearch.value = false;
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Обновить карту</h2>
        <form @submit="submitFunction" class="inputForm">

            <input class="input" type="text" placeholder="Выбор карты" v-model="updated_card" @input="startSearch">
            <div v-show="isSearch" class="added-cards">
                <div v-for="card in foundCards">
                    <div @click="chooseCard(card)">{{ card.card.card_name }}</div>
                </div>
            </div>

            <input class="input" type="text" placeholder="Новое название карты (не обязательно)" v-model="createForm.card_name">

            <input class="input" type="text" placeholder="Цена карты (не обязательно)" v-model="createForm.price">

            <textarea class="input" placeholder="Текст карты (не обязательно)"
                v-model="createForm.text_rules"></textarea>

            <input class="input" type="text" placeholder="Автор иллюстрации (не обязательно)" v-model="createForm.illustration_author">

            <textarea class="input" type="text" placeholder="Художественный текст (не обязательно)"
                v-model="createForm.flavor_text"></textarea>

            <input class="input" type="text" placeholder="Ссылка на иллюстрацию (не обязательно)"
                v-model="createForm.image_href">

            <input class="input" type="text" placeholder="Сила (не обязательно)" v-model="createForm.power">

            <input class="input" type="text" placeholder="Выносливость (не обязательно)"
                v-model="createForm.thoughtness">

            <div class="choose-block">
                <select class="input" v-model="currentType" @change="addType">
                    <option disabled value="">Тип карты (сохраняется каждый новый выбор)</option>
                    <option :value="type.type_name" v-for="type in types">{{ type.type_name }}</option>
                </select>
                <div class="absolute-message">{{ createForm.types.length ? createForm.types : null }}</div>
            </div>

            <div class="choose-block">
                <select class="input" v-model="currentSubtype" @change="addSubtype">
                    <option disabled value="">Подтип карты (сохраняется каждый новый выбор)</option>
                    <option :value="subtype.subtype_name" v-for="subtype in subtypes">{{ subtype.subtype_name }}
                    </option>
                </select>
                <div class="absolute-message">{{ createForm.subtypes.length ? createForm.subtypes : null }}</div>
            </div>

            <div class="choose-block">
                <select class="input" v-model="currentSupertype" @change="addSupertype">
                    <option disabled value="">Супертип карты (сохраняется каждый новый выбор)</option>
                    <option :value="supertype.supertype_name" v-for="supertype in supertypes">{{
                        supertype.supertype_name }}
                    </option>
                </select>
                <div class="absolute-message">{{ createForm.supertypes.length ? createForm.supertypes : null }}</div>
            </div>

            <div class="choose-block">
                <select class="input" v-model="currentKeyword" @change="addKeyword">
                    <option disabled value="">Кейворд карты (сохраняется каждый новый выбор)</option>
                    <option :value="keyword.keyword_name" v-for="keyword in keywords">{{
                        keyword.keyword_name }}
                    </option>
                </select>
                <div class="absolute-message">{{ createForm.keywords.length ? createForm.keywords : null }}</div>
            </div>

            <div class="manavalue">
                <p>Мана стоимость (не обязательно)</p>
                <div class="mana-choose">
                    <p>Белая мана</p>
                    <input placeholder="белая" class="mana-input" v-model="createForm.white_mana">
                </div>
                <div class="mana-choose">
                    <p>Синяя мана</p>
                    <input placeholder="синяя" class="mana-input" v-model="createForm.blue_mana">
                </div>
                <div class="mana-choose">
                    <p>Черная мана</p>
                    <input placeholder="черная" class="mana-input" v-model="createForm.black_mana">
                </div>
                <div class="mana-choose">
                    <p>Красная мана</p>
                    <input placeholder="красная" class="mana-input" v-model="createForm.red_mana">
                </div>
                <div class="mana-choose">
                    <p>Зеленая мана</p>
                    <input placeholder="зеленая" class="mana-input" v-model="createForm.green_mana">
                </div>
                <div class="mana-choose">
                    <p>Бесцветная мана</p>
                    <input placeholder="бесцветная" class="mana-input" v-model="createForm.colorless_mana">
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

.choose-block {
    position: relative;
    display: inline-block;
}

.absolute-message {
    position: absolute;
    top: 34%;
    left: 100%;
    margin-left: 10px;
    white-space: nowrap;
}

.manavalue {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0px 30px;
    flex-direction: column;
    width: 500px;
    border: 1px solid black;
    border-radius: 10px;
}

.added-cards {
    width: 600px;
    border-radius: 20px;
    top: 212px;
    padding: 10px;
    position: absolute;
    height: 180px;
    border: 3px solid black;
    background-color: white;
}

.mana-choose {
    display: flex;
    width: 100%;
    flex-direction: row;
    justify-content: start;
    align-items: center;
}

.mana-input {
    width: 120px;
    border-radius: 10px;
    margin-left: 20px;
    height: 40px;
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