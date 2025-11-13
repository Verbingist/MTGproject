<script setup>
import headerComponent from "../components/headerComponent.vue";
import { reactive } from 'vue';

</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Профиль</h2>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>