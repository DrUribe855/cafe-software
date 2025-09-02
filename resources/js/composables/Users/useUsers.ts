import { ref } from 'vue';
import type { Employee } from '@/interfaces/Employees';
import axios from 'axios';

interface UsersResponse{
    users: Employee[];
}



export function useUsers(){

    const users = ref<Employee[]>([]);

    // Función para cargado de usuarios
    const fetchUsers = async ( establishmentId : number ) => {

        const { data } = await axios.get<UsersResponse>('/api/users', {
            params: { establishmentId }
        });

        users.value = data.users;
        console.log("lista de usuarios", users.value);

    }

    return{
        fetchUsers,
        users,
    }

}
