import { ref } from 'vue';
import { useMenuStore } from '@/stores/Menu/menuStore.js';


export function useMenuCategories(){

    /* ============ Declaración de variables ============ */

    const category = ref({
        'name': '',
        'description': '',
    });

    const { addCategory, setCategories, removeCategory } = useMenuStore();


    /* ============ Funciones ============ */

    /* Función para obtener las categorías */
    const fetchCategories = async () => {
        try {
            const { data } = await axios.get('/api/menu/categories');
            setCategories(data);
            console.log('Categorías cargadas correctamente', data);

        }catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las categorías.',
            });
        }
    };

    /* Función para creación de categorías */
    const saveCategory = async () => {

        console.log('Guardando categoría', category);
        if (!category.value.name){
            console.log('El nombre de la categoría es obligatorio');
            return;
        }

        if (!category.value.description){
            console.log('La descripción de la categoría es obligatoria');
            return
        }

        try {
            const { data } = await axios.post('/api/menu/categories', category.value);
            addCategory(data);
            console.log('Categoría creada correctamente', data);
            category.value = {
                'name': '',
                'description': '',
            };
        }catch(error){
            console.error('Error al crear la categoría', error);
        }
    }

    const deleteCategory = async (id) => {
        try {
            const { data } = await axios.delete(`/api/menu/categories/${id}`);
            removeCategory(id);
            console.log('Categoría eliminada corrrectamente', data);
        }catch(error){
            console.error('Error al eliminar la categoría', error);
        }
    }

    return {
        // Variables
        category,

        //Funciones
        fetchCategories,
        saveCategory,
        deleteCategory,

    }
}
