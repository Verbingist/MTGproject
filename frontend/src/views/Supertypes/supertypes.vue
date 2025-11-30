<script setup>
import axios from 'axios';
import headerComponent from '../../components/headerComponent.vue';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

let supertypes = ref([]);

function loadSupertypes() {
    axios.get(`http://localhost:8000/Supertypes`)
        .then(result => {
            supertypes.value = result.data.supertypes
        })
        .catch(error => addMessage(error.response.data.message));
}

let createForm = reactive({
    name: "",
});

function submitFunction(event) {
    event.preventDefault(event);
    axios.post('http://localhost:8000/Supertype', {
        "supertype_name": createForm.name,
    })
        .then(result => {
            addMesage('Супертип успешно создан');
            loadSupertypes()
        })
        .catch(error => {
            addMesage(error.response.data.message)
        })
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

function deleteButton(supertype) {
    axios.delete(`http://localhost:8000/Supertype/${supertype.supertype_id}`)
        .then(result => {
            loadSupertypes();
        })
        .catch(error => {
            addMesage(error.response.data.message)
        })
}

onMounted(() => {
    loadSupertypes()
})
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Создать супертип</h2>
        <form @submit="submitFunction" class="inputForm">
            <input class="input" type="text" placeholder="Название супертипа" v-model="createForm.name">
            <button class="input backButton" type="submit">Подтвердить</button>
        </form>
        <div class="supertypes">
            <div class="supertype" v-for="supertype in supertypes">
                <p>{{ supertype.supertype_name }}</p>
                <button class="add-button" @click="deleteButton(supertype)">Удалить</button>
            </div>
        </div>
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

.supertype {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 22%;
    border: 3px solid black;
    border-radius: 20px;
    padding: 20px;
    margin: 20px;
}

.supertypes {
    width: 100%;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: start;
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

.add-button {
    margin: 10px;
    width: 60%;
    background: linear-gradient(90deg, rgba(201, 56, 20, 1) 0%, rgba(77, 45, 37, 1) 50%, rgba(0, 0, 0, 1) 100%);
    color: white;
    border-radius: 20px;
}

.search-for-commander {
    width: 500px;
    border-radius: 20px;
    top: 540px;
    padding: 10px;
    position: absolute;
    height: 200px;
    border: 3px solid black;
    background-color: white;
    overflow: scroll;
}

.confirm {
    display: flex;
    flex-direction: column;
    justify-content: start;
    position: absolute;
    width: 550px;
    height: 300px;
    border: 2px solid #C93814;
    background-color: white;
    border-radius: 20px;
    z-index: 2;
}

.red {
    font-weight: bold;
    color: black;
}

#blur {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 1;
}

.submitDot {
    margin: 10px;
}

.loginClass {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0px 10px;
    margin: 20px;
    height: 40px;
    width: 500px;
    border-radius: 10px;
}

.registrationButton {
    color: #C93814;
    text-decoration: none;
    margin: 0px 0px 0px 20px;
}

.submitheaderbox {
    display: flex;
    flex-direction: row;
    justify-content: center;
}

.submitbuttonbox {
    margin: 20px;
    display: flex;
    justify-content: center;
}

.submitbutton {
    margin: 0px 20px;
    width: 200px;
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