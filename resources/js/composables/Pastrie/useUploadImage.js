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

        try{
            const formData = new FormData();
            formData.append('establishment_id', parseInt(establishmentStore.getCode()));
            formData.append('user_id', store.user.id);
            formData.append('schedule', schedule);
            formData.append('file', file);

            const { data } = await axios.post('/api/upload-image', formData, {
                headers: {
                    'Authorization': `Bearer ${store.token}`,
                    'Content-Type': 'multipart/form-data',
                },
            });

            if(data.status){
                alert('Validado','La imagen se ha subido correctamente', 'success');
            }
        }catch(error){
            console.error("Error al subir la imagen: ", error.response);

        }

    }


    return { uploadImage, fetchImage, imageData };
}
