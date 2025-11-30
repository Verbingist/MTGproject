<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let auth = ref(false)

function authCheck() {
    axios.get('http://localhost:8000/IsAuth')
        .then(result => {
            if (result.data.role != 4) {
                auth.value = true;
            }
        })
        .catch(error => addMessage('Не удалось проверить авторизацию'))
}

let route = useRoute()
let card_id = computed(() => Number(route.query.id))

let card = ref([]);

function loadCard() {
    axios.get(`http://localhost:8000/Card/${card_id.value}`)
        .then(result => {
            card.value = result.data
        })
        .catch(error => addMessage("Не удалось загрузить карту"));
}

onMounted(() => {
    loadCard()
})

function addCardToCollection(card) {
    axios.post(`http://localhost:8000/addCardToCollection`, {
        "card_name": card.card.card_name
    })
        .then(result => {
            addMessage("Карта успешно добавлена в коллекцию")
        })
        .catch(error => addMessage("Не удалось добавить карту в коллекцию"));
}

let globalMessage = ref([]);
let visibleMessage = ref(false);

function addMessage(message) {
    globalMessage.value.push(message);
    visibleMessage.value = true;
    setTimeout(function () {
        visibleMessage.value = false;
        globalMessage.value.pop();
    }, 3000)
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>{{ card.card?.card_name }}</h2>
        <div class="card">
            <div class="card-image-box">
                <img class="card-image" :src="card.card?.image_href">
            </div>
            <div class="card-info">
                <div class="mana-value">
                    <p class="mana-value-data" v-show="card.mana_value?.white_mana">Белая: {{
                        card.mana_value?.white_mana }}</p>
                    <p class="mana-value-data" v-show="card.mana_value?.blue_mana">Синяя: {{
                        card.mana_value?.blue_mana }}</p>
                    <p class="mana-value-data" v-show="card.mana_value?.black_mana">Черная: {{
                        card.mana_value?.black_mana }}</p>
                    <p class="mana-value-data" v-show="card.mana_value?.red_mana">Красная: {{ card.mana_value?.red_mana
                        }}</p>
                    <p class="mana-value-data" v-show="card.mana_value?.green_mana">Зеленая: {{
                        card.mana_value?.green_mana }}</p>
                    <p class="mana-value-data" v-show="card.mana_value?.colorless_mana">Бесцветная: {{
                        card.mana_value?.colorless_mana }}</p>
                </div>
                <div class="types">
                    <div class="types-data" v-for="supertype in card.supertypes">{{ supertype.supertype_name }}</div>
                    <div class="types-data" v-for="type in card.types">{{ type.type_name }}</div>
                    <div class="types-data" v-show="card.subtypes?.[0]">-</div>
                    <div class="types-data" v-for="subtype in card.subtypes">{{ subtype.subtype_name }}</div>
                </div>
                <div class="text">{{ card.card?.text_rules }}</div>
                <div class="text"><i>{{ card.card?.flavor_text }}</i></div>

                <div v-show="card.card?.power || card.card?.thoughtness">Сила/Выносливость {{ card.card?.power }}/{{
                    card.card?.thoughtness }}</div>

                <div>Художник: {{ card.card?.illustration_author }}</div>
                <div>Цена: {{ card.card?.price }}$</div>
                <button v-show="auth" class="add-button" @click="addCardToCollection(card)">Добавить в коллекцию</button>
            </div>
        </div>
        <div class="message" v-show="visibleMessage">{{ globalMessage.toString() }}</div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.card {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    width: 100%;
    padding: 20px;
}

.card-image {
    width: 300px;
}

.card-image-box {
    display: flex;
    width: 40%;
    margin-left: 10%;
}

.add-button {
    margin: 20px;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    color: white;
    border-radius: 20px;
}

.card-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: start;
    width: 40%;
    margin-right: 10%;
}

.mana-value {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
}

.mana-value-data {
    margin-right: 20px;
}

.types {
    display: flex;
    flex-direction: row;
}

.types-data {
    margin-right: 20px;
}

.text {
    margin: 10px 0px;
}

.input {
    width: 70%;
    padding: 10px;
    border-radius: 20px;
    margin: 20px;
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