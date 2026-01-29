import { BookOpen, Calendar1, NotebookPen, Coffee, MonitorX, Users } from "lucide-vue-next";

export const dashboardCards = [
    {
        title: 'Empleados',
        description: 'Gestiona los empleados de la empresa.',
        icon: Users,
        route: '/dashboard/users',
        roles: ['admin']
    },
    {
        title: 'Vacaciones',
        description: 'Gestion de vacaciones y permisos.',
        icon: BookOpen,
        route: '/dashboard/permissions',
        roles: ['admin', 'employee']
    },
    // {
    //     title: 'Horarios',
    //     description: 'Revisa tus turnos, horas de entrada y salida.',
    //     icon: Calendar1,
    //     route: 'horarios'
    // },
    // {
    //     title: 'Actividades',
    //     description: 'Revisa y gestiona actividades asignadas.',
    //     icon: NotebookPen,
    //     route: 'actividades'
    // },
    {
        title: 'Bolleria',
        description: 'Estado de la bolleria en apertura, mediodía y cierre.',
        icon: Coffee,
        route: '/dashboard/bakery',
        roles: ['admin', 'employee']
    },
    {
        title: 'Cierre',
        description: 'Temperaturas, actividades, lista de cierre.',
        icon: MonitorX,
        route: '/dashboard/close',
        roles: ['admin', 'employee']
    },
    {
        title: 'Proveedores',
        description: 'Gestión de ingresos de proveedores.',
        icon: MonitorX,
        route: '/dashboard/suppliers',
        roles: ['admin', 'employee']
    },
    //{
    //    title: 'Menú',
    //    description: 'Gestión del menú de productos disponibles.',
    //    icon: MonitorX,
    //    route: '/dashboard/menu',
    //    roles: ['admin', 'employee']
    // }
];
