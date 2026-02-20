import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
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
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:13
* @route '/leads'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/leads',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:13
* @route '/leads'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:13
* @route '/leads'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:13
* @route '/leads'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:13
* @route '/leads'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:13
* @route '/leads'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:13
* @route '/leads'
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
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:104
* @route '/leads'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/leads',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:104
* @route '/leads'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:104
* @route '/leads'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:104
* @route '/leads'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:104
* @route '/leads'
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
* @see \App\Http\Controllers\LeadController::show
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}'
*/
export const show = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/leads/{lead}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadController::show
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}'
*/
show.url = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead: args }
    }

    if (Array.isArray(args)) {
        args = {
            lead: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead: args.lead,
    }

    return show.definition.url
            .replace('{lead}', parsedArgs.lead.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::show
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}'
*/
show.get = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::show
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}'
*/
show.head = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadController::show
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}'
*/
const showForm = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::show
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}'
*/
showForm.get = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::show
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}'
*/
showForm.head = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:136
* @route '/leads/{lead}'
*/
export const update = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/leads/{lead}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:136
* @route '/leads/{lead}'
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
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:136
* @route '/leads/{lead}'
*/
update.put = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:136
* @route '/leads/{lead}'
*/
update.patch = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:136
* @route '/leads/{lead}'
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
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:136
* @route '/leads/{lead}'
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
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:136
* @route '/leads/{lead}'
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

/**
* @see \App\Http\Controllers\LeadController::destroy
* @see app/Http/Controllers/LeadController.php:179
* @route '/leads/{lead}'
*/
export const destroy = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/leads/{lead}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\LeadController::destroy
* @see app/Http/Controllers/LeadController.php:179
* @route '/leads/{lead}'
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
* @see \App\Http\Controllers\LeadController::destroy
* @see app/Http/Controllers/LeadController.php:179
* @route '/leads/{lead}'
*/
destroy.delete = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\LeadController::destroy
* @see app/Http/Controllers/LeadController.php:179
* @route '/leads/{lead}'
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
* @see \App\Http\Controllers\LeadController::destroy
* @see app/Http/Controllers/LeadController.php:179
* @route '/leads/{lead}'
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

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:60
* @route '/leads/kanban'
*/
export const kanban = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kanban.url(options),
    method: 'get',
})

kanban.definition = {
    methods: ["get","head"],
    url: '/leads/kanban',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:60
* @route '/leads/kanban'
*/
kanban.url = (options?: RouteQueryOptions) => {
    return kanban.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:60
* @route '/leads/kanban'
*/
kanban.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kanban.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:60
* @route '/leads/kanban'
*/
kanban.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kanban.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:60
* @route '/leads/kanban'
*/
const kanbanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kanban.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:60
* @route '/leads/kanban'
*/
kanbanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kanban.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:60
* @route '/leads/kanban'
*/
kanbanForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kanban.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

kanban.form = kanbanForm

/**
* @see \App\Http\Controllers\LeadController::updateStatus
* @see app/Http/Controllers/LeadController.php:168
* @route '/leads/{lead}/status'
*/
export const updateStatus = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

updateStatus.definition = {
    methods: ["patch"],
    url: '/leads/{lead}/status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\LeadController::updateStatus
* @see app/Http/Controllers/LeadController.php:168
* @route '/leads/{lead}/status'
*/
updateStatus.url = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return updateStatus.definition.url
            .replace('{lead}', parsedArgs.lead.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::updateStatus
* @see app/Http/Controllers/LeadController.php:168
* @route '/leads/{lead}/status'
*/
updateStatus.patch = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\LeadController::updateStatus
* @see app/Http/Controllers/LeadController.php:168
* @route '/leads/{lead}/status'
*/
const updateStatusForm = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateStatus.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadController::updateStatus
* @see app/Http/Controllers/LeadController.php:168
* @route '/leads/{lead}/status'
*/
updateStatusForm.patch = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateStatus.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

updateStatus.form = updateStatusForm

/**
* @see \App\Http\Controllers\LeadController::create
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/leads/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadController::create
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::create
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::create
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadController::create
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::create
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::create
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\LeadController::edit
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}/edit'
*/
export const edit = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/leads/{lead}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadController::edit
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}/edit'
*/
edit.url = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead: args }
    }

    if (Array.isArray(args)) {
        args = {
            lead: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead: args.lead,
    }

    return edit.definition.url
            .replace('{lead}', parsedArgs.lead.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::edit
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}/edit'
*/
edit.get = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::edit
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}/edit'
*/
edit.head = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadController::edit
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}/edit'
*/
const editForm = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::edit
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}/edit'
*/
editForm.get = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::edit
* @see app/Http/Controllers/LeadController.php:0
* @route '/leads/{lead}/edit'
*/
editForm.head = (args: { lead: string | number } | [lead: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

const leads = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    kanban: Object.assign(kanban, kanban),
    updateStatus: Object.assign(updateStatus, updateStatus),
    create: Object.assign(create, create),
    edit: Object.assign(edit, edit),
}

export default leads