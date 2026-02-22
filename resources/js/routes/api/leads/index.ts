import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\LeadApiController::index
* @see app/Http/Controllers/Api/LeadApiController.php:68
* @route '/api/leads'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/leads',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\LeadApiController::index
* @see app/Http/Controllers/Api/LeadApiController.php:68
* @route '/api/leads'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LeadApiController::index
* @see app/Http/Controllers/Api/LeadApiController.php:68
* @route '/api/leads'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::index
* @see app/Http/Controllers/Api/LeadApiController.php:68
* @route '/api/leads'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::index
* @see app/Http/Controllers/Api/LeadApiController.php:68
* @route '/api/leads'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::index
* @see app/Http/Controllers/Api/LeadApiController.php:68
* @route '/api/leads'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::index
* @see app/Http/Controllers/Api/LeadApiController.php:68
* @route '/api/leads'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Api\LeadApiController::store
* @see app/Http/Controllers/Api/LeadApiController.php:137
* @route '/api/leads'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/leads',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\LeadApiController::store
* @see app/Http/Controllers/Api/LeadApiController.php:137
* @route '/api/leads'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LeadApiController::store
* @see app/Http/Controllers/Api/LeadApiController.php:137
* @route '/api/leads'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::store
* @see app/Http/Controllers/Api/LeadApiController.php:137
* @route '/api/leads'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::store
* @see app/Http/Controllers/Api/LeadApiController.php:137
* @route '/api/leads'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Api\LeadApiController::show
* @see app/Http/Controllers/Api/LeadApiController.php:202
* @route '/api/leads/{lead}'
*/
export const show = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/leads/{lead}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\LeadApiController::show
* @see app/Http/Controllers/Api/LeadApiController.php:202
* @route '/api/leads/{lead}'
*/
show.url = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { lead: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            lead: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead: typeof args.lead === 'object'
        ? args.lead.id
        : args.lead,
    }

    return show.definition.url
            .replace('{lead}', parsedArgs.lead.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LeadApiController::show
* @see app/Http/Controllers/Api/LeadApiController.php:202
* @route '/api/leads/{lead}'
*/
show.get = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::show
* @see app/Http/Controllers/Api/LeadApiController.php:202
* @route '/api/leads/{lead}'
*/
show.head = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::show
* @see app/Http/Controllers/Api/LeadApiController.php:202
* @route '/api/leads/{lead}'
*/
const showForm = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::show
* @see app/Http/Controllers/Api/LeadApiController.php:202
* @route '/api/leads/{lead}'
*/
showForm.get = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::show
* @see app/Http/Controllers/Api/LeadApiController.php:202
* @route '/api/leads/{lead}'
*/
showForm.head = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Api\LeadApiController::update
* @see app/Http/Controllers/Api/LeadApiController.php:251
* @route '/api/leads/{lead}'
*/
export const update = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/api/leads/{lead}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Api\LeadApiController::update
* @see app/Http/Controllers/Api/LeadApiController.php:251
* @route '/api/leads/{lead}'
*/
update.url = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { lead: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            lead: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead: typeof args.lead === 'object'
        ? args.lead.id
        : args.lead,
    }

    return update.definition.url
            .replace('{lead}', parsedArgs.lead.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LeadApiController::update
* @see app/Http/Controllers/Api/LeadApiController.php:251
* @route '/api/leads/{lead}'
*/
update.put = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::update
* @see app/Http/Controllers/Api/LeadApiController.php:251
* @route '/api/leads/{lead}'
*/
update.patch = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::update
* @see app/Http/Controllers/Api/LeadApiController.php:251
* @route '/api/leads/{lead}'
*/
const updateForm = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::update
* @see app/Http/Controllers/Api/LeadApiController.php:251
* @route '/api/leads/{lead}'
*/
updateForm.put = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::update
* @see app/Http/Controllers/Api/LeadApiController.php:251
* @route '/api/leads/{lead}'
*/
updateForm.patch = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Api\LeadApiController::destroy
* @see app/Http/Controllers/Api/LeadApiController.php:313
* @route '/api/leads/{lead}'
*/
export const destroy = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/leads/{lead}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\LeadApiController::destroy
* @see app/Http/Controllers/Api/LeadApiController.php:313
* @route '/api/leads/{lead}'
*/
destroy.url = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { lead: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            lead: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead: typeof args.lead === 'object'
        ? args.lead.id
        : args.lead,
    }

    return destroy.definition.url
            .replace('{lead}', parsedArgs.lead.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\LeadApiController::destroy
* @see app/Http/Controllers/Api/LeadApiController.php:313
* @route '/api/leads/{lead}'
*/
destroy.delete = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::destroy
* @see app/Http/Controllers/Api/LeadApiController.php:313
* @route '/api/leads/{lead}'
*/
const destroyForm = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\LeadApiController::destroy
* @see app/Http/Controllers/Api/LeadApiController.php:313
* @route '/api/leads/{lead}'
*/
destroyForm.delete = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const leads = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default leads