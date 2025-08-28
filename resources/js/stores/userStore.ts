import { defineStore } from 'pinia';
import axios from 'axios';
import type { User } from '../interfaces/User';

export const useUserStore = defineStore('user', {
    state: ()=>({
        user: <User> {
            id: null,
            name: '',
            establishment_id: null,
            role: null,
        },
        isFetched: <boolean> false || null,
    }),
    actions: {
        async fetchUser(){

            if(this.isFetched) return this.user;

            try{
                const { data } = await axios.get('api/user');
                console.log('Información traida por data: ', data);
                const user = {
                   id: data.user.id,
                   name: data.user.name,
                   role: data.user.roles[0].name,
                };

                this.setUser(user);

            }catch (error){
                console.error('Error fetching user: ', error);
            }finally{
                this.isFetched = true;
            }

            return this.user;
        },

        setUser(user: User){
            this.user = user;
            localStorage.setItem('user', JSON.stringify(user));
        }
    }
});

