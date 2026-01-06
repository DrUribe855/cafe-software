import { ref } from 'vue';

const currentDate = ref(new Date());

export function useDateFilter(){


    const previousMonth = () => {
        currentDate.value = new Date( currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
    }

    const nextMonth = () => {
        currentDate.value = new Date( currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
    }

    return{
        previousMonth,
        nextMonth,
        currentDate,
    }

}
