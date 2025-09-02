import { defineStore } from 'pinia';

export const useEstablishmentStore = defineStore("establishment", {
    state: ()=> ({
        code: localStorage.getItem("establishmentCode") || null,
        availableStores: [] as string[],
    }),

    actions: {
        setCode( code: string ){
            this.code = code;
            localStorage.setItem("establishmentCode", code);
        },

        clearCode(){
            this.code = null;
            localStorage.removeItem("establishmentCode");
        },

        setAvailableStores(stores: string[]){
            this.availableStores = stores;
        },

        getCode(){
            return this.code;
        }
    },
});
