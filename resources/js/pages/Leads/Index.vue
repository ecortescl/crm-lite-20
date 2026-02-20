<template>
  <AppLayout title="Leads">
    <div class="px-4 py-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <Heading
          title="Leads"
          description="Gestiona tus leads y oportunidades"
        />
        <Button @click="openCreateDialog">
          <Plus class="w-4 h-4 mr-2" />
          Crear Lead
        </Button>
      </div>

      <!-- Filtros y Búsqueda -->
      <Card>
        <div class="p-4 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Búsqueda -->
            <div class="md:col-span-2">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  v-model="searchQuery"
                  placeholder="Buscar por nombre, email, teléfono..."
                  class="pl-9"
                  @input="debouncedSearch"
                />
              </div>
            </div>

            <!-- Filtro por Estado -->
            <Select v-model="filterStatus" @update:model-value="applyFilters">
              <SelectTrigger>
                <SelectValue placeholder="Todos los estados" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos los estados</SelectItem>
                <SelectItem v-for="status in statuses" :key="status.id" :value="status.id.toString()">
                  {{ status.name }}
                </SelectItem>
              </SelectContent>
            </Select>

            <!-- Filtro por Asignado -->
            <Select v-model="filterAssigned" @update:model-value="applyFilters">
              <SelectTrigger>
                <SelectValue placeholder="Todos los usuarios" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos los usuarios</SelectItem>
                <SelectItem v-for="user in users" :key="user.id" :value="user.id.toString()">
                  {{ user.name }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Botón limpiar filtros -->
          <div v-if="hasActiveFilters" class="flex justify-end">
            <Button variant="ghost" size="sm" @click="clearFilters">
              <X class="w-4 h-4 mr-2" />
              Limpiar filtros
            </Button>
          </div>
        </div>
      </Card>

      <!-- Tabla -->
      <Card>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Nombre</TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Teléfono</TableHead>
              <TableHead>Empresa</TableHead>
              <TableHead>Origen</TableHead>
              <TableHead>Estado</TableHead>
              <TableHead>Asignado a</TableHead>
              <TableHead class="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-if="leads.data.length === 0">
              <TableCell colspan="8" class="text-center py-8 text-muted-foreground">
                No se encontraron leads
              </TableCell>
            </TableRow>
            <TableRow v-for="lead in leads.data" :key="lead.id">
              <TableCell class="font-medium">{{ lead.name }}</TableCell>
              <TableCell>{{ lead.email || '-' }}</TableCell>
              <TableCell>{{ lead.phone || '-' }}</TableCell>
              <TableCell>
                <span v-if="lead.company">
                  {{ lead.company.display_name }}
                  <Badge variant="outline" class="ml-1 text-xs">Registrada</Badge>
                </span>
                <span v-else-if="lead.contact_company" class="text-muted-foreground">
                  {{ lead.contact_company }}
                </span>
                <span v-else class="text-muted-foreground italic">
                  Sin empresa
                </span>
              </TableCell>
              <TableCell>
                <Badge variant="outline" v-if="lead.source">{{ lead.source }}</Badge>
                <span v-else class="text-muted-foreground">-</span>
              </TableCell>
              <TableCell>
                <Badge :style="{ backgroundColor: lead.status.color }">
                  {{ lead.status.name }}
                </Badge>
              </TableCell>
              <TableCell>{{ lead.assigned_user?.name || '-' }}</TableCell>
              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm">
                      <MoreVertical class="w-4 h-4" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="openEditDialog(lead)">
                      <Pencil class="w-4 h-4 mr-2" />
                      Editar
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="confirmDelete(lead)" class="text-destructive">
                      <Trash2 class="w-4 h-4 mr-2" />
                      Eliminar
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>

        <!-- Paginación -->
        <div v-if="leads.data.length > 0" class="p-4 border-t">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              Mostrando {{ leads.from }} a {{ leads.to }} de {{ leads.total }} resultados
            </div>
            <div class="flex gap-2">
              <Button
                variant="outline"
                size="sm"
                :disabled="!leads.prev_page_url"
                @click="goToPage(leads.current_page - 1)"
              >
                <ChevronLeft class="w-4 h-4" />
                Anterior
              </Button>
              <Button
                variant="outline"
                size="sm"
                :disabled="!leads.next_page_url"
                @click="goToPage(leads.current_page + 1)"
              >
                Siguiente
                <ChevronRight class="w-4 h-4" />
              </Button>
            </div>
          </div>
        </div>
      </Card>

      <!-- Dialog Crear/Editar -->
      <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
        <DialogContent class="max-w-2xl max-h-[85vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{{ editingLead ? 'Editar Lead' : 'Crear Nuevo Lead' }}</DialogTitle>
            <DialogDescription>
              Completa la información del lead.
            </DialogDescription>
          </DialogHeader>

          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Información Básica -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Información Básica</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <Label for="name">Nombre del Contacto *</Label>
                  <Input id="name" v-model="form.name" required placeholder="Juan Pérez" class="mt-2" />
                </div>
                <div>
                  <Label for="email">Email</Label>
                  <Input id="email" v-model="form.email" type="email" placeholder="juan@ejemplo.cl" class="mt-2" />
                </div>
                <div>
                  <Label for="phone">Teléfono</Label>
                  <Input id="phone" v-model="form.phone" placeholder="+56 9 1234 5678" class="mt-2" />
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Empresa (Opcional) -->
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold">Empresa</h3>
                <span class="text-xs text-muted-foreground">(Opcional)</span>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                  <Label for="company">Seleccionar Empresa Registrada</Label>
                  <Select v-model="form.company_id">
                    <SelectTrigger class="mt-2">
                      <SelectValue placeholder="Sin empresa asociada" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="unassigned">Sin empresa asociada</SelectItem>
                      <SelectItem v-for="company in companies" :key="company.id" :value="company.id.toString()">
                        {{ company.fantasy_name || company.business_name }} ({{ company.rut }})
                      </SelectItem>
                    </SelectContent>
                  </Select>
                  <p class="text-xs text-muted-foreground mt-1">
                    Si la empresa no está registrada, puedes escribirla abajo
                  </p>
                </div>
                <div class="col-span-2">
                  <Label for="contact_company">O escribir nombre de empresa</Label>
                  <Input 
                    id="contact_company" 
                    v-model="form.contact_company" 
                    placeholder="Nombre de la empresa (si no está en la lista)" 
                    class="mt-2"
                    :disabled="form.company_id !== 'unassigned'"
                  />
                  <p class="text-xs text-muted-foreground mt-1" v-if="form.company_id !== 'unassigned'">
                    Deselecciona la empresa arriba para escribir manualmente
                  </p>
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Asignación -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Asignación</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <Label for="status">Estado *</Label>
                  <Select v-model="form.lead_status_id">
                    <SelectTrigger class="mt-2">
                      <SelectValue placeholder="Selecciona un estado" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem v-for="status in statuses" :key="status.id" :value="status.id.toString()">
                        {{ status.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label for="assigned">Asignado a</Label>
                  <Select v-model="form.assigned_to">
                    <SelectTrigger class="mt-2">
                      <SelectValue placeholder="Sin asignar" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="unassigned">Sin asignar</SelectItem>
                      <SelectItem v-for="user in users" :key="user.id" :value="user.id.toString()">
                        {{ user.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Marketing -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Origen y Marketing</h3>
              <div>
                <Label for="source">Origen del Lead</Label>
                <Select v-model="form.source">
                  <SelectTrigger class="mt-2">
                    <SelectValue placeholder="Sin especificar" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="unspecified">Sin especificar</SelectItem>
                    <SelectItem value="Web">Sitio Web</SelectItem>
                    <SelectItem value="Referido">Referido</SelectItem>
                    <SelectItem value="Redes Sociales">Redes Sociales</SelectItem>
                    <SelectItem value="Email Marketing">Email Marketing</SelectItem>
                    <SelectItem value="Publicidad">Publicidad</SelectItem>
                    <SelectItem value="Evento">Evento</SelectItem>
                    <SelectItem value="Llamada">Llamada Directa</SelectItem>
                    <SelectItem value="Otro">Otro</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>

            <div class="border-t" />

            <!-- Notas y Detalles -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Notas y Detalles</h3>
              <div>
                <Label for="notes">Notas</Label>
                <Textarea id="notes" v-model="form.notes" rows="3" class="mt-2" />
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <Label for="budget">Presupuesto</Label>
                  <Input id="budget" type="number" step="0.01" v-model="form.budget" placeholder="0.00" class="mt-2" />
                </div>
                <div>
                  <Label for="scheduled_at">Fecha de Reunión</Label>
                  <Input id="scheduled_at" type="datetime-local" v-model="form.scheduled_at" class="mt-2" />
                </div>
              </div>
            </div>

            <DialogFooter>
              <Button type="button" variant="outline" @click="dialogOpen = false">
                Cancelar
              </Button>
              <Button type="submit" :disabled="processing">
                {{ editingLead ? 'Actualizar' : 'Crear' }}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      <!-- Alert Dialog para confirmar eliminación -->
      <AlertDialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Estás seguro?</AlertDialogTitle>
            <AlertDialogDescription>
              Esta acción no se puede deshacer. Se eliminará permanentemente el lead
              <strong>{{ leadToDelete?.name }}</strong>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="deleteLead" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Eliminar
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, watch, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { useToast } from '@/composables/useToast'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Card } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Textarea } from '@/components/ui/textarea'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { Plus, Search, X, MoreVertical, Pencil, Trash2, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps<{
  leads: any
  statuses: Array<any>
  users: Array<any>
  companies: Array<any>
  filters: any
}>()

const { success, error } = useToast()

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const editingLead = ref<any>(null)
const leadToDelete = ref<any>(null)
const processing = ref(false)

// Filtros
const searchQuery = ref(props.filters?.search || '')
const filterStatus = ref(props.filters?.status || 'all')
const filterAssigned = ref(props.filters?.assigned || 'all')

const hasActiveFilters = computed(() => {
  return searchQuery.value || filterStatus.value !== 'all' || filterAssigned.value !== 'all'
})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  contact_company: '',
  company_id: '',
  notes: '',
  lead_status_id: '',
  assigned_to: '',
  source: '',
  utm_source: '',
  utm_medium: '',
  utm_campaign: '',
  utm_term: '',
  utm_content: '',
  scheduled_at: '',
  meeting_notes: '',
  budget: '',
  quote_items: '',
  invoice_number: '',
  final_amount: '',
  payment_status: '',
  closed_at: '',
})

let searchTimeout: ReturnType<typeof setTimeout>

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

const applyFilters = () => {
  router.get(route('leads.index'), {
    search: searchQuery.value || undefined,
    status: filterStatus.value !== 'all' ? filterStatus.value : undefined,
    assigned: filterAssigned.value !== 'all' ? filterAssigned.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const clearFilters = () => {
  searchQuery.value = ''
  filterStatus.value = 'all'
  filterAssigned.value = 'all'
  router.get(route('leads.index'))
}

const goToPage = (page: number) => {
  router.get(route('leads.index'), {
    page,
    search: searchQuery.value || undefined,
    status: filterStatus.value !== 'all' ? filterStatus.value : undefined,
    assigned: filterAssigned.value !== 'all' ? filterAssigned.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const openCreateDialog = () => {
  editingLead.value = null
  resetForm()
  dialogOpen.value = true
}

const openEditDialog = (lead: any) => {
  editingLead.value = lead
  form.name = lead.name
  form.email = lead.email || ''
  form.phone = lead.phone || ''
  form.contact_company = lead.contact_company || ''
  form.company_id = lead.company_id?.toString() || 'unassigned'
  form.notes = lead.notes || ''
  form.lead_status_id = lead.lead_status_id.toString()
  form.assigned_to = lead.assigned_to?.toString() || 'unassigned'
  form.source = lead.source || 'unspecified'
  form.utm_source = lead.utm_source || ''
  form.utm_medium = lead.utm_medium || ''
  form.utm_campaign = lead.utm_campaign || ''
  form.utm_term = lead.utm_term || ''
  form.utm_content = lead.utm_content || ''
  form.scheduled_at = lead.scheduled_at ? formatDateTimeLocal(lead.scheduled_at) : ''
  form.meeting_notes = lead.meeting_notes || ''
  form.budget = lead.budget || ''
  form.quote_items = lead.quote_items ? (typeof lead.quote_items === 'string' ? lead.quote_items : JSON.stringify(lead.quote_items, null, 2)) : ''
  form.invoice_number = lead.invoice_number || ''
  form.final_amount = lead.final_amount || ''
  form.payment_status = lead.payment_status || 'unspecified'
  form.closed_at = lead.closed_at ? formatDateTimeLocal(lead.closed_at) : ''
  dialogOpen.value = true
}

const resetForm = () => {
  form.name = ''
  form.email = ''
  form.phone = ''
  form.contact_company = ''
  form.company_id = 'unassigned'
  form.notes = ''
  form.lead_status_id = props.statuses[0]?.id.toString() || ''
  form.assigned_to = 'unassigned'
  form.source = 'unspecified'
  form.utm_source = ''
  form.utm_medium = ''
  form.utm_campaign = ''
  form.utm_term = ''
  form.utm_content = ''
  form.scheduled_at = ''
  form.meeting_notes = ''
  form.budget = ''
  form.quote_items = ''
  form.invoice_number = ''
  form.final_amount = ''
  form.payment_status = 'unspecified'
  form.closed_at = ''
}

const submitForm = () => {
  processing.value = true
  
  const data: any = {
    name: form.name,
    email: form.email || null,
    phone: form.phone || null,
    contact_company: form.contact_company || null,
    company_id: (form.company_id && form.company_id !== 'unassigned') ? parseInt(form.company_id) : null,
    notes: form.notes || null,
    lead_status_id: parseInt(form.lead_status_id),
    assigned_to: (form.assigned_to && form.assigned_to !== 'unassigned') ? parseInt(form.assigned_to) : null,
    source: (form.source && form.source !== 'unspecified') ? form.source : null,
    utm_source: form.utm_source || null,
    utm_medium: form.utm_medium || null,
    utm_campaign: form.utm_campaign || null,
    utm_term: form.utm_term || null,
    utm_content: form.utm_content || null,
    scheduled_at: form.scheduled_at || null,
    meeting_notes: form.meeting_notes || null,
    budget: form.budget || null,
    quote_items: form.quote_items || null,
    invoice_number: form.invoice_number || null,
    final_amount: form.final_amount || null,
    payment_status: (form.payment_status && form.payment_status !== 'unspecified') ? form.payment_status : null,
    closed_at: form.closed_at || null,
  }

  if (editingLead.value) {
    router.put(route('leads.update', editingLead.value.id), data, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Lead actualizado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al actualizar el lead')
      },
      preserveScroll: true,
    })
  } else {
    router.post(route('leads.store'), data, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Lead creado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al crear el lead')
      },
      preserveScroll: true,
    })
  }
}

const confirmDelete = (lead: any) => {
  leadToDelete.value = lead
  deleteDialogOpen.value = true
}

const deleteLead = () => {
  if (!leadToDelete.value) return

  router.delete(route('leads.destroy', leadToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      leadToDelete.value = null
      success('Lead eliminado exitosamente')
    },
    onError: () => {
      error('Error al eliminar el lead')
    },
    preserveScroll: true,
  })
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

watch(dialogOpen, (newValue) => {
  if (!newValue) {
    resetForm()
    editingLead.value = null
  }
})
</script>
