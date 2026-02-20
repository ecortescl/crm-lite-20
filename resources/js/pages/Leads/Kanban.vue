<template>
  <AppLayout title="Kanban de Leads">
    <div class="px-4 py-6 flex flex-col h-[calc(100vh-4rem)]">
      <div class="flex items-center justify-between mb-4">
        <Heading
          title="Kanban de Leads"
          description="Gestiona tus leads arrastrando entre estados"
        />
        <Button as-child variant="outline">
          <Link :href="route('leads.index')">Ver Lista</Link>
        </Button>
      </div>

      <!-- Filtros de Fecha -->
      <div class="flex gap-4 mb-6 items-end">
        <div class="flex-1 max-w-xs">
          <Label for="start_date">Desde</Label>
          <Input
            id="start_date"
            type="date"
            v-model="filters.start_date"
            @change="applyFilters"
          />
        </div>
        <div class="flex-1 max-w-xs">
          <Label for="end_date">Hasta</Label>
          <Input
            id="end_date"
            type="date"
            v-model="filters.end_date"
            @change="applyFilters"
          />
        </div>
        <Button variant="outline" @click="resetFilters">
          Últimos 30 días
        </Button>
      </div>

      <div class="flex gap-4 overflow-x-auto flex-1 pb-4">
        <div
          v-for="status in statuses"
          :key="status.id"
          class="shrink-0 w-[320px] flex flex-col"
        >
          <div class="bg-card rounded-lg border shadow-sm flex flex-col h-full">
            <!-- Header -->
            <div 
              class="px-4 py-3 border-b"
              :style="{ borderTopColor: status.color, borderTopWidth: '3px', borderTopLeftRadius: '0.5rem', borderTopRightRadius: '0.5rem' }"
            >
              <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm">{{ status.name }}</h3>
                <Badge variant="secondary" class="text-xs">{{ status.pagination.total }}</Badge>
              </div>
            </div>

            <!-- Content Area -->
            <div 
              class="flex-1 p-3 space-y-3 overflow-y-auto"
              :class="{ 'bg-muted/20': isDragOver === status.id }"
              @dragover.prevent="handleDragOver($event, status.id)"
              @dragleave="handleDragLeave"
              @drop="handleDrop($event, status.id)"
            >
              <div
                v-for="lead in status.leads"
                :key="lead.id"
                class="bg-background rounded-lg border p-3 cursor-pointer hover:shadow-md hover:border-primary/50 transition-all"
                :class="{ 'opacity-50': draggedLead?.id === lead.id }"
                :draggable="true"
                @dragstart="handleDragStart($event, lead)"
                @dragend="handleDragEnd"
                @click="openLeadModal(lead)"
              >
                <div class="space-y-2">
                  <div>
                    <h4 class="font-semibold text-sm leading-tight">{{ lead.name }}</h4>
                    <p class="text-xs text-muted-foreground mt-0.5">{{ lead.email || 'Sin email' }}</p>
                  </div>
                  
                  <div class="space-y-1.5 pt-1">
                    <div v-if="getCompanyName(lead)" class="flex items-center gap-2 text-xs text-muted-foreground">
                      <Building2 class="h-3.5 w-3.5 shrink-0" />
                      <span class="truncate">{{ getCompanyName(lead) }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-muted-foreground">
                      <User class="h-3.5 w-3.5 shrink-0" />
                      <span class="truncate">{{ lead.assigned_user?.name || 'Sin asignar' }}</span>
                    </div>
                    <div v-if="lead.budget" class="flex items-center gap-2 text-xs font-semibold text-green-600 dark:text-green-500">
                      <DollarSign class="h-3.5 w-3.5 shrink-0" />
                      <span>${{ formatNumber(lead.budget) }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div
                v-if="status.leads.length === 0"
                class="flex items-center justify-center h-32 text-sm text-muted-foreground border-2 border-dashed rounded-lg"
              >
                Arrastra leads aquí
              </div>
            </div>

            <!-- Paginación -->
            <div v-if="status.pagination.last_page > 1" class="px-3 py-2 border-t flex items-center justify-between text-xs">
              <Button
                variant="ghost"
                size="sm"
                :disabled="status.pagination.current_page === 1"
                @click="changePage(status.id, status.pagination.current_page - 1)"
              >
                Anterior
              </Button>
              <span class="text-muted-foreground">
                {{ status.pagination.current_page }} / {{ status.pagination.last_page }}
              </span>
              <Button
                variant="ghost"
                size="sm"
                :disabled="status.pagination.current_page === status.pagination.last_page"
                @click="changePage(status.id, status.pagination.current_page + 1)"
              >
                Siguiente
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Detalles del Lead -->
    <Dialog :open="showModal" @update:open="(value) => showModal = value">
      <DialogScrollContent v-if="showModal" class="max-w-3xl">
        <DialogHeader>
          <DialogTitle>{{ selectedLead?.name }}</DialogTitle>
          <DialogDescription>
            Información completa del lead
          </DialogDescription>
        </DialogHeader>

        <div v-if="selectedLead" class="space-y-6">
          <!-- Información Básica -->
          <div class="space-y-4">
            <h3 class="font-semibold text-sm">Información Básica</h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <Label>Email</Label>
                <p class="text-sm">{{ selectedLead.email || '-' }}</p>
              </div>
              <div>
                <Label>Teléfono</Label>
                <p class="text-sm">{{ selectedLead.phone || '-' }}</p>
              </div>
              <div>
                <Label>Empresa</Label>
                <p class="text-sm">{{ getCompanyName(selectedLead) || '-' }}</p>
              </div>
              <div>
                <Label>Asignado a</Label>
                <p class="text-sm">{{ selectedLead.assigned_user?.name || '-' }}</p>
              </div>
              <div v-if="selectedLead.source">
                <Label>Origen</Label>
                <p class="text-sm">{{ selectedLead.source }}</p>
              </div>
            </div>
            <div v-if="selectedLead.notes">
              <Label>Notas</Label>
              <p class="text-sm">{{ selectedLead.notes }}</p>
            </div>
          </div>

          <Separator />

          <!-- Marketing -->
          <div v-if="hasMarketingData(selectedLead)" class="space-y-4">
            <h3 class="font-semibold text-sm flex items-center gap-2">
              <TrendingUp class="h-4 w-4" />
              Datos de Marketing
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div v-if="selectedLead.utm_source">
                <Label>UTM Source</Label>
                <p>{{ selectedLead.utm_source }}</p>
              </div>
              <div v-if="selectedLead.utm_medium">
                <Label>UTM Medium</Label>
                <p>{{ selectedLead.utm_medium }}</p>
              </div>
              <div v-if="selectedLead.utm_campaign">
                <Label>UTM Campaign</Label>
                <p>{{ selectedLead.utm_campaign }}</p>
              </div>
              <div v-if="selectedLead.utm_term">
                <Label>UTM Term</Label>
                <p>{{ selectedLead.utm_term }}</p>
              </div>
              <div v-if="selectedLead.utm_content" class="col-span-2">
                <Label>UTM Content</Label>
                <p>{{ selectedLead.utm_content }}</p>
              </div>
            </div>
          </div>

          <Separator v-if="hasMarketingData(selectedLead)" />

          <!-- Agendamiento (si está en Reunión) -->
          <div v-if="isSchedulingStatus(selectedLead.lead_status_id)" class="space-y-4">
            <h3 class="font-semibold text-sm flex items-center gap-2">
              <Calendar class="h-4 w-4" />
              Agendamiento
            </h3>
            <div class="grid gap-4">
              <div>
                <Label for="scheduled_at">Fecha y Hora</Label>
                <Input
                  id="scheduled_at"
                  type="datetime-local"
                  v-model="editForm.scheduled_at"
                />
              </div>
              <div>
                <Label for="meeting_notes">Notas de la Reunión</Label>
                <Textarea
                  id="meeting_notes"
                  v-model="editForm.meeting_notes"
                  placeholder="Agenda, temas a tratar..."
                  rows="3"
                />
              </div>
            </div>
          </div>

          <!-- Negociación (si está en Negociación) -->
          <div v-if="isNegotiationStatus(selectedLead.lead_status_id)" class="space-y-4">
            <h3 class="font-semibold text-sm flex items-center gap-2">
              <DollarSign class="h-4 w-4" />
              Negociación
            </h3>
            <div class="grid gap-4">
              <div>
                <Label for="budget">Presupuesto</Label>
                <Input
                  id="budget"
                  type="number"
                  step="0.01"
                  v-model="editForm.budget"
                  placeholder="0.00"
                />
              </div>
              <div>
                <Label for="quote_items">Items Cotizados</Label>
                <Textarea
                  id="quote_items"
                  v-model="quoteItemsText"
                  placeholder="Describe los productos/servicios cotizados..."
                  rows="4"
                />
              </div>
            </div>
          </div>

          <!-- Cierre (si está en Concretado) -->
          <div v-if="isClosedStatus(selectedLead.lead_status_id)" class="space-y-4">
            <h3 class="font-semibold text-sm flex items-center gap-2">
              <FileCheck class="h-4 w-4" />
              Cierre del Negocio
            </h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <Label for="invoice_number">Número de Factura</Label>
                <Input
                  id="invoice_number"
                  v-model="editForm.invoice_number"
                  placeholder="F-001234"
                />
              </div>
              <div>
                <Label for="final_amount">Monto Final</Label>
                <Input
                  id="final_amount"
                  type="number"
                  step="0.01"
                  v-model="editForm.final_amount"
                  placeholder="0.00"
                />
              </div>
              <div>
                <Label for="payment_status">Estado de Pago</Label>
                <Select v-model="editForm.payment_status">
                  <SelectTrigger>
                    <SelectValue placeholder="Seleccionar" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="pending">Pendiente</SelectItem>
                    <SelectItem value="partial">Parcial</SelectItem>
                    <SelectItem value="paid">Pagado</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              <div>
                <Label for="closed_at">Fecha de Cierre</Label>
                <Input
                  id="closed_at"
                  type="datetime-local"
                  v-model="editForm.closed_at"
                />
              </div>
            </div>
          </div>
        </div>

        <DialogFooter>
          <Button variant="outline" @click="showModal = false">Cancelar</Button>
          <Button @click="saveLeadDetails" :disabled="processing">
            Guardar Cambios
          </Button>
        </DialogFooter>
      </DialogScrollContent>
    </Dialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, watch, reactive } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Dialog, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogScrollContent } from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Separator } from '@/components/ui/separator'
