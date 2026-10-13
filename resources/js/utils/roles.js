// Helpers de roles para el modelo de 3 niveles (S1).
// super_admin es un superconjunto de admin: toda UI exclusiva de admin
// también es visible para super_admin.

export const isAdminLike = (role) => role === 'admin' || role === 'super_admin';

export const isSuperAdmin = (role) => role === 'super_admin';
