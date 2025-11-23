import { createRouter, createWebHistory } from 'vue-router';
import mainPage from '../views/mainPage.vue';
import registrationPage from '../views/registrationPage.vue';
import loginPage from '../views/loginPage.vue';
import MyDecks from '../views/MyDecks.vue';
import Users from '../views/Users.vue';
import profile from '../views/profile.vue';
import Deck from '../views/Deck.vue';
import userCards from '../views/userCards.vue';
import UserDecks from '../views/userDecks.vue';
import UserProfile from '../views/UserProfile.vue';
import Lots from '../views/Lots.vue';
import Cards from '../views/cards.vue';
import MyCards from '../views/MyCards.vue';
import createDeck from '../views/createDeck.vue';

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
      path: '/MyCards',
      name: 'MyCards',
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
      path: '/Profile',
      name: 'profile',
      component: profile,
      meta: {
        title: "Профиль"
      }
    },
    {
      path: '/Deck',
      name: 'deck',
      component: Deck,
      meta: {
        title: "Колода"
      }
    },
    {
      path: '/UserCards',
      name: 'UserCards',
      component: userCards,
      meta: {
        title: "Коллекция пользователя"
      }
    },
    {
      path: '/UserDecks',
      name: 'UserDecks',
      component: UserDecks,
      meta: {
        title: "Колоды пользователя"
      }
    },
    {
      path: '/UserProfile',
      name: 'UserProfile',
      component: UserProfile,
      meta: {
        title: "Профиль пользователя"
      }
    },
    {
      path: '/Lots',
      name: 'Lots',
      component: Lots,
      meta: {
        title: "Объявления на продажу"
      }
    },
    {
      path: '/Cards',
      name: 'cards',
      component: Cards,
      meta: {
        title: "Карты"
      }
    },
    {
      path: '/createDeck',
      name: 'createDeck',
      component: createDeck,
      meta: {
        title: "Создание колоды"
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