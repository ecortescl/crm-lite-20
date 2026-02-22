<template>
  <AppLayout title="Calendario">
    <div class="px-4 py-6 space-y-6">
      <div class="flex items-center justify-between">
        <Heading title="Calendario" description="Reuniones agendadas con tus leads" />
        <Button @click="startCreate">Nueva reunión</Button>
      </div>

      <div class="grid gap-6 lg:grid-cols-3">
        <Card class="lg:col-span-2">
          <CardHeader class="space-y-4">
            <div class="flex items-center justify-between gap-3">
              <CardTitle>Calendario de reuniones</CardTitle>
              <div class="inline-flex rounded-md border p-1">
                <Button size="sm" :variant="viewMode === 'week' ? 'default' : 'ghost'" @click="viewMode = 'week'">
                  Semana
                </Button>
                <Button size="sm" :variant="viewMode === 'month' ? 'default' : 'ghost'" @click="viewMode = 'month'">
                  Mes
                </Button>
                <Button size="sm" :variant="viewMode === 'list' ? 'default' : 'ghost'" @click="viewMode = 'list'">
                  Lista
                </Button>
              </div>
            </div>
            <div class="flex items-center justify-between gap-3">
              <div class="text-sm text-muted-foreground">
                {{ calendarLabel }}
              </div>
              <div class="flex items-center gap-2">
                <Button size="sm" variant="outline" @click="goPrev">Anterior</Button>
                <Button size="sm" variant="outline" @click="goToday">Hoy</Button>
                <Button size="sm" variant="outline" @click="goNext">Siguiente</Button>
              </div>
            </div>
          </CardHeader>
          <CardContent>
            <div v-if="sortedMeetings.length === 0" class="py-6 text-center text-sm text-muted-foreground">
              No hay reuniones agendadas
            </div>
            <div v-else-if="viewMode === 'list'" class="space-y-3">
              <div
                v-for="meeting in sortedMeetings"
                :key="meeting.id"
                class="flex items-center justify-between rounded-lg border p-3 hover:bg-muted/50 transition-colors"
              >
                <div class="min-w-0">
                  <p class="text-sm font-semibold truncate">{{ meeting.name }}</p>
                  <div class="text-xs text-muted-foreground">
                    {{ formatDate(meeting.scheduled_at) }} · {{ formatTime(meeting.scheduled_at) }}
                  </div>
                  <div v-if="meeting.meeting_link" class="text-xs text-muted-foreground truncate">
                    {{ meeting.meeting_link }}
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <Badge variant="secondary" class="text-[10px]">
                    {{ meeting.status?.name || 'Reunión' }}
                  </Badge>
                  <Button size="sm" variant="outline" @click="startEdit(meeting)">Editar</Button>
                </div>
              </div>
            </div>
            <div v-else-if="viewMode === 'week'" class="grid gap-3 md:grid-cols-7">
              <div
                v-for="day in weekDays"
                :key="day.key"
                class="rounded-lg border p-2 min-h-40"
              >
                <div class="mb-2 border-b pb-2">
                  <p class="text-xs text-muted-foreground">{{ day.label }}</p>
                  <p class="text-sm font-semibold">{{ day.dayNumber }}</p>
                </div>
                <div class="space-y-2">
                  <button
                    v-for="meeting in meetingsByDay[day.key] || []"
                    :key="meeting.id"
                    type="button"
                    class="w-full rounded-md border bg-muted/40 p-2 text-left text-xs hover:bg-muted"
                    @click="startEdit(meeting)"
                  >
                    <p class="truncate font-semibold">{{ formatTime(meeting.scheduled_at) }} · {{ meeting.name }}</p>
                    <p v-if="meeting.meeting_link" class="truncate text-muted-foreground">{{ meeting.meeting_link }}</p>
                  </button>
                  <p v-if="!(meetingsByDay[day.key] || []).length" class="text-xs text-muted-foreground">Sin reuniones</p>
                </div>
              </div>
            </div>
            <div v-else class="grid gap-3 md:grid-cols-7">
              <div
                v-for="day in monthDays"
                :key="day.key"
                class="rounded-lg border p-2 min-h-32"
                :class="day.isCurrentMonth ? '' : 'opacity-45'"
              >
                <div class="mb-2 flex items-center justify-between">
                  <p class="text-sm font-semibold">{{ day.dayNumber }}</p>
                  <Badge v-if="(meetingsByDay[day.key] || []).length" variant="secondary" class="text-[10px]">
                    {{ (meetingsByDay[day.key] || []).length }}
                  </Badge>
                </div>
                <div class="space-y-1">
                  <button
                    v-for="meeting in (meetingsByDay[day.key] || []).slice(0, 2)"
                    :key="meeting.id"
                    type="button"
                    class="w-full rounded-md border bg-muted/40 p-1.5 text-left text-xs hover:bg-muted"
                    @click="startEdit(meeting)"
                  >
                    <p class="truncate font-medium">{{ formatTime(meeting.scheduled_at) }} · {{ meeting.name }}</p>
                  </button>
                  <p
                    v-if="(meetingsByDay[day.key] || []).length > 2"
                    class="text-xs text-muted-foreground"
                  >
                    +{{ (meetingsByDay[day.key] || []).length - 2 }} más
                  </p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>{{ selectedMeeting ? 'Editar reunión' : 'Nueva reunión' }}</CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div>
              <Label for="lead_id">Lead *</Label>
              <Select v-model="form.lead_id" :disabled="!!selectedMeeting">
                <SelectTrigger class="mt-2">
                  <SelectValue placeholder="Selecciona un lead" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem v-for="lead in leads" :key="lead.id" :value="lead.id.toString()">
                    {{ lead.name }}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div>
              <Label for="scheduled_at">Fecha y Hora *</Label>
              <Input
                id="scheduled_at"
                type="datetime-local"
                v-model="form.scheduled_at"
                class="mt-2"
              />
            </div>
            <div>
              <Label for="meeting_link">Link de reunión</Label>
              <Input
                id="meeting_link"
                type="url"
                v-model="form.meeting_link"
                placeholder="https://meet.google.com/..."
                class="mt-2"
              />
            </div>
            <div>
              <Label for="meeting_notes">Notas</Label>
              <Textarea
                id="meeting_notes"
                v-model="form.meeting_notes"
                rows="3"
                placeholder="Agenda, temas a tratar..."
                class="mt-2"
              />
            </div>
            <div class="flex items-center justify-between pt-2">
              <Button variant="outline" @click="resetForm">Cancelar</Button>
              <div class="flex items-center gap-2">
                <Button v-if="selectedMeeting" variant="destructive" @click="deleteMeeting">
                  Eliminar
                </Button>
                <Button @click="saveMeeting">Guardar</Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed, reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { getSwal } from '@/lib/swal'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Badge } from '@/components/ui/badge'
