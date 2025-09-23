
import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../stores/userStore';
import { useEstablishmentStore } from '../../stores/establishmentStore';

export function useUploadClose() {
  const store = useUserStore();
  const establishmentStore = useEstablishmentStore();
  const closeData = ref([]);

  const fetchCloseFiles = async (date, refrigeratorId) => {
    try {
      const { data } = await axios.get('/api/closing-logs', {
        params: {
          date: date,
          establishment_id: parseInt(establishmentStore.getCode()),
          refrigerator_id: refrigeratorId
        }
      });

      closeData.value = data;
      console.log("Archivos de cierre obtenidos:", closeData.value);
    } catch (error) {
      console.error("Error al obtener archivos de cierre:", error);
    }
  };

  const uploadCloseFiles = async (files, refrigeratorId) => {
    const formData = new FormData();
    formData.append('establishment_id', parseInt(establishmentStore.getCode()));
    formData.append('user_id', store.user.id);
    formData.append('refrigerator_id', refrigeratorId);

    files.forEach((file, index) => {
      formData.append(`files[${index}]`, file);
    });

    try {
      const { data } = await axios.post('/api/closing-logs', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      console.log("Respuesta de subida de archivos de cierre:", data);
    } catch (error) {
      console.error("Error al subir archivos de cierre:", error);
    }
  };

  const deleteCloseFile = async (id) => {
    try {
      await axios.delete(`/api/closing-logs/${id}`);
      closeData.value = closeData.value.filter(item => item.id !== id);
      console.log("Archivo de cierre eliminado:", id);
    } catch (error) {
      console.error("Error al eliminar archivo de cierre:", error);
    }
  };

  return { uploadCloseFiles, fetchCloseFiles, deleteCloseFile, closeData };
}
