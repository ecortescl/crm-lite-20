import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\LeadStatusController::index
* @see app/Http/Controllers/LeadStatusController.php:26
* @route '/lead-statuses'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/lead-statuses',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadStatusController::index
* @see app/Http/Controllers/LeadStatusController.php:26
* @route '/lead-statuses'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadStatusController::index
* @see app/Http/Controllers/LeadStatusController.php:26
* @route '/lead-statuses'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::index
* @see app/Http/Controllers/LeadStatusController.php:26
* @route '/lead-statuses'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadStatusController::index
* @see app/Http/Controllers/LeadStatusController.php:26
* @route '/lead-statuses'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::index
* @see app/Http/Controllers/LeadStatusController.php:26
* @route '/lead-statuses'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::index
* @see app/Http/Controllers/LeadStatusController.php:26
* @route '/lead-statuses'
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
* @see \App\Http\Controllers\LeadStatusController::create
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/lead-statuses/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadStatusController::create
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadStatusController::create
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::create
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadStatusController::create
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::create
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::create
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/create'
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
* @see \App\Http\Controllers\LeadStatusController::store
* @see app/Http/Controllers/LeadStatusController.php:44
* @route '/lead-statuses'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/lead-statuses',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\LeadStatusController::store
* @see app/Http/Controllers/LeadStatusController.php:44
* @route '/lead-statuses'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadStatusController::store
* @see app/Http/Controllers/LeadStatusController.php:44
* @route '/lead-statuses'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadStatusController::store
* @see app/Http/Controllers/LeadStatusController.php:44
* @route '/lead-statuses'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadStatusController::store
* @see app/Http/Controllers/LeadStatusController.php:44
* @route '/lead-statuses'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\LeadStatusController::edit
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/{lead_status}/edit'
*/
export const edit = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/lead-statuses/{lead_status}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LeadStatusController::edit
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/{lead_status}/edit'
*/
edit.url = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead_status: args }
    }

    if (Array.isArray(args)) {
        args = {
            lead_status: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead_status: args.lead_status,
    }

    return edit.definition.url
            .replace('{lead_status}', parsedArgs.lead_status.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadStatusController::edit
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/{lead_status}/edit'
*/
edit.get = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::edit
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/{lead_status}/edit'
*/
edit.head = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadStatusController::edit
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/{lead_status}/edit'
*/
const editForm = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::edit
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/{lead_status}/edit'
*/
editForm.get = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadStatusController::edit
* @see app/Http/Controllers/LeadStatusController.php:0
* @route '/lead-statuses/{lead_status}/edit'
*/
editForm.head = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\LeadStatusController::update
* @see app/Http/Controllers/LeadStatusController.php:62
* @route '/lead-statuses/{lead_status}'
*/
export const update = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/lead-statuses/{lead_status}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\LeadStatusController::update
* @see app/Http/Controllers/LeadStatusController.php:62
* @route '/lead-statuses/{lead_status}'
*/
update.url = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead_status: args }
    }

    if (Array.isArray(args)) {
        args = {
            lead_status: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead_status: args.lead_status,
    }

    return update.definition.url
            .replace('{lead_status}', parsedArgs.lead_status.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadStatusController::update
* @see app/Http/Controllers/LeadStatusController.php:62
* @route '/lead-statuses/{lead_status}'
*/
update.put = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\LeadStatusController::update
* @see app/Http/Controllers/LeadStatusController.php:62
* @route '/lead-statuses/{lead_status}'
*/
update.patch = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\LeadStatusController::update
* @see app/Http/Controllers/LeadStatusController.php:62
* @route '/lead-statuses/{lead_status}'
*/
const updateForm = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadStatusController::update
* @see app/Http/Controllers/LeadStatusController.php:62
* @route '/lead-statuses/{lead_status}'
*/
updateForm.put = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadStatusController::update
* @see app/Http/Controllers/LeadStatusController.php:62
* @route '/lead-statuses/{lead_status}'
*/
updateForm.patch = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\LeadStatusController::destroy
* @see app/Http/Controllers/LeadStatusController.php:77
* @route '/lead-statuses/{lead_status}'
*/
export const destroy = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/lead-statuses/{lead_status}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\LeadStatusController::destroy
* @see app/Http/Controllers/LeadStatusController.php:77
* @route '/lead-statuses/{lead_status}'
*/
destroy.url = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead_status: args }
    }

    if (Array.isArray(args)) {
        args = {
            lead_status: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead_status: args.lead_status,
    }

    return destroy.definition.url
            .replace('{lead_status}', parsedArgs.lead_status.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadStatusController::destroy
* @see app/Http/Controllers/LeadStatusController.php:77
* @route '/lead-statuses/{lead_status}'
*/
destroy.delete = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\LeadStatusController::destroy
* @see app/Http/Controllers/LeadStatusController.php:77
* @route '/lead-statuses/{lead_status}'
*/
const destroyForm = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadStatusController::destroy
* @see app/Http/Controllers/LeadStatusController.php:77
* @route '/lead-statuses/{lead_status}'
*/
destroyForm.delete = (args: { lead_status: string | number } | [lead_status: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const leadStatuses = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default leadStatuses