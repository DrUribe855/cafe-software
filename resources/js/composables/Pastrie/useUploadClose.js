import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../stores/userStore';
import { useEstablishmentStore } from '../../stores/establishmentStore';

export function useUploadClose() {
    const store = useUserStore();
    const establishmentStore = useEstablishmentStore();
    const closeData = ref([]);
    
    const fetchCloseFiles = async (date) => {
        console.log("Fetching close files for date: ", date);
        try {
            const { data } = await axios.get('/api/get-close-files', {
                params: {
                    date: date,
                    establishment_id: parseInt(establishmentStore.getCode())
                }
            });

            closeData.value = data;
            console.log("Archivos de cierre obtenidos: ", closeData.value);
        } catch (error) {
            console.error("Error al obtener archivos de cierre: ", error);
        }
    };

   
    const uploadCloseFiles = async (files, observations) => {
        const formData = new FormData();
        formData.append('establishment_id', parseInt(establishmentStore.getCode()));
        formData.append('user_id', store.user.id);
        formData.append('observations', observations);

       
        files.forEach((file, index) => {
            formData.append(`files[${index}]`, file);
        });

        try {
            const { data } = await axios.post('/api/upload-close-files', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            console.log("Respuesta de subida de archivos de cierre: ", data);
        } catch (error) {
            console.error("Error al subir archivos de cierre: ", error);
        }
    };

    return { uploadCloseFiles, fetchCloseFiles, closeData };
}
