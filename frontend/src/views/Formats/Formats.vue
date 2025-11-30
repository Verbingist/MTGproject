<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";

let formats = ref([]);

function loadFormats() {
    axios.get(`http://localhost:8000/Formats`)
        .then(result => {
            formats.value = result.data.formats
        })
        .catch(error => addMessage('Не удалось загрузить форматы'));
}

onMounted(() => {
    loadFormats()
})

let globalMessage = ref([]);
let visibleMessage = ref(false);

function addMessage(message) {
    globalMessage.value.push(message);
    visibleMessage.value = true;
    setTimeout(function () {
        visibleMessage.value = false;
        globalMessage.value.pop();
    }, 3000);
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Форматы</h2>
        <div class="formats">
            <div v-for="format in formats" class="format">
                <h3>{{ format.format_name }}</h3>
                <p>{{ format.format_description }}</p>
                <p>Минимум карт в колоде: {{ format.min_cards_in_deck }}</p>
                <p>Максимум карт в колоде: {{ format.max_cards_in_deck ? format.max_cards_in_deck : "Нет" }}</p>
                <RouterLink class="info" :to="{ path: '/restrictedCards', query: { format: format.format_name } }">
                    Список ограниченных карт
                </RouterLink>
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

.format {
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

.formats {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: start;
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

.info {
    text-decoration: none;
    color: #C93814;
}
</style>