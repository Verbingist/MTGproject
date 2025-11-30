<script setup>
import headerComponent from '../components/headerComponent.vue';
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2 class="logo">Страница для админа</h2>
        <div class="href-column">
            <RouterLink class="sub-navigation" to="/">Создать карту
            </RouterLink>
            <RouterLink class="sub-navigation" to="/">Изменить карту
            </RouterLink>
            <RouterLink class="sub-navigation" to="/">Удалить карту
            </RouterLink>
        </div>
        <div class="href-column">
            <RouterLink class="sub-navigation" to="/Subtypes">Подтипы
            </RouterLink>
            <RouterLink class="sub-navigation" to="/Types">Типы
            </RouterLink>
            <RouterLink class="sub-navigation" to="/Supertypes">Супертипы
            </RouterLink>
        </div>
        <div class="href-column">
            <RouterLink class="sub-navigation" to="/">Создать сет
            </RouterLink>
            <RouterLink class="sub-navigation" to="/">Удалить сет
            </RouterLink>
            <RouterLink class="sub-navigation" to="/">Добавить/Удалить карту из сета
            </RouterLink>
        </div>
    </main>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
}

.logo {
    display: flex;
    justify-content: center;
    width: 100%;
}

.href-column {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 30px;
}

.sub-navigation {
    margin: 20px 0px;
    text-decoration: none;
    color: #C93814;
}
</style>