import Swal from 'sweetalert2'

export function alert(title, text, icon = 'info') {
  return Swal.fire({
    title,
    text,
    icon,
    confirmButtonColor: '#2563eb', 
    background: '#f9fafb',
    color: '#111827',
    timer: 1150,
    timerProgressBar: true,
    showConfirmButton: false
  });
}