import { User, Building2, DollarSign, Calendar, FileCheck, TrendingUp } from 'lucide-vue-next'

const props = defineProps<{
  statuses: Array<any>
  filters: {
    start_date: string
    end_date: string
  }
}>()

const filters = reactive({
  start_date: props.filters.start_date,
  end_date: props.filters.end_date,
})

const draggedLead = ref<any>(null)
const isDragOver = ref<number | null>(null)
const showModal = ref(false)
const selectedLead = ref<any>(null)
const processing = ref(false)
const editForm = ref<any>({})
const quoteItemsText = ref('')

const applyFilters = () => {
  router.get(route('leads.kanban'), filters, {
    preserveState: true,
    preserveScroll: true,
  })
}

const resetFilters = () => {
  const today = new Date()
  const thirtyDaysAgo = new Date(today)
  thirtyDaysAgo.setDate(today.getDate() - 30)
  
  filters.start_date = thirtyDaysAgo.toISOString().split('T')[0]
  filters.end_date = today.toISOString().split('T')[0]
  
  applyFilters()
}

const changePage = (statusId: number, page: number) => {
  const params = {
    ...filters,
    [`status_${statusId}_page`]: page,
  }
  
  router.get(route('leads.kanban'), params, {
    preserveState: true,
    preserveScroll: true,
  })
}

