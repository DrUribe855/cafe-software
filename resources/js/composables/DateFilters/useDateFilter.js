import { ref } from 'vue';

const currentDate = ref(new Date());
const monthCache = ref({});


export function useDateFilter(){

    const getWeekRange = (date) => {
        const current = new Date(date);

        const day = current.getDay();

        const diffToMonday = day === 0 ? -6 : 1 - day;

        const start = new Date(current);
        start.setDate(current.getDate() + diffToMonday);
        start.setHours(0, 0, 0, 0);

        const end = new Date(start);
        end.setDate(start.getDate() + 6);
        end.setHours(23, 59, 59, 999);

        return { start, end };
    }

    const previousWeek = () => {
        const d = new Date(currentDate.value);
        d.setDate(d.getDate() - 7);
        currentDate.value = d;
        console.log('Current Date after going to previous week:', currentDate.value);
        return getWeekRange(d);
    }

    const nextWeek = () => {
        const d = new Date(currentDate.value);
        d.setDate(d.getDate() + 7);
        currentDate.value = d;
        console.log('Current Date after going to next week:', currentDate.value);
        return getWeekRange(d);
    }

    const getMonthKey = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        return `${year}-${month}`;
    }

    const isMonthLoaded = async (date) => {
        const monthKey = getMonthKey(date);

        if(!monthCache.value[monthKey]){
            await fetchMonthDate(date);
        }
    }






    return{
        nextWeek,
        previousWeek,
        getWeekRange,
        getMonthKey,
        currentDate,
    }

}
