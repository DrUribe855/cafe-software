import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

import LoginView from '../views/LoginView.vue';
import DashboardView from '../views/Dashboard/DashboardView.vue';
import DashboardMenu from '../views/Dashboard/DashboardMenu.vue';
import UsersView from '../views/Dashboard/Users/UsersView.vue';
import { useUserStore } from '@/stores/userStore';


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
        children: [
            { path: '', name: 'dashboard-home', component: DashboardMenu},
            { path: 'users', name: 'users', component: UsersView},
        ],
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from) => {
    const userStore = useUserStore();
    if(to.meta.requiresAuth){
        try{
            await axios.get('/api/user');
            return true;
        }catch(error){
            userStore.clearUser();
            return { path: "/" }
        }
    }else{
        if(to.path === '/'){
            console.log(to.path);
            return true
        }

        userStore.clearUser();
        return { path: '/'}
    }
})

export default router;