import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

import LoginView from '../views/LoginView.vue';
import DashboardView from '../views/DashboardView.vue';

const routes = [
    {
        path: '/',
        name: 'login',
        component: LoginView
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardView,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from, next) => {
    if(to.meta.requiresAuth){
        try{
            await axios.get('/api/user');
            next();
        }catch(error){
            next("/login");
        }
    }else{
        next();
    }
})

export default router;
