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

  const fetchCloseFiles = async (date, refrigeratorId) => {
    try {
      const { data } = await axios.get('/api/closing-logs', {
        params: {
          date,
          establishment_id: parseInt(establishmentStore.getCode()),
          refrigerator_id: refrigeratorId,
        },
      });

      closeData.value = data.map(item => ({
        ...item,
        temperature: item.temperature ?? 'No registrada', 
      }));

      return closeData.value;
    } catch (error) {
      console.error('Error al obtener archivos de cierre:', error);
    }
  };

  const uploadCloseFiles = async (files, refrigeratorId, temperature) => {
    const fileArray = Array.from(files || []);
    if (!refrigeratorId && refrigeratorId !== 0) throw new Error('Nevera requerida');
    if (temperature === null || temperature === undefined || temperature === '') throw new Error('Temperature requerida');

    const parsedTemp = Number(temperature);
    if (Number.isNaN(parsedTemp)) throw new Error('Temperature debe ser un número');
    if (!fileArray.length) throw new Error('No hay archivos para subir');

    const formData = new FormData();
    formData.append('refrigerator_id', refrigeratorId);
    formData.append('temperature', parsedTemp);

    fileArray.forEach(file => formData.append('images[]', file));

    try {
      const { data } = await axios.post('/api/closing-logs', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });

      if (data?.data) {
        closeData.value = [...closeData.value, ...data.data.map(item => ({
          ...item,
          temperature: item.temperature ?? parsedTemp
        }))];
      }
      alert('Cierre subido exitosamente', 'La imagen y temperatura han sido subidas correctamente.', 'success');
      
      return data;
    } catch (error) {
      console.error('Error al subir archivos de cierre:', error.response?.data || error);
      throw error;
    }
  };

  const deleteCloseFile = async id => {
    try {
      await axios.delete(`/api/closing-logs/${id}`);
      closeData.value = closeData.value.filter(item => item.id !== id);
      return true;
    } catch (error) {
      console.error('Error al eliminar archivo de cierre:', error);
      throw error;
    }
  };

  return { fetchCloseFiles, uploadCloseFiles, deleteCloseFile, closeData };
}
