import { ref } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../stores/userStore';
import { useEstablishmentStore } from '../../stores/establishmentStore';
import { alert } from './alert';
import Swal from 'sweetalert2';

export function useUploadClose() {
  const store = useUserStore();
  const establishmentStore = useEstablishmentStore();
  const closeData = ref([]);

  const uploadCloseFiles = async (files, refrigeratorId, temperature) => {
    try {
      const formData = new FormData();

      files.forEach((file) => {
        formData.append('images[]', file);
      });

      formData.append('refrigerator_id', refrigeratorId);
      formData.append('temperature', temperature);

      const establishmentId = establishmentStore.getCode();
      formData.append('establishment_id', establishmentId);

      const response = await axios.post('/api/closing-logs', formData, {
        headers: {
          Authorization: `Bearer ${store.token}`,
          'Content-Type': 'multipart/form-data',
        },
      });

      alert(
        'Cierre subido exitosamente',
        'Las imágenes y la temperatura se registraron correctamente.',
        'success'
      );

      return response.data;
    } catch (error) {
      console.error('Error al subir archivos de cierre:', error.response?.data || error);
      throw error;
    }
  };

  const fetchCloseFiles = async (date, refrigeratorId = null) => {
    try {
      const establishmentId = establishmentStore.getCode();

      if (!establishmentId) {
        console.log('No hay establecimiento seleccionado');
        return;
      }

      let params = {
        establishment_id: establishmentId,
        date,
        refrigerator_id:
          refrigeratorId === null || refrigeratorId === "" || refrigeratorId === "null"
            ? null
            : refrigeratorId,
      };

      const response = await axios.get('/api/closing-logs', {
        headers: {
          Authorization: `Bearer ${store.token}`,
        },
        params,
      });

      closeData.value = response.data;
      console.log('Datos de cierre cargados:', closeData.value);
    } catch (error) {
      console.error('Error al cargar archivos de cierre:', error.response?.data || error);

      Swal.fire({
        icon: 'error',
        title: 'Error al cargar cierres',
        text: error.response?.data?.message || 'No se pudieron obtener los registros.',
      });

      closeData.value = [];
    }
  };

  return {
    uploadCloseFiles,
    fetchCloseFiles,
    closeData,
  };
}
