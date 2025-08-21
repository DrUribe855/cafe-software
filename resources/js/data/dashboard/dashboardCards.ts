import { BookOpen, Calendar1, NotebookPen, Coffee, MonitorX } from "lucide-vue-next";

export const dashboardCards = [
    {
        title: 'Vacaciones',
        description: 'Gestiona tus vacaciones, solicita días y revisa su estado.',
        icon: BookOpen
    },
    {
        title: 'Horarios',
        description: 'Revisa tus turnos, horas de entrada y salida.',
        icon: Calendar1
    },
    {
        title: 'Actividades',
        description: 'Revisa y gestiona actividades asignadas.',
        icon: NotebookPen
    },
    {
        title: 'Bolleria',
        description: 'Estado de la bolleria en apertura, mediodía y cierre.',
        icon: Coffee
    },
    {
        title: 'Cierre',
        description: 'Temperaturas, actividades, lista de cierre.',
        icon: MonitorX
    }
];
