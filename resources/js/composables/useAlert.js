import Swal from "sweetalert2";

export function useAlert () {

    const success = (text, message) => {
        Swal.fire(text, message, 'success');
    }

    const errorAlert = (text, message) => {
        Swal.fire(text, message, 'error');
    }

    const warning = (text, message) => {
        Swal.fire(text, message, 'warning');
    }


    return {
        success,
        errorAlert,
        warning,
    }
}