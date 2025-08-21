import { useRouter } from 'vue-router';

export function useAuth(){

    const router = useRouter();

    const toDashboard = () => {
        router.push('/dashboard');
    }


    return{
        toDashboard
    }
}
