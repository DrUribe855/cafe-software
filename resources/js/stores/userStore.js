import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
    state: ()=>({
        user:  {
            id: null,
            name: '',
            role: null,
        },
        isLoading: false,
        isFetched: false,
    }),
    actions: {
        async fetchUser(){

            this.isLoading = true;

            try{
                const { data } = await axios.get('/api/user');
                const user = {
                   id: data.user.id,
                   name: data.user.name,
                   role: data.user.roles[0].name,
                };
                console.log('página recargada, el usuario es: ', user);
                this.setUser(user);

            }catch (error){
                console.error('Error fetching user: ', error);
                this.clearUser();
            }finally{
                this.isLoading = false;
            }

            return this.user;
        },

        setUser(user){
            this.user = user;
            localStorage.setItem('user', JSON.stringify(this.user));
        },

        getUser(){
            const storedUser = localStorage.getItem('user');
            if(storedUser){
                this.user = JSON.parse(storedUser);
                // this.isFetched = true;
            }

            this.fetchUser();
        },

        clearUser(){
            this.user = {
                id: null,
                name: '',
                role: null
            };
            this.isFetched = false;
            localStorage.removeItem('user');
        }
    }
});

