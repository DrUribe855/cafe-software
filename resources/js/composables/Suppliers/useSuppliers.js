import {  useSuppliersStore } from '@/stores/Suppliers/suppliersStore';

export function useSuppliers() {
    /* ========== VARIABLES ========== */
    const supplierStore = useSuppliersStore();

    /* ========== FUNCIONES ========== */

    const fetchSuppliers = async () => {
        try{
            const { data } = await axios.get('/api/suppliers');
            supplierStore.setSuppliers(data.suppliers);
        }catch(error){
            console.error('Error cargando proveedores', error);
        }
    }



    return {
        // Variables


        // Funciones
        fetchSuppliers,
    }

}
