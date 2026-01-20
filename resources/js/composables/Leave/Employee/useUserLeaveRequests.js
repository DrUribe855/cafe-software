import axios from 'axios';
import Swal from 'sweetalert2';
import { ref, computed } from 'vue';
import { alert } from '../../Pastrie/alert';
import { useLoader } from '../../useLoader';
import { useUserStore } from '../../../stores/userStore';
import { useLeaveRequestsStore } from '../../../stores/LeaveRequests/Administrator/useLeaveRequestStore';
import { storeToRefs } from 'pinia';

export function useUserLeaveRequests(){

    /* ============================= Declaración de variables ============================= */

    const leaveRequestStore = useLeaveRequestsStore();
    const userStore = useUserStore();
    const { showLoader, hideLoader } = useLoader();
    const { requests } = storeToRefs(leaveRequestStore);
    const employeeSearch  = ref('');
    const statusFilter    = ref('');

    /* Objeto para almacenar la información de la solicitud */

    const request = ref({
        type: '',
        startDate: '',
        endDate: '',
        reason: '',
    });

    /* Objeto para validar si el usuario ha interactuado con los campos del formulario */

    const touched = ref({
        type: false,
        startDate: false,
        endDate: false,
        reason: false,
    });


    /* Variable computada que retorna errores en formulario al momento de enviar un permiso */

    const errors = computed(() => {
        return {
            type: request.value.type === '' ? '¡Debes seleccionar el tipo de solicitud!' : '',
            startDate: request.value.startDate === '' ? '¡Debes seleccionar la fecha de inicio!' : '',
            endDate: request.value.endDate === '' ? '¡Debes seleccionar la fecha de fin!' : '',
            reason: request.value.reason === '' && request.value.type !== 'Vacaciones' ? '¡Debes diligenciar el motivo!' : '',
        };
    });

    /* Valida el contenido de errors para confirmar si se detectó error o no en los campos */

    const hasErrors = computed(() =>
        Object.values(errors.value).some(error => error !== '')
    );


    /* Funcion para creacion de permisos o vacaciones */

    const sendLeaveRequest = async () => {

        showLoader();

        Object.keys(touched.value).forEach(key => touched.value[key] = true);
        console.log('Errores de validación encontrados:', errors.value);
        if (hasErrors.value) {
            console.log('Errores de validación encontrados:', errors.value);
            return;
        }

        try{
            const { data } = await axios.post('/api/leave-requests', {
                type: request.value.type,
                start_date: request.value.startDate,
                end_date: request.value.endDate,
                reason: request.value.reason,
            });

            requests.value.push(data.record);
            clearVariables();

            alert('Registrado', 'Solicitud enviada con éxito', 'success');

        }catch(error){
            console.error('Error al enviar la solicitud:', error);
            Swal.fire({
                icon: 'error',
                title: 'Errores encontrados',
                html: `<ol>${Object.values(error.response.data.errors).map(err => `<li class="text-sm"> - ${err}</li>`).join('')}</ol>`,
            })
        }finally{
            hideLoader();
        }
    }

    /* Funcion para traer los permisos o vacaciones del usuario logueado */

    const fetchLeaveRequestsPerUser = async (date) => {
        showLoader();
        try{
            const id    = userStore.user.id;
            const month = date.getMonth() + 1;
            const year  = date.getFullYear();

            const { data } = await axios.get(`/api/leave-requests/${id}`, {
                params: {
                    month: month,
                    year: year
                }
            });
            requests.value = data.requests;
            console.log('Solicitudes obtenidas: ', requests.value);
        }catch(error){
            console.error('Error al obtener las solicitudes: ', error);
        }finally{
            hideLoader();
        }
    }

    const clearVariables = () => {
        request.value = {
            type:      '',
            startDate: '',
            endDate:   '',
            reason:    '',
        };

        touched.value = {
            type:      false,
            startDate: false,
            endDate:   false,
            reason:    false,
        };


    }

    return{
        request,
        errors,
        touched,
        requests,
        employeeSearch,
        statusFilter,
        sendLeaveRequest,
        fetchLeaveRequestsPerUser,
    }

}
