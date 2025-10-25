import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../stores/userStore';
import { useEstablishmentStore } from '../../stores/establishmentStore';
import { alert } from './alert.js';

export function useUploadImage(){

    const store = useUserStore();
    const establishmentStore = useEstablishmentStore();
    const imageData = ref([]);

    const fetchImage = async (date) => {
        console.log("Fetching image for date: ", date);
        try{
            const { data } = await axios.get('/api/get-image', {
                params: {
                    date: date,
                    establishment_id: parseInt(establishmentStore.getCode())
                }
            });

            imageData.value = data;
            console.log("Imagen obtenida: ", imageData.value);


        }catch(error){
            console.error("Error al obtener la imagen: ", error);
        }
    }

    const uploadImage = async (file, schedule) => {

        const formData = new FormData();
        formData.append('establishment_id', parseInt(establishmentStore.getCode()));
        formData.append('user_id', store.user.id);
        formData.append('schedule', schedule);
        formData.append('file', file);

        try{
            const { data } = await axios.post('/api/upload-image', formData);
            console.log("Respuesta de subida de image: ", data);
            alert('Validado', 'Imagen subida correctamente', 'success');
            return true;
        }catch(error){
            console.error("Error al subir la imagen: ", error);
            alert('Error', 'No se pudo subir la imagen', 'error');
        }
    }


    return { uploadImage, fetchImage, imageData };
}