const getCompanyName = (lead: any) => {
  if (lead.company) {
    // Si company es un objeto, usar display_name o business_name
    if (typeof lead.company === 'object') {
      return lead.company.display_name || lead.company.business_name || lead.company.fantasy_name
    }
    // Si es string, devolverlo directamente
    return lead.company
  }
  // Fallback al campo contact_company
  return lead.contact_company || null
}

const handleDragStart = (event: DragEvent, lead: any) => {
  draggedLead.value = lead
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move'
    event.dataTransfer.setData('text/html', '')
  }
}

const handleDragOver = (event: DragEvent, statusId: number) => {
  event.preventDefault()
  isDragOver.value = statusId
  if (event.dataTransfer) {
    event.dataTransfer.dropEffect = 'move'
  }
}

const handleDragLeave = () => {
  isDragOver.value = null
}

const handleDragEnd = () => {
  draggedLead.value = null
  isDragOver.value = null
}

const handleDrop = (event: DragEvent, statusId: number) => {
  event.preventDefault()
  isDragOver.value = null
  
  if (draggedLead.value && draggedLead.value.lead_status_id !== statusId) {
    router.patch(
      route('leads.update-status', draggedLead.value.id),
      { lead_status_id: statusId },
      { 
        preserveScroll: true,
        onSuccess: () => {
          draggedLead.value = null
        }
      }
    )
  }
}