import { route } from '@/lib/route'

const props = defineProps<{
  meetings: Array<any>
  leads: Array<any>
  meetingStatusId: number | null
  isAdmin: boolean
}>()

const selectedMeeting = ref<any>(null)
const viewMode = ref<'week' | 'month' | 'list'>('week')
const currentDate = ref(new Date())

const form = reactive({
  lead_id: '',
  scheduled_at: '',
  meeting_link: '',
  meeting_notes: '',
})

const sortedMeetings = computed(() => {
  return [...props.meetings].sort((a, b) => {
    return new Date(a.scheduled_at).getTime() - new Date(b.scheduled_at).getTime()
  })
})

const meetingsByDay = computed(() => {
  return sortedMeetings.value.reduce((acc: Record<string, any[]>, meeting) => {
    const key = toDayKey(meeting.scheduled_at)
    if (!acc[key]) acc[key] = []
    acc[key].push(meeting)
    return acc
  }, {})
})

const weekDays = computed(() => {
  const weekStart = getWeekStart(currentDate.value)
  return Array.from({ length: 7 }).map((_, index) => {
    const date = new Date(weekStart)
    date.setDate(weekStart.getDate() + index)
    return {
      key: toDayKey(date),
      label: new Intl.DateTimeFormat('es-CL', { weekday: 'short' }).format(date),
      dayNumber: date.getDate(),
    }
  })
})

