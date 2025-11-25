<script setup>
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref, computed, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let route = useRoute()
let lot_id = computed(() => Number(route.query.id))

let lot = ref([]);

function loadLot() {
    axios.get(`http://localhost:8000/Lot/${lot_id.value}`)
        .then(result => {
            lot.value = result.data.lot
            console.log(result.data.lot)
            getUser(result.data.lot.user_id)
        })
        .catch(error => addMessage("Не удалось загрузить объявление"));
}

let user = ref([]);

function getUser(id) {
    axios.get(`http://localhost:8000/User/${id}`)
        .then(result => {
            user.value = result.data.user
        })
        .catch(error => addMessage("Не удалось получить пользователя"))
}


onMounted(() => {
    loadLot()
})

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
        <div class="">
            <h2>Название лота: {{ lot.lot_name }}</h2>
            <p>
                <RouterLink class="author" :to="{ path: '/UserProfile', query: { user: lot.user_id } }">Владелец: {{
                    user.login }}
                </RouterLink>
            </p>
            <p>Описание: {{ lot.lot_description }}</p>
            <p v-show="lot.price">Цена: {{ lot.price }}$</p>
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

.author {
    text-decoration: none;
    color: #C93814;
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