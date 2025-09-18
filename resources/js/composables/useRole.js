import { ref, onMounted } from 'vue';
import { useUserStore } from '../stores/userStore';

export function useRole(){
    const store = useUserStore();
    const globalRole = ref(null);

    onMounted( () => {
        globalRole.value = store.user.role;
    });


    return { globalRole };
}
