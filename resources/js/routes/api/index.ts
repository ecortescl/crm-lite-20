import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import companies from './companies'
import leads from './leads'
/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:346
* @route '/api/lead-statuses'
*/
export const leadStatuses = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leadStatuses.url(options),
    method: 'get',
})

leadStatuses.definition = {
    methods: ["get","head"],
    url: '/api/lead-statuses',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:346
* @route '/api/lead-statuses'
*/
leadStatuses.url = (options?: RouteQueryOptions) => {
    return leadStatuses.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:346
* @route '/api/lead-statuses'
*/
leadStatuses.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leadStatuses.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:346
* @route '/api/lead-statuses'
*/
leadStatuses.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leadStatuses.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:346
* @route '/api/lead-statuses'
*/
const leadStatusesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadStatuses.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:346
* @route '/api/lead-statuses'
*/
leadStatusesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadStatuses.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:346
* @route '/api/lead-statuses'
*/
leadStatusesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadStatuses.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

leadStatuses.form = leadStatusesForm

/**
* @see routes/web.php:19
* @route '/api/documentation'
*/
export const documentation = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: documentation.url(options),
    method: 'get',
})

documentation.definition = {
    methods: ["get","head"],
    url: '/api/documentation',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:19
* @route '/api/documentation'
*/
documentation.url = (options?: RouteQueryOptions) => {
    return documentation.definition.url + queryParams(options)
}

/**
* @see routes/web.php:19
* @route '/api/documentation'
*/
documentation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: documentation.url(options),
    method: 'get',
})

/**
* @see routes/web.php:19
* @route '/api/documentation'
*/
documentation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: documentation.url(options),
    method: 'head',
})

/**
* @see routes/web.php:19
* @route '/api/documentation'
*/
const documentationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see routes/web.php:19
* @route '/api/documentation'
*/
documentationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see routes/web.php:19
* @route '/api/documentation'
*/
documentationForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

documentation.form = documentationForm

const api = {
    companies: Object.assign(companies, companies),
    leads: Object.assign(leads, leads),
    leadStatuses: Object.assign(leadStatuses, leadStatuses),
    documentation: Object.assign(documentation, documentation),
}

export default api