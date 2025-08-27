import { defineStore } from 'pinia';
import axios from 'axios';
import type { User } from '../interfaces/User';

export const useUserStore = defineStore('user', {
    state: ()=>({
        user: <User> {
            id: null,
            name: '',
            establishment_id: null,
        },
        isFetched: <boolean> false || null,
    }),

    actions: {
        async fetchUser(){

            if(this.isFetched) return this.user;

            try{
                const { data } = await axios.get('api/user');
                this.user = {
                   id: data.id,
                   name: data.name,
                   establishment_id: data.establishment_id
                };

            }catch (error){
                console.error('Error fetching user: ', error);
            }finally{
                this.isFetched = true;
            }

            return this.user;
        }
    }
});

