import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../stores/userStore';
import { useEstablishmentStore } from '../../stores/establishmentStore';
import { useLoader } from '../useLoader.js';
import { alert } from './alert.js';

const { showLoader, hideLoader } = useLoader();
const images = ref([]);

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

            imageData.value = data.images;
            console.log("Imagen obtenida: ", imageData.value);


        }catch(error){
            console.error("Error al obtener la imagen: ", error);
        }
    }

    const uploadImage = async (schedule) => {

        if (images.value.length == 0) {
            alert("Selecciona un archivo");
            return;
        }
        if (!schedule) {
            alert("No hay horario definido");
            return;
        }

        showLoader();

        try{
            const formData = new FormData();
            formData.append('establishment_id', parseInt(establishmentStore.getCode()));
            formData.append('user_id', store.user.id);
            formData.append('schedule', schedule);
            images.value.forEach(image => {
                formData.append('images[]', image.file);
            });

            const { data } = await axios.post('/api/upload-image', formData);

            if(data.status){
                alert('Validado','La imagen se ha subido correctamente', 'success');
                images.value = [];
            }

        }catch(error){
            console.error("Error al subir la imagen: ", error.response);
            alert('¡Error!', 'Ocurrió un error desconocido, contacte son soporte.', 'info');
        }finally{
            hideLoader();
        }

    }

    const fileSelected = (event) => {

        const selectedImages = Array.from(event.target.files);

        selectedImages.forEach(file => {
            images.value.push({
                file,
                preview: URL.createObjectURL(file),
            });
        });

        event.target.value = "";

    };

    const removeImage = (index) => {

        URL.revokeObjectURL(images.value[index].preview);

        images.value.splice(index, 1);
    };


    return { uploadImage, fetchImage, fileSelected, removeImage, imageData, images };
}
