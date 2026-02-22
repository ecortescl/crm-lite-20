import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ApiDocumentationController::postman
* @see app/Http/Controllers/ApiDocumentationController.php:14
* @route '/api/documentation/postman'
*/
export const postman = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: postman.url(options),
    method: 'get',
})

postman.definition = {
    methods: ["get","head"],
    url: '/api/documentation/postman',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ApiDocumentationController::postman
* @see app/Http/Controllers/ApiDocumentationController.php:14
* @route '/api/documentation/postman'
*/
postman.url = (options?: RouteQueryOptions) => {
    return postman.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApiDocumentationController::postman
* @see app/Http/Controllers/ApiDocumentationController.php:14
* @route '/api/documentation/postman'
*/
postman.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: postman.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::postman
* @see app/Http/Controllers/ApiDocumentationController.php:14
* @route '/api/documentation/postman'
*/
postman.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: postman.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::postman
* @see app/Http/Controllers/ApiDocumentationController.php:14
* @route '/api/documentation/postman'
*/
const postmanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: postman.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::postman
* @see app/Http/Controllers/ApiDocumentationController.php:14
* @route '/api/documentation/postman'
*/
postmanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: postman.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::postman
* @see app/Http/Controllers/ApiDocumentationController.php:14
* @route '/api/documentation/postman'
*/
postmanForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: postman.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

postman.form = postmanForm

const documentation = {
    postman: Object.assign(postman, postman),
}

export default documentation