import { ref } from 'vue';
import axios from 'axios';

export function useUsers(){

    const users = ref([]);
    const errors = ref({});

    /* Función para cargado de usuarios */
    const fetchUsers = async ( establishmentId ) => {

        const { data } = await axios.get('/api/users', {
            params: { establishmentId }
        });

        users.value = data.users;
        console.log("lista de usuarios", users.value);

    }

    /* Función para guardado de información de usuarios */

    const saveUser = async ( user ) => {

        /* Si el id está vacio o indefinido se hace creación de usuario. */

        let errores = formValidations(user);
        console.log('Errores en la validación: ', errores);

        if(formValidations(user)) return null


        if(!user.id){
            try{
                const { data } = await axios.post('/api/users/', user);
                return data.user;
            }catch(error){
                console.log('Error en creación: ', error);

                if(error.response.status === 422){
                    errors.value = error.response.data.errors;
                    console.log(errors.value);
                }
            }

        }else{
            try{
                const { data } = await axios.put(`/api/users/${user.id}`, user);
                return data.user;
            }catch(error){

                if(error.response.status === 422){
                    errors.value = error.response.data.errors;
                    console.log(errors.value);
                }
                console.log('Error en modificación: ', error);
            }
        }
    }

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
