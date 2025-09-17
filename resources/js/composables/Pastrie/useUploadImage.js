import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../stores/userStore';
import { useEstablishmentStore } from '../../stores/establishmentStore';

export function useUploadImage(){

    const store = useUserStore();
    const establishmentStore = useEstablishmentStore();
    const imageUrl = ref(null);

    const fetchImage = async (schedule, date) => {
        try{
            const { data } = await axios.get('/api/get-image', {
                params: {
                    schedule: schedule,
                    date: date,
                    establishment_id: parseInt(establishmentStore.getCode())
                }
            });

            imageUrl.value = data.image_url;
            console.log("Imagen obtenida: ", imageUrl.value);

        }catch(error){
            console.error("Error al obtener la imagen: ", error);
        }
    }

    const uploadImage = async (file, schedule) => {

        console.log("Tipo: ", file, file instanceof File);

        const formData = new FormData();
        formData.append('establishment_id', parseInt(establishmentStore.getCode()));
        formData.append('user_id', store.user.id);
        formData.append('schedule', schedule);
        formData.append('file', file);

        try{
            const { data } = await axios.post('/api/upload-image', formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            });
            console.log("Respuesta de subida de image: ", data);
        }catch(error){
            console.error("Error al subir la imagen: ", error);
        }
    }


    return { uploadImage, fetchImage, imageUrl };
}
