import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import companies from './companies'
import leads from './leads'
import quotations from './quotations'
import calendar from './calendar'
import documentation6f9654 from './documentation'
/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:351
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
* @see app/Http/Controllers/Api/LeadApiController.php:351
* @route '/api/lead-statuses'
*/
leadStatuses.url = (options?: RouteQueryOptions) => {
    return leadStatuses.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:351
* @route '/api/lead-statuses'
*/
leadStatuses.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leadStatuses.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:351
* @route '/api/lead-statuses'
*/
leadStatuses.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leadStatuses.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:351
* @route '/api/lead-statuses'
*/
const leadStatusesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadStatuses.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:351
* @route '/api/lead-statuses'
*/
leadStatusesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: leadStatuses.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::leadStatuses
* @see app/Http/Controllers/Api/LeadApiController.php:351
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
* @see \App\Http\Controllers\ApiDocumentationController::documentation
* @see app/Http/Controllers/ApiDocumentationController.php:9
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
* @see \App\Http\Controllers\ApiDocumentationController::documentation
* @see app/Http/Controllers/ApiDocumentationController.php:9
* @route '/api/documentation'
*/
documentation.url = (options?: RouteQueryOptions) => {
    return documentation.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ApiDocumentationController::documentation
* @see app/Http/Controllers/ApiDocumentationController.php:9
* @route '/api/documentation'
*/
documentation.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::documentation
* @see app/Http/Controllers/ApiDocumentationController.php:9
* @route '/api/documentation'
*/
documentation.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: documentation.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::documentation
* @see app/Http/Controllers/ApiDocumentationController.php:9
* @route '/api/documentation'
*/
const documentationForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::documentation
* @see app/Http/Controllers/ApiDocumentationController.php:9
* @route '/api/documentation'
*/
documentationForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: documentation.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ApiDocumentationController::documentation
* @see app/Http/Controllers/ApiDocumentationController.php:9
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
    quotations: Object.assign(quotations, quotations),
    calendar: Object.assign(calendar, calendar),
    documentation: Object.assign(documentation, documentation6f9654),
}

export default api