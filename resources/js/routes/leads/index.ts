import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:69
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
* @see app/Http/Controllers/LeadController.php:69
* @route '/leads/kanban'
*/
kanban.url = (options?: RouteQueryOptions) => {
    return kanban.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:69
* @route '/leads/kanban'
*/
kanban.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: kanban.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:69
* @route '/leads/kanban'
*/
kanban.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: kanban.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:69
* @route '/leads/kanban'
*/
const kanbanForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kanban.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:69
* @route '/leads/kanban'
*/
kanbanForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: kanban.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::kanban
* @see app/Http/Controllers/LeadController.php:69
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
* @see app/Http/Controllers/LeadController.php:237
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
* @see app/Http/Controllers/LeadController.php:237
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
* @see app/Http/Controllers/LeadController.php:237
* @route '/leads/{lead}/status'
*/
updateStatus.patch = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateStatus.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\LeadController::updateStatus
* @see app/Http/Controllers/LeadController.php:237
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
* @see app/Http/Controllers/LeadController.php:237
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
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:14
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
* @see app/Http/Controllers/LeadController.php:14
* @route '/leads'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:14
* @route '/leads'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:14
* @route '/leads'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:14
* @route '/leads'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:14
* @route '/leads'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LeadController::index
* @see app/Http/Controllers/LeadController.php:14
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
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:133
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
* @see app/Http/Controllers/LeadController.php:133
* @route '/leads'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:133
* @route '/leads'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:133
* @route '/leads'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LeadController::store
* @see app/Http/Controllers/LeadController.php:133
* @route '/leads'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:170
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
* @see app/Http/Controllers/LeadController.php:170
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
* @see app/Http/Controllers/LeadController.php:170
* @route '/leads/{lead}'
*/
update.put = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:170
* @route '/leads/{lead}'
*/
update.patch = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\LeadController::update
* @see app/Http/Controllers/LeadController.php:170
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
* @see app/Http/Controllers/LeadController.php:170
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
* @see app/Http/Controllers/LeadController.php:170
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
* @see \App\Http\Controllers\LeadController::destroy
* @see app/Http/Controllers/LeadController.php:253
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
* @see app/Http/Controllers/LeadController.php:253
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
* @see app/Http/Controllers/LeadController.php:253
* @route '/leads/{lead}'
*/
destroy.delete = (args: { lead: number | { id: number } } | [lead: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\LeadController::destroy
* @see app/Http/Controllers/LeadController.php:253
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
* @see app/Http/Controllers/LeadController.php:253
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

const leads = {
    kanban: Object.assign(kanban, kanban),
    updateStatus: Object.assign(updateStatus, updateStatus),
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default leads