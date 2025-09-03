import { BookOpen, Calendar1, NotebookPen, Coffee, TicketX } from "lucide-vue-next";

export const dashboardCards = [
  {
    title: 'VACACIONES',
    description: 'Gestiona tus vacaciones y solicita días libres.',
    icon: BookOpen,
  },
  {
    title: 'HORARIOS',
    description: 'Revisa tus turnos, horas de entrada y salida.',
    icon: Calendar1,
  },
  {
    title: 'ACTIVIDADES',
    description: 'Revisa y gestiona actividades asignadas.',
    icon: NotebookPen,
  },
  {
    title: 'BOLLERÍA',
    description: 'Estado de la bollería en apertura, mediodía y cierre.',
    icon: Coffee,
  },
  {
    title: 'CIERRE',
    description: 'Temperaturas, actividades, lista de cierre.',
    icon: TicketX,
  }
];
