<script setup>
import headerComponent from '../components/headerComponent.vue';
import { reactive, ref } from 'vue';

let confirmData = ref(false);

let registrationForm = reactive({
    firstname: "",
    lastname: "",
    email: "",
    login: "",
    password: "",
    passwordRepeat: "",
    age: ""
});

let isVisibleErrors = reactive({
    firstname: false,
    lastname: false,
    email: false,
    login: false,
    password: false,
    passwordRepeat: false,
    age: false
})

let errors = reactive({
    firstnameError: "",
    lastnameError: "",
    emailError: "",
    loginError: "",
    passwordError: "",
    passwordRepeatError: "",
    ageError: ""
});

function firstnameTest() {
    if (/^[A-Za-zА-Яа-яё]+$/.test(registrationForm.firstname) || registrationForm.firstname == "") {
        if (registrationForm.firstname.length > 3 || registrationForm.firstname == "") {
            isVisibleErrors.firstname = false;
            errors.firstnameError = "";
        }
        else {
            isVisibleErrors.firstname = true;
            errors.firstnameError = "Имя содержит хотя бы 4 символа";
        }
    }
    else {
        isVisibleErrors.firstname = true;
        errors.firstnameError = "Имя содержит только буквы";
    }
}

function lastnameTest() {
    if (/^[A-Za-zА-Яа-яё]+$/.test(registrationForm.lastname) || registrationForm.lastname == "") {
        if (registrationForm.lastname.length > 3 || registrationForm.lastname == "") {
            isVisibleErrors.lastname = false;
            errors.lastnameError = "";
        }
        else {
            isVisibleErrors.lastname = true;
            errors.lastnameError = "Фамилия содержит хотя бы 4 символа";
        }
    }
    else {
        isVisibleErrors.lastname = true;
        errors.lastnameError = "Фамилия содержит только буквы";
    }
}

function emailTest() {
    if (/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(registrationForm.email) || registrationForm.email === "") {
        if (registrationForm.email.length > 3 || registrationForm.email == "") {
            isVisibleErrors.email = false;
            errors.emailError = "";
        }
        else {
            isVisibleErrors.email = true;
            errors.emailError = "Email содержит хотя бы 4 символа";
        }
    }
    else {
        isVisibleErrors.email = true;
        errors.emailError = "Email содержит только буквы и специальные символы";
    }
}

function loginTest() {
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

function passwordRepeatTest() {
    if (registrationForm.password == registrationForm.passwordRepeat || registrationForm.passwordRepeat == "") {
        isVisibleErrors.passwordRepeat = false;
        errors.passwordRepeatError = "";
    }
    else {
        isVisibleErrors.passwordRepeat = true;
        errors.passwordRepeatError = "Пароли не совпадают";
    }
}

function ageTest() {
    if (/^[0-9]+$/.test(registrationForm.age) || registrationForm.age == "") {
        if (Number(registrationForm.age) > 10 && Number(registrationForm.age) < 90 || registrationForm.age == "") {
            isVisibleErrors.ageError = false;
            errors.ageError = "";
        }
        else {
            isVisibleErrors.ageError = true;
            errors.ageError = "Возраст между 10 и 90";
        }
    }
    else {
        isVisibleErrors.ageError = true;
        errors.ageError = "Возраст содержит только цифры";
    }
}

function submitFunction(event) {
    event.preventDefault();
    if (/^[A-Za-zА-Яа-яё]+$/.test(registrationForm.firstname)) {
        if (registrationForm.firstname.length > 3) {
            if (/^[A-Za-zА-Яа-яё]+$/.test(registrationForm.lastname)) {
                if (registrationForm.lastname.length > 3) {
                    if (/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(registrationForm.email)) {
                        if (registrationForm.email.length > 3) {
                            if (/^[A-Za-z0-9]+$/.test(registrationForm.login)) {
                                if (registrationForm.login.length > 3) {
                                    if (/^[A-Za-z0-9]+$/.test(registrationForm.password)) {
                                        if (registrationForm.password.length > 3) {
                                            if (registrationForm.password == registrationForm.passwordRepeat) {
                                                if (/^[0-9]+$/.test(registrationForm.age)) {
                                                    if (Number(registrationForm.age) > 10 && Number(registrationForm.age) < 90) {
                                                        confirmData.value = true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
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
        <h2>Регистрация</h2>
        <form @submit="submitFunction" class="inputForm">
            <input id="firstname" class="input" type="text" placeholder="Имя" v-model="registrationForm.firstname"
                @blur="firstnameTest" @input="firstnameTest">
            <div class="red" v-show="isVisibleErrors.firstname">{{ errors.firstnameError }}</div>

            <input id="lastname" class="input" type="text" placeholder="Фамилия" v-model="registrationForm.lastname"
                @blur="lastnameTest" @input="lastnameTest">
            <div class="red" v-show="isVisibleErrors.lastname">{{ errors.lastnameError }}</div>

            <input id="email" class="input" type="text" placeholder="Почта" v-model="registrationForm.email"
                @blur="emailTest" @input="emailTest">
            <div class="red" v-show="isVisibleErrors.email">{{ errors.emailError }}</div>

            <input id="login" class="input" type="text" placeholder="Логин" v-model="registrationForm.login"
                @blur="loginTest" @input="loginTest">
            <div class="red" v-show="isVisibleErrors.login">{{ errors.loginError }}</div>

            <input id="password" class="input" type="text" placeholder="Пароль" v-model="registrationForm.password"
                @blur="passwordTest" @input="passwordTest">
            <div class="red" v-show="isVisibleErrors.password">{{ errors.passwordError }}</div>

            <input id="password2" class="input" type="text" placeholder="Повторите пароль"
                v-model="registrationForm.passwordRepeat" @blur="passwordRepeatTest" @input="passwordRepeatTest">
            <div class="red" v-show="isVisibleErrors.passwordRepeat">{{ errors.passwordRepeatError }}</div>

            <input id="age" class="input" type="text" placeholder="Возраст" v-model="registrationForm.age"
                @blur="ageTest" @input="ageTest">
            <div class="red" v-show="isVisibleErrors.ageError">{{ errors.ageError }}</div>

            <div class="loginClass">
                <p>Уже есть аккаунт?<RouterLink class="loginButton" to="/Login">Вход</RouterLink></p>
            </div>

            <button id="Submit" class="input backButton" type="submit">Подтвердить</button>
        </form>
        <div v-show="confirmData" class="confirm">
            <div class="submitheaderbox">
                <h2>Подтвердите</h2>
            </div>
            <div class="submitdatabox">
                <div class="submitDot">Имя: {{ registrationForm.firstname }}</div>
                <div class="submitDot">Фамилия: {{ registrationForm.lastname }}</div>
                <div class="submitDot">Почта: {{ registrationForm.email }}</div>
                <div class="submitDot">Логин: {{ registrationForm.login }}</div>
                <div class="submitDot">Пароль: {{ registrationForm.password }}</div>
                <div class="submitDot">Возраст: {{ registrationForm.age }}</div>
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

.loginButton {
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