import { useRouter } from 'vue-router';
import axios from 'axios';
import { useEstablishmentStore } from '@/stores/establishmentStore';
import { useUserStore } from '@/stores/userStore';

export function useAuth(){

    const router = useRouter();

    /* Importamos los store para almacenar datos de forma global */
    const { setCode } = useEstablishmentStore();
    const { setUser, clearUser } = useUserStore();

    const login = async ( document, password) => {

        /* Se hace petición a sanctum para generación de token CSRF */
        await axios.get('/sanctum/csrf-cookie');

        /* Asignación y desestructuración de objetos */
        const { data } = await axios.post('/api/login', { document, password});
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

    }

    const logout = async () =>{

        try{
            const { data } = await axios.post('/api/logout');
            clearUser();
            console.log('sesion cerrada', data);
            router.push('/');
        }catch(error){
            console.log('error', error.response?.data)
        }

    }

    return{
        login,
        logout
    }
}

