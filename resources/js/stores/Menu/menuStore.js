import { defineStore } from 'pinia';
import axios from 'axios';

export const useMenuStore = defineStore("menu", {
    state: ()=> ({
        categories: [],
        products: [],
    }),

    actions: {

        /* Funciones categorias */
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

        /* Funciones productos */

        setProducts(products){
            this.products = products;
        },

        getProducts(){
            return this.products;
        },

        addProduct(product){
            this.products.push(product);
        },

        editProduct(product){
            const index = this.products.findIndex(p => p.id === product.id);
            this.products.splice(index, 1, product);
        },

        removeProduct(id){
            this.products = this.products.filter(product => product.id !== id);
        }
    },
});
