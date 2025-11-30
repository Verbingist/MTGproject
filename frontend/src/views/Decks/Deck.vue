<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, watch, onMounted } from 'vue';
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

let isOwnDeck = ref(false)

function isOwnDeckCheck() {
    axios.get(`http://localhost:8000/isOwnDeck/${deck_id.value}`)
        .then(result => {
            auth.value = result.data.isOwnDeck
        })
        .catch(error => addMessage("Не удалось идентифицировать пользователя"));
}

let cards = ref([]);

let route = useRoute()
let deck_id = computed(() => Number(route.query.id))


let deck = ref([]);

function loadDeck() {
    axios.get(`http://localhost:8000/Deck/${deck_id.value}`)
        .then(result => {
            deck.value = result.data.deck
            commander_id.value = result.data.deck.commander_card_id
            loadCommander()
        })
        .catch(error => addMessage("Не удалось загрузить карты"));
}


function loadCards() {
    axios.get(`http://localhost:8000/CardsInDeck/${deck_id.value}`)
        .then(result => {
            cards.value = result.data.cards
        })
        .catch(error => addMessage("Не удалось загрузить колоду"));
}

let commander = ref([]);
let commander_id = ref(0)

function loadCommander() {
    if (!commander_id.value) return;
    axios.get(`http://localhost:8000/Card/${commander_id.value}`)
        .then(result => {
            commander.value = result.data.card;
        })
        .catch(error => {
            commander.value = null;
            addMessage("Не удалось загрузить командира")
        });
}

onMounted(() => {
    loadCards()
    loadDeck()
    isOwnDeckCheck()
    authCheck()
})


let newCard = ref('');
let isSearchForAdd = ref(false);
let foundCardsForAdd = ref([]);

function startAdding() {
    if (newCard.value.length < 3) {
        isSearchForAdd.value = false;
    }
    else {
        isSearchForAdd.value = true
        axios.get(`http://localhost:8000/Cards?search=${newCard.value}`)
            .then(result => {
                foundCardsForAdd.value = result.data.cards
            })
            .catch(error => addMessage("Ошибка при поиске карт"))
    }
}

function addCardToDeck(card) {
    axios.post(`http://localhost:8000/addCardToDeck/${deck_id.value}`, {
        "card_name": card.card.card_name
    })
        .then(result => {
            loadCards();
        })
        .catch(error => addMessage("Не удалось добавить карту в колоду"))
}

function removeCardFromDeck(card) {
    axios.delete(`http://localhost:8000/removeCardFromDeck/${deck_id.value}`, {
        data: {
            "card_name": card.card.card_name
        }
    })
        .then(result => loadCards())
        .catch(error => addMessage('Не удалось удалить карту'))
}

function addCardToCollection(card) {
    axios.post(`http://localhost:8000/addCardToCollection`, {
        "card_name": card.card.card_name
    })
        .then(result => addMessage("Успешно добавлено в коллекцию"))
        .catch(error => addMessage("Не удалось добавить в коллекцию"))
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
        <h2>Колода {{ deck.deck_name }}</h2>
        <input v-show="isOwnDeck" id="addCards" class="input" type="text" placeholder="Добавить карту в колоду"
            v-model="newCard" @input="startAdding">
        <div v-show="isSearchForAdd" class="added-cards">
            <div v-for="card in foundCardsForAdd">
                <div @click="addCardToDeck(card)">{{ card.card.card_name }}</div>
            </div>
        </div>
        <div class="cards">
            <div class="card" v-show="commander_id">
                <h3>Коммандир колоды: {{ commander.card_name }}</h3>
                <img class="card-image" :src="commander.image_href">
                <p>
                    <RouterLink class="cardInfo" to="/">Подробное описание</RouterLink>
                </p>
                <button v-show="isOwnDeck" class="add-button" @click="removeCardFromDeck(card)">Удалить карту из
                    колоды</button>
                <button v-show="auth" class="add-button" @click="addCardToCollection(card)">Добавить в коллекцию</button>
            </div>
            <div v-for="card in cards" class="card">
                <h3>{{ card.card.card_name }}</h3>
                <img class="card-image" :src="card.card.image_href">
                <p>
                    <RouterLink class="cardInfo" :to="{ path: '/Card', query: { id: card.card.card_id } }">Подробное
                        описание</RouterLink>
                </p>
                <button v-show="isOwnDeck" class="add-button" @click="removeCardFromDeck(card)">Удалить карту из
                    колоды</button>
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

.cardInfo {
    text-decoration: none;
    color: #C93814;
}

.card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 30%;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 20px;
}

.cards {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: start;
}

.added-cards {
    width: 70%;
    border-radius: 20px;
    top: 285px;
    position: absolute;
    height: 200px;
    padding: 10px;
    border: 3px solid black;
    background-color: white;
}

.add-button {
    margin: 20px;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    color: white;
    border-radius: 20px;
}

.card-image {
    width: 300px;
}

.pagination {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 50px;
}

.pagination-button {
    text-decoration: none;
    color: #C93814;
    margin: 20px 20px;
}

.input {
    width: 70%;
    border-radius: 20px;
    margin: 20px;
    padding: 10px;
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