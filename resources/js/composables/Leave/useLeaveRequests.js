import { ref, computed } from 'vue';
import { alert } from '../Pastrie/alert';
import { useEstablishmentStore } from '../../stores/establishmentStore';
import { useDateFilter } from '../DateFilters/useDateFilter';
import axios from 'axios';
import Swal from 'sweetalert2';

const requests = ref([]);
const { getMonthKey } = useDateFilter();

export function useLeaveRequests(){
    
    /* ============================= Declaración de variables ============================= */

    const { code } = useEstablishmentStore();

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

        console.log('Enviando solicitud de permiso...', request.value);

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

            alert('Registrado', 'Solicitud enviada con éxito', 'success');

        }catch(error){
            console.error('Error al enviar la solicitud:', error);
            Swal.fire({
                icon: 'error',
                title: 'Errores encontrados',
                html: `<ol>${Object.values(error.response.data.errors).map(err => `<li class="text-sm"> - ${err}</li>`).join('')}</ol>`,
            })
        }
    }


    /* Funcion para traer los permisos o vacaciones del usuario logueado */

    const fetchLeaveRequestsPerUser = async () => {       
        try{
            const { data } = await axios.get('/api/leave-requests');
            console.log('Solicitudes obtenidas con éxito: ', data);
            requests.value = data.requests;
            console.log('Requests value updated: ', requests.value);
            
        }catch(error){
            console.error('Error al obtener las solicitudes: ', error);
        }
    }

    const fetchMonthData = async (date) => {

        console.log(code);

        console.log('se ejecuto fetchMonthData con fecha: ', date);

        if(!code){
            console.log('Ocurrio un error con el ID del establecimiento, contacte con soporte');
            return;
        }

        if(!date){
            console.log('Ocurrió un error con la fecha suministrada, contacte con soporte');
            return;
        }

        const monthKey = getMonthKey(date);

        try{
            const { data } = await axios.get(`/api/establishments/${code}/leave-requests`, {
                params: {
                    month: monthKey,
                }
            });

            console.log('Solicitudes obtenidas con éxito para el establecimiento: ', data);

        }catch(error){
            console.error('Error al obtener las solicitudes para el establecimiento: ', error);
        }
    }

    return{
        request,
        errors,
        touched,
        requests,
        sendLeaveRequest,
        fetchLeaveRequestsPerUser,
        fetchMonthData,
    }

}
