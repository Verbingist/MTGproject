<script setup>
import headerComponent from "../components/headerComponent.vue";
import { reactive } from 'vue';

let posts = [
    {
        id: 1,
        name: "Введение в Vue 3",
        description: "Краткое руководство по Composition API, реактивности и рендер-функциям.",
    },
    {
        id: 2,
        name: "Оптимизация запросов к базе",
        description: "Как уменьшить количество запросов и правильно кешировать результаты.",
    },
    {
        id: 3,
        name: "Черновик: идеи для API",
        description: "Наброски по версии API, формату ошибок и пагинации.",
    },
    {
        id: 4,
        name: "Большой пост с HTML в теле",
        description: "<p>Пример поста с <strong>HTML</strong> внутри. Используйте sanitizer перед render.</p>",
    },
    {
        id: 5,
        name: "Короткий",
        description: "Одно предложение.",
    }
]

</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Моя коллекция</h2>
        <div v-for="post in posts" :key="post.id" class="post">
            <h3>{{ post.name }}</h3>
            <p>{{ post.description }}</p>
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

.post {
    width: 70%;
    height: 200px;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 20px;
}
</style>