import axios from 'axios';
import { ref, computed, watch } from 'vue';
import { useEstablishmentStore } from '../../../stores/establishmentStore';
import { useDateFilter } from '../../DateFilters/useDateFilter';
import { useLeaveRequestsStore } from '../../../stores/LeaveRequests/Administrator/useLeaveRequestStore';
import { useLoader } from '../../useLoader';
import { storeToRefs } from 'pinia';

const weekDays = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];

export function useAdminLeaveRequests(){

    /* ============================= Declaración de variables ============================= */

    const establishmentStore  = useEstablishmentStore();
    const { code } = storeToRefs(establishmentStore);
    const leaveRequestStore = useLeaveRequestsStore();
    const { requests } = storeToRefs(leaveRequestStore);
    const { showLoader, hideLoader } = useLoader();
    const { currentDate } = useDateFilter();
    const employeeSearch  = ref('');
    const statusFilter    = ref('');
    const selectedRequest = ref({});
    const isModalOpen     = ref(false);

    watch(currentDate, (date) => {
        fetchMonthData(date);
    });

    watch(code, newCode =>{
        if(!newCode) return
        fetchMonthData(currentDate.value);
    })


    /* Variable computada para filtras solicitudes por usuario o tipo de solicitud */
    const filteredRequests = computed(() => {
        const search = employeeSearch.value.toLowerCase();

        return (requests.value ?? []).map(day => ({
            ...day,
            requests: (day.requests ?? []).filter(r =>{
                const matchName = !search || r.user.name.toLowerCase().includes(search);

                const matchStatus = !statusFilter.value || r.status === statusFilter.value;

                return matchName && matchStatus;
            }),
            absences: (day.absences ?? []).filter(a => {

                const absenceMatchName   = !search || a.user.name.toLowerCase().includes(search);
                const absenceMatchStatus = !statusFilter.value || statusFilter.value === 'Aprobado';
                return absenceMatchName && absenceMatchStatus;
            })
        }));

    });

    /* Funciones para manejar el comportamiento del modal */

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

    /* Función para envio de respuesta de la solicitud */

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

            closeRequestModal();
            await fetchMonthData(currentDate.value);
        }catch(error){
            console.log('Se ha producido un error', error.message);
        }finally{
            hideLoader();
        }

    }

    /* Función para obtener la suma de solicitudes por mes */

    const getRequestSum = async (month, year) => {
        try{
            const { data } = await axios.get(`/api/establishments/${code.value}/leave-requests/sum`, {
                params: {
                    month: month,
                    year: year
                }
            });

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

        data.created_requests.forEach(r => {

            /* Indexamos las solicitudes por fecha de creación */
            const createdDate = r.created_at.split('T')[0];

            if(!requestsByDay[createdDate]){
                requestsByDay[createdDate] = [];
            }

            requestsByDay[createdDate].push(r);


        });

        data.absences.forEach(a => {

            /* Indexamos las solicitudes aprobadas por rango de fechas */
            const dateKey = a.date;

            if (!absencesByDay[dateKey]) {
                absencesByDay[dateKey] = [];
            }

            absencesByDay[dateKey].push(a);
        });

        const firstDayOfMonth = new Date(year, month - 1, 1)
        let startWeekDay = firstDayOfMonth.getDay() //  0  Domingo

        // Convertimos a semana que empieza en LUNES
        startWeekDay = startWeekDay === 0 ? 6 : startWeekDay - 1

        for (let j = 0; j < startWeekDay; j++) {
            mappedRequests.push(['']);
        }

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

        console.log('mappedRequests: ', mappedRequests);

        return mappedRequests;


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
            const { data } = await axios.get(`/api/establishments/${code.value}/leave-requests`, {
                params: {
                    month: month,
                    year: year,
                }
            });

            console.log('Datos de solicitudes recibidos: ', data);

            leaveRequestStore.setRequests(dataMapping(month, year, data));

        }catch(error){
            console.error('Error al obtener las solicitudes para el establecimiento: ', error);
        }finally{
            hideLoader();
        }
    }

    return{
        weekDays,
        employeeSearch,
        statusFilter,
        filteredRequests,
        sendRequestResponse,
        fetchMonthData,
        openRequestModal,
        closeRequestModal,
        isModalOpen,
        selectedRequest,
    }

}
