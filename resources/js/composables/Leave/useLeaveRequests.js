import { ref, computed } from 'vue';
import { alert } from '../Pastrie/alert';
import axios from 'axios';

export function useLeaveRequests(){

    /* Definición de variables */

    const permission = ref({
        type: '',
        startDate: '',
        endDate: '',
        reason: '',
    });

    const touched = ref({
        type: false,
        startDate: false,
        endDate: false,
        reason: false,
    });

    const errors = computed(() => {
        return {
            type: permission.value.type === '' ? '¡Debes seleccionar el tipo de solicitud!' : '',
            startDate: permission.value.startDate === '' ,
            endDate: permission.value.endDate === '',
            reason: permission.value.reason === '' && permission.value.type !== 'Vacaciones',
        };
    });

    const hasErrors = computed(() =>
        Object.values(errors.value).some(error => error === true)
    );

    const sendPermissionRequest = async () => {

        console.log('Enviando solicitud de permiso...', permission.value);

        Object.keys(touched.value).forEach(key => touched.value[key] = true);

        if (hasErrors.value) {
            return;
        }

        try{
            const { data } = await axios.post('/api/permissions', {
                type: permission.value.type,
                start_date: permission.value.startDate,
                end_date: permission.value.endDate,
                reason: permission.value.reason,
            });
            console.log('Solicitud enviada con éxito:', data);
        }catch(error){
            console.error('Error al enviar la solicitud:', error);
        }
    }



    return{
        permission,
        errors,
        touched,
        sendPermissionRequest,
    }

}
