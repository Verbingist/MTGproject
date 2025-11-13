<script setup>
import headerComponent from '../components/headerComponent.vue';
import { reactive, ref } from 'vue';

let confirmData = ref(false);

let registrationForm = reactive({
    login: "",
    password: "",
});

let isVisibleErrors = reactive({
    login: false,
    password: false,
})

let errors = reactive({
    loginError: "",
    passwordError: "",
});

function loginOrEmailTest() {
    if (/^[A-Za-z0-9]+$/.test(registrationForm.login) || registrationForm.login == "") {
        if (registrationForm.login.length > 3 || registrationForm.login == "") {
            isVisibleErrors.login = false;
            errors.loginError = "";
        }
        else {
            isVisibleErrors.login = true;
            errors.loginError = "Логин содержит хотя бы 4 символа";
        }
    }
    else {
        isVisibleErrors.login = true;
        errors.loginError = "Логин содержит только латинские буквы и цифры";
    }
}

function passwordTest() {
    if (/^[A-Za-z0-9]+$/.test(registrationForm.password) || registrationForm.password == "") {
        if (registrationForm.password.length > 3 || registrationForm.password == "") {
            isVisibleErrors.password = false;
            errors.passwordError = "";
        }
        else {
            isVisibleErrors.password = true;
            errors.passwordError = "Пароль содержит хотя бы 4 символа";
        }
    }
    else {
        isVisibleErrors.password = true;
        errors.passwordError = "Пароль содержит только латинские буквы и цифры";
    }
}

function submitFunction(event) {
    event.preventDefault();
    if (/^[A-Za-z0-9]+$/.test(registrationForm.login)) {
        if (registrationForm.login.length > 3) {
            if (/^[A-Za-z0-9]+$/.test(registrationForm.password)) {
                if (registrationForm.password.length > 3) {
                    confirmData.value = true;
                }
            }
        }
    }
}

function closeWindow() {
    confirmData.value = false;
}

function reloadPage() {
    window.location.reload();
}
</script>

<template>
    <header>
        <headerComponent />
    </header>
    <main class="wrapper">
        <h2>Вход</h2>
        <form @submit="submitFunction" class="inputForm">

            <input id="login" class="input" type="text" placeholder="Почта или логин" v-model="registrationForm.login"
                @blur="loginOrEmailTest" @input="loginOrEmailTest">
            <div class="red" v-show="isVisibleErrors.login">{{ errors.loginError }}</div>

            <input id="password" class="input" type="text" placeholder="Пароль" v-model="registrationForm.password"
                @blur="passwordTest" @input="passwordTest">
            <div class="red" v-show="isVisibleErrors.password">{{ errors.passwordError }}</div>

            <div class="loginClass">
                <p>Еще нет аккаунта?<RouterLink class="registrationButton" to="/Registration">Регистрация</RouterLink>
                </p>
            </div>

            <button id="Submit" class="input backButton" type="submit">Подтвердить</button>
        </form>
        <div v-show="confirmData" class="confirm">
            <div class="submitheaderbox">
                <h2>Подтвердите</h2>
            </div>
            <div class="submitdatabox">
                <div class="submitDot">Логин: {{ registrationForm.login }}</div>
                <div class="submitDot">Пароль: {{ registrationForm.password }}</div>
            </div>
            <div class="submitbuttonbox">
                <button class="submitbutton" @click="closeWindow">Изменить форму</button>
                <button class="submitbutton" @click="reloadPage">Подтвердить</button>
            </div>
        </div>
    </main>
    <div v-show="confirmData" id="blur" @click="closeWindow"></div>
</template>

<style scoped>
.wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
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

.confirm {
    display: flex;
    flex-direction: column;
    justify-content: start;
    position: absolute;
    width: 550px;
    height: 450px;
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
</style>