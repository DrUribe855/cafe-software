import { ref } from 'vue';
import { useLeaveRequests } from '../Leave/useLeaveRequests';

const currentDate = ref(new Date());


export function useDateFilter(){

    const { fetchMonthData } = useLeaveRequests();

    const previousMonth = () => {
        currentDate.value.setMonth(currentDate.value.getMonth() - 1);
        fetchMonthData(currentDate.value);
    }

    const nextMonth = () => {
        console.log('Next month clicked');
        currentDate.value.setMonth(currentDate.value.getMonth() + 1);
        fetchMonthData(currentDate.value);
    }

    return{
        previousMonth,
        nextMonth,
        currentDate,
    }

}
