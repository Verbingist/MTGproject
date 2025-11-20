<script setup>
import axios from 'axios';
axios.get('http://localhost:8000/sanctum/csrf-cookie')
</script>

<template>
  <router-view />
</template>

<style scoped></style>