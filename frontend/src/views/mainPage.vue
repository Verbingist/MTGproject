<script setup>
import headerComponent from "../components/headerComponent.vue";
import { reactive, ref, computed, watch, onMounted } from 'vue';
import axios from "axios";
import { useRoute } from 'vue-router'

let route = useRoute()

let firstPage = true;
let lastPage = true;
let page = computed(() => Number(route.query.page) || 1)
let tournaments = ref([]);

function loadTournaments() {
    axios.get(`http://localhost:8000/Tournaments?page=${page.value}`)
        .then(result => {
            tournaments.value = result.data.tournaments.data
            checkPages();
        })
        .catch(error => console.log(error));
}

function checkPages() {
    if (tournaments.value.length < 8) {
        lastPage = false;
    }
    else {
        lastPage = true;
    }
    if (page.value == 1) {
        firstPage = false;
    }
    else {
        firstPage = true;
    }
}

onMounted(() => {
    loadTournaments(page.value)
})

watch(page, (newPage) => {
    loadTournaments(newPage)
})


</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Турниры</h2>
        <div v-for="tournament in tournaments" :key="tournament.tournament_id" class="tournament">
            <h3 class="name">Название: {{ tournament.tournament_name }}</h3>
            <p>Формат: {{ tournament.format_name }}</p>
            <p>Статус: {{ tournament.status }}</p>
            <p>Дата: {{ tournament.tournament_date }}</p>
            <p>Описание: {{ tournament.tournament_description ? tournament.tournament_description : "Нет" }}</p>
            <button>Записаться</button>
        </div>
        <div class="pagination">
            <RouterLink v-show="firstPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page - 1 } }">← Предыдущая</RouterLink>
            <span>Страница: {{ page }}</span>
            <RouterLink v-show="lastPage" class="pagination-button"
                :to="{ path: $route.path, query: { page: page + 1 } }">Следующая →</RouterLink>
        </div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.tournament {
    width: 70%;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 20px;
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

.name {
    color: #C93814;
}
</style>