import { useRouter } from 'vue-router';
import axios from 'axios';
import { useEstablishmentStore } from '@/stores/establishmentStore';

export function useAuth(){

    const router = useRouter();
    const { setCode } = useEstablishmentStore();

    const login = async ( document : number, password : string ) => {

        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.post('/api/login', { document, password});
        console.log(response.data);
        const { establishment_id } = response.data.user;
        setCode(establishment_id);
        router.push('/dashboard');

    }

    const toDashboard = () => {
        router.push('/dashboard');
    }


    return{
        toDashboard,
        login
    }
}
