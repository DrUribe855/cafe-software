import { defineStore } from 'pinia';
import axios from 'axios';

export const useMenuStore = defineStore("menu", {
    state: ()=> ({
        categories: [],
        products: [],
    }),

    actions: {
        setCategories(categories){
            this.categories = categories;
        },

        getCategories(){
            return this.categories;
        },

        addCategory(category){
            this.categories.push(category);
        },

        removeCategory(id){
            this.categories = this.categories.filter(category => category.id !== id);
        },

        setProducts(products){
            this.products = products;
        },

        getProducts(){
            return this.products;
        },
    },
});
