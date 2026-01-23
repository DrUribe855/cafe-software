import { ref } from 'vue';
import { useMenuStore } from '@/stores/Menu/menuStore.js';

export function useMenuProducts(){

    /* ============ Declaración de variables ============ */
    const { setProducts, editProduct, addProduct, removeProduct } = useMenuStore();
    const isEditing  = ref(false);
    const file       = ref(null);
    const isSelected = ref(false);
    const category   = ref({
        id: null,
        category_id: null,
        name: "",
        description: "",
        price: 0,
        image_url: null,
        tag: "",
        new_tag: "",
        custom_tags: [],
        recommended: false,
    });

    /* ============ Funciones ============ */

    /* Función para cargado de productos */
    const fetchProducts = async () => {

        try {
            const { data } = await axios.get('/api/menu/products');
            setProducts(data);
            console.log('Productos cargados correctamente: ', data);
        }catch(error){
            console.error('Error al cargar los productos');
        }
    }

    /* Función para guardar o editar un producto */
    const saveProduct = async () => {

        console.log('Guardando producto: ', category.value);

        if(formValidations()){
            const formData = new FormData();
            formData.append('category_id', category.value.category_id);
            formData.append('name', category.value.name);
            formData.append('description', category.value.description ?? '');
            formData.append('price', category.value.price);
            formData.append('tag', category.value.tag ?? '');
            formData.append('custom_tags', Array.isArray(category.value.custom_tags) ? category.value.custom_tags.join(',') : category.value.custom_tags ?? '');
            formData.append('recommended', category.value.recommended ? 1 : 0);

            console.log('FormData preparado: ', formData.get('category_id'));

            try{
                if(isEditing.value){
                    formData.append('_method', 'PUT');
                    const { data } = await axios.post(`/api/menu/products/${category.value.id}`, formData);
                    console.log('Producto actualizado correctamente: ', data);
                    editProduct(data);
                    resetForm();
                }else{
                    const { data } = await axios.post('/api/menu/products', formData);
                    console.log('Producto creado correctamente: ', data);
                    addProduct(data);
                    resetForm();
                }
            }catch(error){
                console.error('Error al guardar el producto: ', error);
            }

        }

    }

    /* Función para eliminar un producto */
    const deleteProduct = async (id) => {
        try{
            const { data } = await axios.delete(`/api/menu/products/${id}`);
            removeProduct(id);
            console.log('Producto eliminado correctamente:', data);
        }catch(error){
            console.error('Error al eliminar el producto: ', error);
        }
    }

    /* Función para habilitar la edición de un producto */
    const enableEditing = (product) => {
        console.log('Habilitando edición para el producto: ', product);
        isEditing.value = true;
        category.value = {
            id: product.id,
            category_id: product.category_id,
            name: product.name,
            description: product.description,
            price: product.price,
            image_url: product.image_url,
            tag: product.tag,
            custom_tags: product.custom_tags ? product.custom_tags.split(",").map(t => t.trim()) : [],
            recommended: product.recommended,
        };
        console.log('Editando producto: ', category.value);
    }

    /* Función para validaciones del formulario */
    const formValidations = () => {

        if(!category.value.category_id){
            console.log('La categoría del producto es obligatoria');
            return false;
        }

        if(!category.value.name){
            console.log('El nombre del producto es obligatorio');
            return false;
        }

        if(!category.value.description){
            console.log('La descripción del producto es obligatoria');
            return false;
        }

        if(!category.value.price || category.value.price <= 0){
            console.log('El precio del producto es obligatorio y debe ser mayor a 0');
            return false;
        }

        if(!category.value.tag){
            console.log('La etiqueta principal del producto es obligatoria');
            return false;
        }

        if(!isEditing.value && !(category.value.image_url instanceof File)){
            console.log('La imagen del producto es obligatoria');
            return false;
        }

        return true;
    }

    /* Función para resetear valores del formulario */
    const resetForm = () => {
        isEditing.value  = false;
        isSelected.value = false;
        category.value   = {
            id: null,
            category_id: null,
            name: "",
            description: "",
            price: "",
            image_url: null,
            tag: "",
            new_tag: "",
            custom_tags: [],
            recommended: false,
        };
    };

    /* Función para agregar una etiqueta personalizada */
    const addCustomTag = () => {
        if (!category.value.new_tag.trim()) return;
        category.value.custom_tags.push(category.value.new_tag.trim());
        category.value.new_tag = "";
    };


    /* Función para eliminar una etiqueta personalizada */
    const removeCustomTag = (index) => {
        category.value.custom_tags.splice(index, 1);
    };

    /* Función para manejar la selección de archivo */
    const fileSelected = (file) => {
        const selected = file.target.files[0];

        if(!selected){
            file.value = null;
            isSelected.value = false;
            return;
        };

        if(!selected.type.startsWith('image/')){
            alert('Solo se permiten imágenes');
            return;
        }

        category.value.image_url = selected;

        console.log('Archivo seleccionado: ', selected);
        isSelected.value = true;
    }


    return {
        //Variables
        isEditing,
        category,
        file,
        isSelected,

        //Funciones
        fetchProducts,
        addCustomTag,
        removeCustomTag,
        fileSelected,
        saveProduct,
        enableEditing,
        deleteProduct,
    }
}
