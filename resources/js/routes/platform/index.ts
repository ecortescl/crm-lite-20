import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import logo from './logo'
/**
* @see \App\Http\Controllers\Settings\PlatformController::edit
* @see app/Http/Controllers/Settings/PlatformController.php:13
* @route '/settings/platform'
*/
export const edit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/settings/platform',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\PlatformController::edit
* @see app/Http/Controllers/Settings/PlatformController.php:13
* @route '/settings/platform'
*/
edit.url = (options?: RouteQueryOptions) => {
    return edit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\PlatformController::edit
* @see app/Http/Controllers/Settings/PlatformController.php:13
* @route '/settings/platform'
*/
edit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::edit
* @see app/Http/Controllers/Settings/PlatformController.php:13
* @route '/settings/platform'
*/
edit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::edit
* @see app/Http/Controllers/Settings/PlatformController.php:13
* @route '/settings/platform'
*/
const editForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::edit
* @see app/Http/Controllers/Settings/PlatformController.php:13
* @route '/settings/platform'
*/
editForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::edit
* @see app/Http/Controllers/Settings/PlatformController.php:13
* @route '/settings/platform'
*/
editForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Settings\PlatformController::update
* @see app/Http/Controllers/Settings/PlatformController.php:35
* @route '/settings/platform'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post","patch"],
    url: '/settings/platform',
} satisfies RouteDefinition<["post","patch"]>

/**
* @see \App\Http\Controllers\Settings\PlatformController::update
* @see app/Http/Controllers/Settings/PlatformController.php:35
* @route '/settings/platform'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\PlatformController::update
* @see app/Http/Controllers/Settings/PlatformController.php:35
* @route '/settings/platform'
*/
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::update
* @see app/Http/Controllers/Settings/PlatformController.php:35
* @route '/settings/platform'
*/
update.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::update
* @see app/Http/Controllers/Settings/PlatformController.php:35
* @route '/settings/platform'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::update
* @see app/Http/Controllers/Settings/PlatformController.php:35
* @route '/settings/platform'
*/
updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\PlatformController::update
* @see app/Http/Controllers/Settings/PlatformController.php:35
* @route '/settings/platform'
*/
updateForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

const platform = {
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    logo: Object.assign(logo, logo),
}

export default platform