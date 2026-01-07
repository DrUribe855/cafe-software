import { defineStore } from 'pinia';
import axios from 'axios';

export const useEstablishmentStore = defineStore("establishment", {
    state: ()=> ({
        code: localStorage.getItem("establishmentCode") || null,

        refrigerators: [],        
        refrigeratorsLoaded: false 
    }),

    actions: {
        setCode(code){
            this.code = code;
            localStorage.setItem("establishmentCode", code);
        },

        clearCode(){
            this.code = null;
            localStorage.removeItem("establishmentCode");
        },

        getCode(){
            return this.code;
        },

        async fetchRefrigerators() {
            if (!this.code) return;

            try {
                const response = await axios.get(`/api/refrigerators/establishment/${this.code}`);

                this.refrigerators = response.data;
                this.refrigeratorsLoaded = true;

            } catch (err) {
                console.error("Error cargando neveras:", err);
            }
        }
    },
});
