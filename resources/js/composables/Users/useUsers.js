import { ref } from 'vue';
import axios from 'axios';
import { useEstablishmentStore }  from '../../stores/establishmentStore';

export function useUsers(){

    const establishment = useEstablishmentStore();
    const users = ref([]);

    // Función para cargado de usuarios
    const fetchUsers = async ( establishmentId ) => {

        const { data } = await axios.get('/api/users', {
            params: { establishmentId }
        });

        users.value = data.users;
        console.log("lista de usuarios", users.value);

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

    return{
        fetchUsers,
        users,
        createUser,
    }

}
