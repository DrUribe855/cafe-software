import { ref } from 'vue';
import axios from 'axios';





export function useUsers(){

    const users = ref([]);

    // Función para cargado de usuarios
    const fetchUsers = async ( establishmentId ) => {

        const { data } = await axios.get('/api/users', {
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
