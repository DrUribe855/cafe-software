import { ref, computed } from 'vue';
import { alert } from '../Pastrie/alert';
import axios from 'axios';
import Swal from 'sweetalert2';

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
            startDate: permission.value.startDate === '' ? '¡Debes seleccionar la fecha de inicio!' : '',
            endDate: permission.value.endDate === '' ? '¡Debes seleccionar la fecha de fin!' : '',
            reason: permission.value.reason === '' && permission.value.type !== 'Vacaciones' ? '¡Debes diligenciar el motivo!' : '',
        };
    });

    const hasErrors = computed(() =>
        Object.values(errors.value).some(error => error !== '')
    );

    const sendPermissionRequest = async () => {

        console.log('Enviando solicitud de permiso...', permission.value);

        Object.keys(touched.value).forEach(key => touched.value[key] = true);
        console.log('Errores de validación encontrados:', errors.value);
        if (hasErrors.value) {
            console.log('Errores de validación encontrados:', errors.value);
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
            Swal.fire({
                icon: 'error',
                title: 'Errores encontrados',
                html: `<ol>${Object.values(error.response.data.errors).map(err => `<li class="text-sm"> - ${err}</li>`).join('')}</ol>`,
            })
        }
    }



    return{
        permission,
        errors,
        touched,
        sendPermissionRequest,
    }

}
