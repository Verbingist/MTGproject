import { createRouter, createWebHistory } from 'vue-router';
import mainPage from '../views/mainPage.vue';
import registrationPage from '../views/registrationPage.vue';
import loginPage from '../views/loginPage.vue';
import MyCards from '../views/MyCards.vue';
import MyDecks from '../views/MyDecks.vue';
import Users from '../views/Users.vue';
import info from '../views/info.vue';
import profile from '../views/profile.vue';



const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: mainPage,
      meta: {
        title: "Главная"
      }
    },
    {
      path: '/Registration',
      name: 'registration',
      component: registrationPage,
      meta: {
        title: "Регистрация"
      }
    },
    {
      path: '/Login',
      name: 'login',
      component: loginPage,
      meta: {
        title: "Вход"
      }
    },
    {
      path: '/Decks',
      name: 'decks',
      component: MyDecks,
      meta: {
        title: "Колоды"
      }
    },
    {
      path: '/Cards',
      name: 'cards',
      component: MyCards,
      meta: {
        title: "Коллекция"
      }
    },
    {
      path: '/Users',
      name: 'users',
      component: Users,
      meta: {
        title: "Пользователи"
      }
    },
    {
      path: '/Info',
      name: 'info',
      component: info,
      meta: {
        title: "Информация"
      }
    },
    {
      path: '/Profile',
      name: 'profile',
      component: profile,
      meta: {
        title: "Профиль"
      }
    }
  ],
})

router.afterEach((to) => {
  if (to.meta.title)
    document.title = to.meta.title.toString()
  else
    document.title = "404";
})

export default router