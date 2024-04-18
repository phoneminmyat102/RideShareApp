import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Login from '../views/Login.vue'
import Index from '../views/Index.vue'
import Location from "../views/Location.vue";
import Map from "../views/Map.vue";
import Trip from "../views/Trip.vue";
import StandBy from "../views/StandBy.vue";
import Driver from "../views/Driver.vue";
import Driving from "../views/Driving.vue";
import axios from 'axios'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "login",
      component: Login,
    },
    {
      path: "/index",
      name: "index",
      component: Index,
    },
    {
      path: "/location",
      name: "location",
      component: Location,
    },
    {
      path: "/map",
      name: "map",
      component: Map,
    },
    {
      path: "/trip",
      name: "trip",
      component: Trip,
    },
    {
      path: "/standBy",
      name: "standBy",
      component: StandBy,
    },
    {
      path: "/driver",
      name: "driver",
      component: Driver,
    },
    {
      path: "/driving",
      name: "driving",
      component: Driving,
    }
  ],
});

router.beforeEach((to, from) => {

  if(to.name === 'login') {
    return true;
  }

  if(!localStorage.getItem('token')) {
    return {
      name: 'login'
    }
  }

  checkTokenAuthenticity();
})

const checkTokenAuthenticity = () => {
  axios.get('http://localhost:8000/api/user', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  })
  .then ((res) => {})
  .catch ((err)=>{
    localStorage.removeItem('token');
    router.push({name:'login'});
  })
}
export default router
