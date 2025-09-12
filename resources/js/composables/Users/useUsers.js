import { ref } from 'vue';
import axios from 'axios';
import { useEstablishmentStore }  from '../../stores/establishmentStore';

export function useUsers(){

    const establishment = useEstablishmentStore();
    const users = ref([]);
    const errors = ref({});

    /* Función para cargado de usuarios */
    const fetchUsers = async ( establishmentId ) => {

        const { data } = await axios.get('/api/users', {
            params: { establishmentId }
        });

        users.value = data.users;

    }

    const createUser = async ( user ) => {

        const store = establishment.getCode();

        // Si el id está vacio o indefinido se hace creación de usuario.
        if(!user.id){
            try{
                const { data } = await axios.post('/api/users/', user);
                return data.user;
            }catch(error){
                console.log('Error en creación: ', error);
            }
        }
    }

    /* Función para validación de campos de formulario */

    const formValidations = ( user ) => {

        errors.value = {};

        if(user.document === '' || user.document == null){
            errors.value.document = ['El DNI es obligatorio'];
        }

        if(user.name.trim() === ''){
            errors.value.name = ['El nombre es obligatorio'];
        }

        if(user.email.trim() === ''){
            errors.value.email = ['El email es obligatorio'];
        }

        if(user.role === '' || user.role === null || user.role == undefined){
            errors.value.role = ['Debe indicar el rol del usuario'];
        }

        if(user.status === '' || user.role == null){
            errors.value.status = ['Debe indicar el estado del usuario'];
        }

        return Object.keys(errors.value).length > 0 ? true : false;
    }

    return{
        fetchUsers,
        users,
        saveUser,
        errors,
    }

}