import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\QuotationApiController::nextNumber
* @see app/Http/Controllers/Api/QuotationApiController.php:375
* @route '/api/quotations/next-number'
*/
export const nextNumber = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nextNumber.url(options),
    method: 'get',
})

nextNumber.definition = {
    methods: ["get","head"],
    url: '/api/quotations/next-number',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\QuotationApiController::nextNumber
* @see app/Http/Controllers/Api/QuotationApiController.php:375
* @route '/api/quotations/next-number'
*/
nextNumber.url = (options?: RouteQueryOptions) => {
    return nextNumber.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\QuotationApiController::nextNumber
* @see app/Http/Controllers/Api/QuotationApiController.php:375
* @route '/api/quotations/next-number'
*/
nextNumber.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: nextNumber.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::nextNumber
* @see app/Http/Controllers/Api/QuotationApiController.php:375
* @route '/api/quotations/next-number'
*/
nextNumber.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: nextNumber.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::nextNumber
* @see app/Http/Controllers/Api/QuotationApiController.php:375
* @route '/api/quotations/next-number'
*/
const nextNumberForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: nextNumber.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::nextNumber
* @see app/Http/Controllers/Api/QuotationApiController.php:375
* @route '/api/quotations/next-number'
*/
nextNumberForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: nextNumber.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::nextNumber
* @see app/Http/Controllers/Api/QuotationApiController.php:375
* @route '/api/quotations/next-number'
*/
nextNumberForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: nextNumber.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

nextNumber.form = nextNumberForm

/**
* @see \App\Http\Controllers\Api\QuotationApiController::index
* @see app/Http/Controllers/Api/QuotationApiController.php:69
* @route '/api/quotations'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/quotations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\QuotationApiController::index
* @see app/Http/Controllers/Api/QuotationApiController.php:69
* @route '/api/quotations'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\QuotationApiController::index
* @see app/Http/Controllers/Api/QuotationApiController.php:69
* @route '/api/quotations'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::index
* @see app/Http/Controllers/Api/QuotationApiController.php:69
* @route '/api/quotations'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::index
* @see app/Http/Controllers/Api/QuotationApiController.php:69
* @route '/api/quotations'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::index
* @see app/Http/Controllers/Api/QuotationApiController.php:69
* @route '/api/quotations'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::index
* @see app/Http/Controllers/Api/QuotationApiController.php:69
* @route '/api/quotations'
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
* @see \App\Http\Controllers\Api\QuotationApiController::store
* @see app/Http/Controllers/Api/QuotationApiController.php:116
* @route '/api/quotations'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/quotations',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\QuotationApiController::store
* @see app/Http/Controllers/Api/QuotationApiController.php:116
* @route '/api/quotations'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\QuotationApiController::store
* @see app/Http/Controllers/Api/QuotationApiController.php:116
* @route '/api/quotations'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::store
* @see app/Http/Controllers/Api/QuotationApiController.php:116
* @route '/api/quotations'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::store
* @see app/Http/Controllers/Api/QuotationApiController.php:116
* @route '/api/quotations'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Api\QuotationApiController::show
* @see app/Http/Controllers/Api/QuotationApiController.php:188
* @route '/api/quotations/{quotation}'
*/
export const show = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/quotations/{quotation}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\QuotationApiController::show
* @see app/Http/Controllers/Api/QuotationApiController.php:188
* @route '/api/quotations/{quotation}'
*/
show.url = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { quotation: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { quotation: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            quotation: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        quotation: typeof args.quotation === 'object'
        ? args.quotation.id
        : args.quotation,
    }

    return show.definition.url
            .replace('{quotation}', parsedArgs.quotation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\QuotationApiController::show
* @see app/Http/Controllers/Api/QuotationApiController.php:188
* @route '/api/quotations/{quotation}'
*/
show.get = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::show
* @see app/Http/Controllers/Api/QuotationApiController.php:188
* @route '/api/quotations/{quotation}'
*/
show.head = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::show
* @see app/Http/Controllers/Api/QuotationApiController.php:188
* @route '/api/quotations/{quotation}'
*/
const showForm = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::show
* @see app/Http/Controllers/Api/QuotationApiController.php:188
* @route '/api/quotations/{quotation}'
*/
showForm.get = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::show
* @see app/Http/Controllers/Api/QuotationApiController.php:188
* @route '/api/quotations/{quotation}'
*/
showForm.head = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Api\QuotationApiController::update
* @see app/Http/Controllers/Api/QuotationApiController.php:226
* @route '/api/quotations/{quotation}'
*/
export const update = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/api/quotations/{quotation}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Api\QuotationApiController::update
* @see app/Http/Controllers/Api/QuotationApiController.php:226
* @route '/api/quotations/{quotation}'
*/
update.url = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { quotation: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { quotation: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            quotation: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        quotation: typeof args.quotation === 'object'
        ? args.quotation.id
        : args.quotation,
    }

    return update.definition.url
            .replace('{quotation}', parsedArgs.quotation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\QuotationApiController::update
* @see app/Http/Controllers/Api/QuotationApiController.php:226
* @route '/api/quotations/{quotation}'
*/
update.put = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::update
* @see app/Http/Controllers/Api/QuotationApiController.php:226
* @route '/api/quotations/{quotation}'
*/
update.patch = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::update
* @see app/Http/Controllers/Api/QuotationApiController.php:226
* @route '/api/quotations/{quotation}'
*/
const updateForm = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::update
* @see app/Http/Controllers/Api/QuotationApiController.php:226
* @route '/api/quotations/{quotation}'
*/
updateForm.put = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::update
* @see app/Http/Controllers/Api/QuotationApiController.php:226
* @route '/api/quotations/{quotation}'
*/
updateForm.patch = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Api\QuotationApiController::destroy
* @see app/Http/Controllers/Api/QuotationApiController.php:295
* @route '/api/quotations/{quotation}'
*/
export const destroy = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/quotations/{quotation}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\QuotationApiController::destroy
* @see app/Http/Controllers/Api/QuotationApiController.php:295
* @route '/api/quotations/{quotation}'
*/
destroy.url = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { quotation: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { quotation: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            quotation: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        quotation: typeof args.quotation === 'object'
        ? args.quotation.id
        : args.quotation,
    }

    return destroy.definition.url
            .replace('{quotation}', parsedArgs.quotation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\QuotationApiController::destroy
* @see app/Http/Controllers/Api/QuotationApiController.php:295
* @route '/api/quotations/{quotation}'
*/
destroy.delete = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::destroy
* @see app/Http/Controllers/Api/QuotationApiController.php:295
* @route '/api/quotations/{quotation}'
*/
const destroyForm = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::destroy
* @see app/Http/Controllers/Api/QuotationApiController.php:295
* @route '/api/quotations/{quotation}'
*/
destroyForm.delete = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Api\QuotationApiController::updateStatus
* @see app/Http/Controllers/Api/QuotationApiController.php:342
* @route '/api/quotations/{quotation}/status'
*/
export const updateStatus = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

updateStatus.definition = {
    methods: ["patch"],
    url: '/api/quotations/{quotation}/status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Api\QuotationApiController::updateStatus
* @see app/Http/Controllers/Api/QuotationApiController.php:342
* @route '/api/quotations/{quotation}/status'
*/
updateStatus.url = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { quotation: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { quotation: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            quotation: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        quotation: typeof args.quotation === 'object'
        ? args.quotation.id
        : args.quotation,
    }

    return updateStatus.definition.url
            .replace('{quotation}', parsedArgs.quotation.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\QuotationApiController::updateStatus
* @see app/Http/Controllers/Api/QuotationApiController.php:342
* @route '/api/quotations/{quotation}/status'
*/
updateStatus.patch = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::updateStatus
* @see app/Http/Controllers/Api/QuotationApiController.php:342
* @route '/api/quotations/{quotation}/status'
*/
const updateStatusForm = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateStatus.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\QuotationApiController::updateStatus
* @see app/Http/Controllers/Api/QuotationApiController.php:342
* @route '/api/quotations/{quotation}/status'
*/
updateStatusForm.patch = (args: { quotation: number | { id: number } } | [quotation: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: updateStatus.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

updateStatus.form = updateStatusForm

const quotations = {
    nextNumber: Object.assign(nextNumber, nextNumber),
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    updateStatus: Object.assign(updateStatus, updateStatus),
}

export default quotations