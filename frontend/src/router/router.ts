import { createRouter, createWebHistory } from 'vue-router';
import mainPage from '../views/mainPage.vue';
import registrationPage from '../views/Auth/registrationPage.vue';
import loginPage from '../views/Auth/loginPage.vue';
import MyDecks from '../views/Decks/MyDecks.vue';
import Users from '../views/Users.vue';
import profile from '../views/Auth/profile.vue';
import Deck from '../views/Decks/Deck.vue';
import userCards from '../views/Cards/userCards.vue';
import UserDecks from '../views/Decks/userDecks.vue';
import UserProfile from '../views/Auth/UserProfile.vue';
import Lots from '../views/Lots/Lots.vue';
import Cards from '../views/Cards/cards.vue';
import MyCards from '../views/Cards/MyCards.vue';
import createDeck from '../views/Decks/createDeck.vue';
import Decks from '../views/Decks/Decks.vue';
import card from '../views/Cards/card.vue';
import MyLots from '../views/Lots/MyLots.vue';
import UserLots from '../views/Lots/UserLots.vue';
import Lot from '../views/Lots/Lot.vue';
import createLot from '../views/Lots/createLot.vue';

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
      path: '/MyDecks',
      name: 'mydecks',
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
    },
    {
      path: '/Decks',
      name: 'Decks',
      component: Decks,
      meta: {
        title: "Колоды"
      }
    },
    {
      path: '/Card',
      name: 'card',
      component: card,
      meta: {
        title: "Карта"
      }
    },
    {
      path: '/MyLots',
      name: 'mylots',
      component: MyLots,
      meta: {
        title: "Мои лоты"
      }
    },
    {
      path: '/UserLots',
      name: 'userlots',
      component: UserLots,
      meta: {
        title: "Объявления пользователя"
      }
    },
    {
      path: '/Lot',
      name: 'lot',
      component: Lot,
      meta: {
        title: "Объявление"
      }
    },
    {
      path: '/createLot',
      name: 'createlot',
      component: createLot,
      meta: {
        title: "Создать объявление"
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