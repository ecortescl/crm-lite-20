import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\PlatformController::deleteMethod
* @see app/Http/Controllers/Settings/PlatformController.php:45
* @route '/settings/platform/logo'
*/
export const deleteMethod = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteMethod.url(options),
    method: 'delete',
})

deleteMethod.definition = {
    methods: ["delete"],
    url: '/settings/platform/logo',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Settings\PlatformController::deleteMethod
* @see app/Http/Controllers/Settings/PlatformController.php:45
* @route '/settings/platform/logo'
*/
deleteMethod.url = (options?: RouteQueryOptions) => {
    return deleteMethod.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\PlatformController::deleteMethod
* @see app/Http/Controllers/Settings/PlatformController.php:45
* @route '/settings/platform/logo'
*/
deleteMethod.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteMethod.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::deleteMethod
* @see app/Http/Controllers/Settings/PlatformController.php:45
* @route '/settings/platform/logo'
*/
const deleteMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: deleteMethod.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::deleteMethod
* @see app/Http/Controllers/Settings/PlatformController.php:45
* @route '/settings/platform/logo'
*/
deleteMethodForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: deleteMethod.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

deleteMethod.form = deleteMethodForm

const logo = {
    delete: Object.assign(deleteMethod, deleteMethod),
}

export default logo