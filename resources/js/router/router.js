import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

import LoginView from '../views/LoginView.vue';
import DashboardView from '../views/Dashboard/DashboardView.vue';
import DashboardMenu from '../views/Dashboard/DashboardMenu.vue';
import UsersView from '../views/Dashboard/Users/UsersView.vue';
import BakeryView from '../views/Dashboard/Pastrie/PastriesView.vue';
import CloseView from '../views/Dashboard/Close/PastriesCloseView.vue';   

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
            { path: '', name: 'dashboard-home', component: DashboardMenu },
            { path: 'users', name: 'users', component: UsersView, meta: { roles: 'admin' } },
            { path: 'bakery', name: 'bakery', component: BakeryView, meta: { roles: ['admin', 'employee'] } }, 
            { path: 'close', name: 'close', component: CloseView, meta: { roles: ['admin', 'employee'] } },
        ],
        meta: { requiresAuth: true, roles: ['admin', 'employee'] }
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
            const { data } = await axios.get('/api/user');
            
            if (to.meta.roles) {
                const allowedRoles = Array.isArray(to.meta.roles)
                    ? to.meta.roles
                    : [to.meta.roles];

                if (!allowedRoles.includes(data.roles[0].name)) {
                    // rol no permitido
                    return { path: "/dashboard" };
                }
            }
            
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
