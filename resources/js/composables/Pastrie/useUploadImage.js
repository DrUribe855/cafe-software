import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../stores/userStore';
import { useEstablishmentStore } from '../../stores/establishmentStore';
import { useLoader } from '../useLoader.js';
import { alert } from './alert.js';

const { showLoader, hideLoader } = useLoader();

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

        showLoader();

        try{
            const formData = new FormData();
            formData.append('establishment_id', parseInt(establishmentStore.getCode()));
            formData.append('user_id', store.user.id);
            formData.append('schedule', schedule);
            formData.append('file', file);

            const { data } = await axios.post('/api/upload-image', formData, {
                headers: {
                    'Authorization': `Bearer ${store.token}`,
                    // 'Content-Type': 'multipart/form-data',
                },
            });

            if(data.status){
                alert('Validado','La imagen se ha subido correctamente', 'success');
            }else{
                alert('¡Ups!', 'No insertó pero tampoco lanzó error', data);
                window.alert("Error de try: " + error.message);
            }

            return data.message;
        }catch(error){
            console.error("Error al subir la imagen: ", error.response);
            if(error.status === 409){
                alert('¡Ups!', 'Ya existe una imagen para este horario', 'info');
            }else{
              alert('Depuracion', 'Error fuera de condiciones controladas', error.response);
              window.alert("Error de catch: " + error.message);
            }
        }finally{
            hideLoader();
        }

    }


    return { uploadImage, fetchImage, imageData };
}
