import { BookOpen, Calendar1, NotebookPen, Coffee, MonitorX, Users } from "lucide-vue-next";

export const dashboardCards = [
    {
        title: 'Empleados',
        description: 'Gestiona los empleados de la empresa.',
        icon: Users,
        route: '/dashboard/users'
    },
    {
        title: 'Vacaciones',
        description: 'Gestiona tus vacaciones y solicita días libres.',
        icon: BookOpen,
        route: 'vacaciones'
    },
    {
        title: 'Horarios',
        description: 'Revisa tus turnos, horas de entrada y salida.',
        icon: Calendar1,
        route: 'horarios'
    },
    {
        title: 'Actividades',
        description: 'Revisa y gestiona actividades asignadas.',
        icon: NotebookPen,
        route: 'actividades'
    },
    {
        title: 'Bolleria',
        description: 'Estado de la bolleria en apertura, mediodía y cierre.',
        icon: Coffee,
        route: '/dashboard/bakery'
    },
    {
        title: 'Cierre',
        description: 'Temperaturas, actividades, lista de cierre.',
        icon: MonitorX,
        route: '/dashboard/close'
    }
];