const openLeadModal = (lead: any) => {
  selectedLead.value = lead
  editForm.value = {
    scheduled_at: lead.scheduled_at ? formatDateTimeLocal(lead.scheduled_at) : '',
    meeting_notes: lead.meeting_notes || '',
    budget: lead.budget || '',
    invoice_number: lead.invoice_number || '',
    final_amount: lead.final_amount || '',
    payment_status: lead.payment_status || 'pending',
    closed_at: lead.closed_at ? formatDateTimeLocal(lead.closed_at) : '',
  }
  quoteItemsText.value = lead.quote_items ? JSON.stringify(lead.quote_items, null, 2) : ''
  showModal.value = true
}

const saveLeadDetails = () => {
  processing.value = true
  
  const data = { ...editForm.value }
  
  // Convertir quote_items de texto a objeto si es necesario
  if (quoteItemsText.value) {
    try {
      data.quote_items = JSON.parse(quoteItemsText.value)
    } catch {
      data.quote_items = quoteItemsText.value
    }
  }
  
  router.patch(
    route('leads.update', selectedLead.value.id),
    data,
    {
      preserveScroll: true,
      onSuccess: () => {
        showModal.value = false
        processing.value = false
      },
      onError: () => {
        processing.value = false
      }
    }
  )
}

const isSchedulingStatus = (statusId: number) => {
  const status = props.statuses.find(s => s.id === statusId)
  return status?.name.toLowerCase().includes('reunión') || status?.name.toLowerCase().includes('reunion')
}

const isNegotiationStatus = (statusId: number) => {
  const status = props.statuses.find(s => s.id === statusId)
  return status?.name.toLowerCase().includes('negociación') || status?.name.toLowerCase().includes('negociacion')
}

const isClosedStatus = (statusId: number) => {
  const status = props.statuses.find(s => s.id === statusId)
  return status?.name.toLowerCase().includes('concretado') || status?.name.toLowerCase().includes('cerrado')
}

const formatNumber = (value: number) => {
  return new Intl.NumberFormat('es-CL').format(value)
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

const hasMarketingData = (lead: any) => {
  return lead.utm_source || lead.utm_medium || lead.utm_campaign || lead.utm_term || lead.utm_content
}

// Limpiar el formulario cuando el modal se cierra
watch(showModal, (newValue) => {
  if (!newValue) {
    // Limpiar inmediatamente
    selectedLead.value = null
    editForm.value = {}
    quoteItemsText.value = ''
  }
})
</script>
