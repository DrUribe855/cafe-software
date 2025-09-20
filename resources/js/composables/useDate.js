import dayjs from "dayjs";

export function useDate(){
    const getActualDate = () => dayjs().format("YYYY-MM-DD");

    return { getActualDate };
}
