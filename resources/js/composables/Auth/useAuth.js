import { useRouter } from 'vue-router';
import { ref } from 'vue';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import { useUserStore } from '@/stores/userStore';
import axios from 'axios';


export function useAuth(){

    const router = useRouter();

    /* Importamos los store para almacenar datos de forma global */
    const { setCode, clearCode } = useEstablishmentStore();
    const { setUser, clearUser } = useUserStore();
    const errors = ref({});

    const login = async ( document, password) => {

        /* Se reiniciar objeto de errores en cada ejecución */
        errors.value = {};

        if(document == null){
            return errors.value.document = ['El DNI es obligatorio'];
        }

        if(password.trim() === ''){
            return errors.value.password = ['La contraseña es obligatoria'];
        }

        try{
            /* Se hace petición a sanctum para generación de token CSRF */
            await axios.get('/sanctum/csrf-cookie');

            /* Asignación y desestructuración de objetos */
            const { data } = await axios.post('/api/login', { document, password});
            console.log('respuesta de login', data);
            const { establishment_id } = data.user;
            const user = {
                id: data.user.id,
                name: data.user.name,
                role: data.user.roles[0].name,
            }

            /* Cargado de variables globales y redireccionamiento */
            setCode(establishment_id);
            setUser(user);
            router.push('/dashboard');

        }catch(error){

            if(error.response.status === 422){
                return errors.value =  error.response.data.errors;
            }

            if(error.response.status === 404){
                return errors.value.userNotFound = error.response.data.message;
            }

            if(error.response.status === 401){
                return errors.value.incorrectUser = error.response.data.message;
            }

            if(error.response.status === 403){
                return errors.value.inactiveUser = error.response.data.message;
            }
        }

    }

    const logout = async () =>{

        try{
            const { data } = await axios.post('/api/logout');
            clearUser();
            clearCode();
            console.log('sesion cerrada', data);
            router.push('/');
        }catch(error){
            console.log('error', error.response?.data)
        }

    }

    return{
        login,
        logout,
        errors,
    }
}