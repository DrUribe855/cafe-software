import { ref, computed, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useSuppliersStore } from '@/stores/Suppliers/suppliersStore';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import { useUserStore } from '@/stores/userStore';
export function useSuppliers() {
    /* ========== VARIABLES ========== */
    const supplierStore = useSuppliersStore();
    const { suppliersLogs } = storeToRefs(supplierStore);
    const establishmentStore = useEstablishmentStore();
    const userStore = useUserStore();
    const supplier  = ref(null);
    const images    = ref([]);

    const filteredImages = computed( () => {
        console.log('supplierslog lista: ', suppliersLogs.value);
        console.log('valor seleccionado: ', supplier.value);

        if(!suppliersLogs.value.length) return [];

        if(!supplier.value) return suppliersLogs.value;

        return suppliersLogs.value.filter( log => log.supplier_id == supplier.value );
    });

    /* ========== FUNCIONES ========== */

    const fetchSuppliers = async () => {
        try{
            const { data } = await axios.get('/api/suppliers');
            supplierStore.setSuppliers(data.suppliers);
        }catch(error){
            console.error('Error cargando proveedores', error);
        }
    }

    const fetchImages = async (date) => {
        console.log("Fetching image for date: ", date);

        const params = {
            establishment_id: parseInt(establishmentStore.code),
        }

        if(date){
            params.date = date;
        }

        console.log('Valor de params: ', params);

        try{
            const { data } = await axios.get('/api/suppliers/fetch-images', {
                params
            });


            console.log("Imagen obtenida: ", data);
            supplierStore.setSuppliersLogs(data.images);


        }catch(error){
            console.error("Error al obtener la imagen: ", error);
        }
    }

    const uploadImage = async (supplier) => {

        if (images.value.length == 0) {
            alert("Selecciona un archivo");
            return;
        }

        if (!supplier) {
            alert("No hay proveedor definido");
            return;
        }

        // showLoader();

        try{
            const formData = new FormData();
            formData.append('establishment_id', establishmentStore.code);
            formData.append('user_id', userStore.user.id);
            images.value.forEach(image => {
                formData.append('images[]', image.file);
            });

            const { data } = await axios.post(`/api/suppliers/${supplier}/upload-images`, formData);

            supplierStore.addSupplierLog(data.log);
            images.value = [];

        }catch(error){
            console.error("Error al subir la imagen: ", error.response);
            alert('¡Error!', 'Ocurrió un error desconocido, contacte son soporte.', 'info');
        }finally{
            // hideLoader();
        }

    }

    const fileSelected = (event) => {

        const selectedImages = Array.from(event.target.files);

        selectedImages.forEach(file => {
            images.value.push({
                file,
                preview: URL.createObjectURL(file),
            });
        });

        event.target.value = "";

    };

    const removeImage = (index) => {

        URL.revokeObjectURL(images.value[index].preview);

        images.value.splice(index, 1);
    };



    return {
        // Variables
        supplier,
        images,
        filteredImages,


        // Funciones
        fetchSuppliers,
        uploadImage,
        fileSelected,
        fetchImages,
        removeImage,
    }

}
