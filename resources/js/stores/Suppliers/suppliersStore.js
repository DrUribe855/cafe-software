import { defineStore } from 'pinia';
import axios from 'axios';

export const useSuppliersStore = defineStore("suppliers", {
    state: ()=> ({
        suppliers: [],
        suppliersLogs: [],
    }),

    actions: {
        setSuppliers(suppliers){
            this.suppliers = suppliers;
        },

        getSuppliers(){
            return this.suppliers;
        },

        setSuppliersLogs(logs){
            this.suppliersLogs = logs;
        },

        getSuppliersLogs(){
            return this.suppliersLogs;
        },

        addSupplierLog(log){
            this.suppliersLogs.push(log);
        },
    },
});
