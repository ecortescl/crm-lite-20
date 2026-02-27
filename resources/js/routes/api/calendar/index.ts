import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\CalendarApiController::meetings
* @see app/Http/Controllers/Api/CalendarApiController.php:52
* @route '/api/calendar/meetings'
*/
export const meetings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: meetings.url(options),
    method: 'get',
})

meetings.definition = {
    methods: ["get","head"],
    url: '/api/calendar/meetings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\CalendarApiController::meetings
* @see app/Http/Controllers/Api/CalendarApiController.php:52
* @route '/api/calendar/meetings'
*/
meetings.url = (options?: RouteQueryOptions) => {
    return meetings.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CalendarApiController::meetings
* @see app/Http/Controllers/Api/CalendarApiController.php:52
* @route '/api/calendar/meetings'
*/
meetings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: meetings.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::meetings
* @see app/Http/Controllers/Api/CalendarApiController.php:52
* @route '/api/calendar/meetings'
*/
meetings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: meetings.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::meetings
* @see app/Http/Controllers/Api/CalendarApiController.php:52
* @route '/api/calendar/meetings'
*/
const meetingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: meetings.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::meetings
* @see app/Http/Controllers/Api/CalendarApiController.php:52
* @route '/api/calendar/meetings'
*/
meetingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: meetings.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::meetings
* @see app/Http/Controllers/Api/CalendarApiController.php:52
* @route '/api/calendar/meetings'
*/
meetingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: meetings.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

meetings.form = meetingsForm

/**
* @see \App\Http\Controllers\Api\CalendarApiController::schedule
* @see app/Http/Controllers/Api/CalendarApiController.php:121
* @route '/api/calendar/meetings'
*/
export const schedule = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: schedule.url(options),
    method: 'post',
})

schedule.definition = {
    methods: ["post"],
    url: '/api/calendar/meetings',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\CalendarApiController::schedule
* @see app/Http/Controllers/Api/CalendarApiController.php:121
* @route '/api/calendar/meetings'
*/
schedule.url = (options?: RouteQueryOptions) => {
    return schedule.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CalendarApiController::schedule
* @see app/Http/Controllers/Api/CalendarApiController.php:121
* @route '/api/calendar/meetings'
*/
schedule.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: schedule.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::schedule
* @see app/Http/Controllers/Api/CalendarApiController.php:121
* @route '/api/calendar/meetings'
*/
const scheduleForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: schedule.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::schedule
* @see app/Http/Controllers/Api/CalendarApiController.php:121
* @route '/api/calendar/meetings'
*/
scheduleForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: schedule.url(options),
    method: 'post',
})

schedule.form = scheduleForm

/**
* @see \App\Http\Controllers\Api\CalendarApiController::cancel
* @see app/Http/Controllers/Api/CalendarApiController.php:175
* @route '/api/calendar/meetings/{lead_id}'
*/
export const cancel = (args: { lead_id: string | number } | [lead_id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: cancel.url(args, options),
    method: 'delete',
})

cancel.definition = {
    methods: ["delete"],
    url: '/api/calendar/meetings/{lead_id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\CalendarApiController::cancel
* @see app/Http/Controllers/Api/CalendarApiController.php:175
* @route '/api/calendar/meetings/{lead_id}'
*/
cancel.url = (args: { lead_id: string | number } | [lead_id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { lead_id: args }
    }

    if (Array.isArray(args)) {
        args = {
            lead_id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        lead_id: args.lead_id,
    }

    return cancel.definition.url
            .replace('{lead_id}', parsedArgs.lead_id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\CalendarApiController::cancel
* @see app/Http/Controllers/Api/CalendarApiController.php:175
* @route '/api/calendar/meetings/{lead_id}'
*/
cancel.delete = (args: { lead_id: string | number } | [lead_id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: cancel.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::cancel
* @see app/Http/Controllers/Api/CalendarApiController.php:175
* @route '/api/calendar/meetings/{lead_id}'
*/
const cancelForm = (args: { lead_id: string | number } | [lead_id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cancel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\CalendarApiController::cancel
* @see app/Http/Controllers/Api/CalendarApiController.php:175
* @route '/api/calendar/meetings/{lead_id}'
*/
cancelForm.delete = (args: { lead_id: string | number } | [lead_id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cancel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

cancel.form = cancelForm

const calendar = {
    meetings: Object.assign(meetings, meetings),
    schedule: Object.assign(schedule, schedule),
    cancel: Object.assign(cancel, cancel),
}

export default calendar