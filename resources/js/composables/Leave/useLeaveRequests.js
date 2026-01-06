import { ref, computed } from 'vue';
import { alert } from '../Pastrie/alert';
import { useEstablishmentStore } from '../../stores/establishmentStore';
import { useLeaveRequestsStore } from '../../stores/LeaveRequests/Administrator/useLeaveRequestStore';
import { useLoader } from '../useLoader';
import axios from 'axios';
import Swal from 'sweetalert2';
import { storeToRefs } from 'pinia';

const weekDays = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];
const requests = ref([]);



export function useLeaveRequests(){

    /* ============================= Declaración de variables ============================= */

    const { code }  = useEstablishmentStore();
    const leaveRequestStore = useLeaveRequestsStore();
    const { requests } = storeToRefs(leaveRequestStore);
    const { showLoader, hideLoader } = useLoader();
    const employeeSearch  = ref('');
    const statusFilter    = ref('');
    const selectedRequest = ref({});
    const isModalOpen     = ref(false);

    /* Variable computada para filtras solicitudes por usuario o tipo de solicitud */
    const filteredRequests = computed(() => {
        const search = employeeSearch.value.toLowerCase();

        console.log('entro en computed')

        return (requests.value ?? []).map(day => ({
            ...day,
            requests: (day.requests ?? []).filter(r =>{
                const matchName =
                    !search || r.user.name.toLowerCase().includes(search);

                const matchStatus =
                    !statusFilter.value || r.status === statusFilter.value;

                return matchName && matchStatus;
            }),
            absences: (day.absences ?? []).filter(a =>
                a.user.name.toLowerCase().includes(search)
            )
        }));

    });


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

    const openRequestModal = ( request ) => {
        selectedRequest.value = request;
        isModalOpen.value = true;
        console.log('Solicitud seleccionada: ', selectedRequest.value);
        console.log('Modal abierto: ', isModalOpen.value);
    }

    const closeRequestModal = () =>{
        selectedRequest.value = {};
        isModalOpen.value = false;
        console.log('Modal abierto: ', isModalOpen.value);
    }

    const sendRequestResponse = async (comment, response, id) => {
        showLoader();

        if(response === '' || response === null){
            console.log('Error, ocurrió un error al cargar la respuesta de la solicitud');
            return;
        }

        if(!id){
            console.log('Error, ocurrió un error al cargar el ID de la solicitud');
            return;
        }

        try{
            const { data } = axios.patch(`/api/leave-requests/${id}`, {
                response: response,
                comment: comment
            });

            console.log('Informacion insertada tras respuesta: ', data);
        }catch(error){
            console.log('Se ha producido un error', error.message);
        }finally{
            hideLoader();
        }

    }

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

    const getRequestSum = async (month, year) => {
        try{
            const { data } = await axios.get(`/api/establishments/${code}/leave-requests/sum`, {
                params: {
                    month: month,
                    year: year
                }
            });

            console.log('Respuesta de suma de peticiones', data);
            leaveRequestStore.setSumRequests(data.sumRequest);
        }catch(error){
            console.log('Ocurrió un error al traer la suma de las peticiones', error);
        }
    }

    /* Mapeo de datos para visualización mensual */
    const dataMapping = (month, year, data) => {
        const days = new Date(year, month, 0).getDate();
        const requestsByDay = {};
        const absencesByDay = {};
        const mappedRequests = [];

        data.requests.forEach(r => {

            /* Indexamos las solicitudes por fecha de creación */
            const createdDate = r.created_at.split('T')[0];
            if(!requestsByDay[createdDate]){
                requestsByDay[createdDate] = [];
            }

            requestsByDay[createdDate].push(r);

            /* Indexamos las solicitudes aprobadas por rango de fechas */
            if(r.status === 'Aprobado'){
                const start = new Date(r.start_date);
                const end = new Date(r.end_date);

                while(start <= end){
                    const dateKey = start.toISOString().split('T')[0];
                    if(!absencesByDay[dateKey]){
                        absencesByDay[dateKey] = [];
                    }
                    absencesByDay[dateKey].push(r);
                    start.setDate(start.getDate() + 1);
                }
            }
        });

        /* Añadimos los datos indexados al array de solicitudes */
        for(let i = 1; i <= days; i++){
            const currentDate = `${year}-${String(month).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            mappedRequests.push({
                day: i,
                date: currentDate,
                requests: requestsByDay[currentDate] || [],
                absences: absencesByDay[currentDate] || [],
            });

        }

        return mappedRequests;


    }

    /* Funcion para traer los permisos o vacaciones del usuario logueado */

    const fetchLeaveRequestsPerUser = async () => {
        try{
            const { data } = await axios.get('/api/leave-requests');
            // console.log('Solicitudes obtenidas con éxito: ', data);
            requests.value = data.requests;
            // console.log('Requests value updated: ', requests.value);

        }catch(error){
            console.error('Error al obtener las solicitudes: ', error);
        }
    }

    /* Función para obtener solicitudes por establecimiento de forma mensual */

    const fetchMonthData = async (date) => {
        showLoader();

        if(!date){
            console.log('Ocurrió un error con la fecha suministrada, contacte con soporte');
            return;
        }

        if(!code){
            console.log('Ocurrio un error con el ID del establecimiento, contacte con soporte');
            return;
        }

        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        getRequestSum(month, year);

        try{
            const { data } = await axios.get(`/api/establishments/${code}/leave-requests`, {
                params: {
                    month: month,
                    year: year,
                }
            });

            leaveRequestStore.setRequests(dataMapping(month, year, data));

        }catch(error){
            console.error('Error al obtener las solicitudes para el establecimiento: ', error);
        }finally{
            hideLoader();
        }
    }

    return{
        request,
        errors,
        touched,
        requests,
        weekDays,
        employeeSearch,
        statusFilter,
        filteredRequests,
        sendLeaveRequest,
        sendRequestResponse,
        fetchLeaveRequestsPerUser,
        fetchMonthData,
        openRequestModal,
        closeRequestModal,
        isModalOpen,
        selectedRequest,
    }

}
