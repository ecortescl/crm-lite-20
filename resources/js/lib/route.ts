export function route(name: string, params?: any): string {
  const routes: Record<string, string> = {
    'dashboard': '/dashboard',
    'leads.index': '/leads',
    'leads.kanban': '/leads/kanban',
    'leads.store': '/leads',
    'leads.update': '/leads/:id',
    'leads.destroy': '/leads/:id',
    'leads.update-status': '/leads/:id/status',
    'users.index': '/users',
    'users.store': '/users',
    'users.update': '/users/:id',
    'users.destroy': '/users/:id',
    'roles.index': '/roles',
    'roles.store': '/roles',
    'roles.update': '/roles/:id',
    'roles.destroy': '/roles/:id',
    'permissions.index': '/permissions',
    'permissions.store': '/permissions',
    'permissions.update': '/permissions/:id',
    'permissions.destroy': '/permissions/:id',
    'lead-statuses.index': '/lead-statuses',
    'lead-statuses.store': '/lead-statuses',
    'lead-statuses.update': '/lead-statuses/:id',
    'lead-statuses.destroy': '/lead-statuses/:id',
    'companies.index': '/companies',
    'companies.store': '/companies',
    'companies.update': '/companies/:id',
    'companies.destroy': '/companies/:id',
    'platform.edit': '/settings/platform',
    'platform.update': '/settings/platform',
    'platform.logo.delete': '/settings/platform/logo',
    'profile.edit': '/settings/profile',
    'profile.update': '/settings/profile',
    'profile.destroy': '/settings/profile',
    'api-tokens.index': '/settings/api-tokens',
    'api-tokens.store': '/settings/api-tokens',
    'api-tokens.destroy': '/settings/api-tokens/:id',
  }

  let url = routes[name] || name

  if (params) {
    if (typeof params === 'object' && !Array.isArray(params)) {
      // Si params es un objeto con id, reemplazar :id
      if (params.id !== undefined) {
        url = url.replace(':id', params.id.toString())
      }
      // Si params es solo un número, asumir que es el id
    } else if (typeof params === 'number' || typeof params === 'string') {
      url = url.replace(':id', params.toString())
    }
  }

  return url
}
