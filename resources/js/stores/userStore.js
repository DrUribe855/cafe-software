import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
    state: ()=>({
        user:  {
            id: null,
            name: '',
            role: null,
        },
        isFetched: false,
    }),
    actions: {
        async fetchUser(){

            // if(this.isFetched) return this.user;

            try{
                const { data } = await axios.get('/api/user');
                console.log('Información traida por data: ', data);
                const user = {
                   id: data.user.id,
                   name: data.user.name,
                   role: data.user.roles[0].name,
                };
                console.log('página recargada, el usuario es: ', user);
                this.setUser(user);

            }catch (error){
                console.error('Error fetching user: ', error);
            }finally{
                this.isFetched = true;
            }

            return this.user;
        },

        setUser(user){
            this.user = user;
            console.log('entro en seteo de usuario', JSON.stringify(this.user));
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