const monthDays = computed(() => {
  const year = currentDate.value.getFullYear()
  const month = currentDate.value.getMonth()
  const firstDay = new Date(year, month, 1)
  const gridStart = getWeekStart(firstDay)

  return Array.from({ length: 42 }).map((_, index) => {
    const date = new Date(gridStart)
    date.setDate(gridStart.getDate() + index)
    return {
      key: toDayKey(date),
      dayNumber: date.getDate(),
      isCurrentMonth: date.getMonth() === month,
    }
  })
})

const calendarLabel = computed(() => {
  if (viewMode.value === 'week') {
    const start = getWeekStart(currentDate.value)
    const end = new Date(start)
    end.setDate(start.getDate() + 6)
    return `${new Intl.DateTimeFormat('es-CL', { dateStyle: 'medium' }).format(start)} - ${new Intl.DateTimeFormat('es-CL', { dateStyle: 'medium' }).format(end)}`
  }

  if (viewMode.value === 'month') {
    return new Intl.DateTimeFormat('es-CL', { month: 'long', year: 'numeric' }).format(currentDate.value)
  }

  return 'Listado cronológico'
})

const startCreate = () => {
  selectedMeeting.value = null
  form.lead_id = ''
  form.scheduled_at = ''
  form.meeting_link = ''
  form.meeting_notes = ''
}

const startEdit = (meeting: any) => {
  selectedMeeting.value = meeting
  form.lead_id = meeting.id.toString()
  form.scheduled_at = formatDateTimeLocal(meeting.scheduled_at)
  form.meeting_link = meeting.meeting_link || ''
  form.meeting_notes = meeting.meeting_notes || ''
}

const resetForm = () => {
  startCreate()
}

const goPrev = () => {
  const date = new Date(currentDate.value)
  if (viewMode.value === 'month') {
    date.setMonth(date.getMonth() - 1)
  } else {
    date.setDate(date.getDate() - 7)
  }
  currentDate.value = date
}

const goNext = () => {
  const date = new Date(currentDate.value)
  if (viewMode.value === 'month') {
    date.setMonth(date.getMonth() + 1)
  } else {
    date.setDate(date.getDate() + 7)
  }
  currentDate.value = date
}

const goToday = () => {
  currentDate.value = new Date()
}

const saveMeeting = () => {
  if (!form.lead_id || !form.scheduled_at) {
    getSwal().fire({
      title: 'Datos requeridos',
      text: 'Selecciona un lead y define fecha/hora.',
      icon: 'warning',
    })
    return
  }

  const payload: Record<string, any> = {
    scheduled_at: form.scheduled_at,
    meeting_link: form.meeting_link || null,
    meeting_notes: form.meeting_notes || null,
  }

  if (props.meetingStatusId) {
    payload.lead_status_id = props.meetingStatusId
  }

  router.patch(route('leads.update', Number(form.lead_id)), payload, {
    preserveScroll: true,
    onSuccess: () => {
      if (!selectedMeeting.value) startCreate()
    },
  })
}

const deleteMeeting = () => {
  if (!selectedMeeting.value) return

  getSwal().fire({
    title: 'Eliminar reunión',
    text: 'Esto quitará la fecha y el link de la reunión.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar',
    reverseButtons: true,
  }).then((result) => {
    if (!result.isConfirmed) return
    router.patch(route('leads.update', selectedMeeting.value.id), {
      scheduled_at: null,
      meeting_link: null,
      meeting_notes: null,
    }, {
      preserveScroll: true,
      onSuccess: () => {
        startCreate()
      },
    })
  })
}

const formatDate = (value: string) => {
  return new Intl.DateTimeFormat('es-CL', { dateStyle: 'medium' }).format(new Date(value))
}

const formatTime = (value: string) => {
  return new Intl.DateTimeFormat('es-CL', { timeStyle: 'short' }).format(new Date(value))
}

const formatDateTimeLocal = (dateString: string) => {
  const date = new Date(dateString)
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')
  return `${year}-${month}-${day}T${hours}:${minutes}`
}

const toDayKey = (value: string | Date) => {
  const date = value instanceof Date ? value : new Date(value)
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const day = String(date.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

const getWeekStart = (base: Date) => {
  const date = new Date(base)
  const day = date.getDay()
  const mondayOffset = day === 0 ? -6 : 1 - day
  date.setDate(date.getDate() + mondayOffset)
  date.setHours(0, 0, 0, 0)
  return date
}
</script>
