<template>
  <AppLayout title="Estados de Leads">
    <div class="px-4 py-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <Heading
          title="Estados de Leads"
          description="Personaliza los estados del pipeline"
        />
        <Button @click="openCreateDialog">
          <Plus class="w-4 h-4 mr-2" />
          Crear Estado
        </Button>
      </div>

      <!-- Búsqueda -->
      <Card>
        <div class="p-4">
          <div class="relative max-w-md">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input
              v-model="searchQuery"
              placeholder="Buscar por nombre..."
              class="pl-9"
              @input="debouncedSearch"
            />
          </div>
        </div>
      </Card>

      <!-- Tabla -->
      <Card>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Orden</TableHead>
              <TableHead>Nombre</TableHead>
              <TableHead>Icono</TableHead>
              <TableHead>Color</TableHead>
              <TableHead>Leads</TableHead>
              <TableHead class="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-if="statuses.length === 0">
              <TableCell colspan="6" class="text-center py-8 text-muted-foreground">
                No se encontraron estados
              </TableCell>
            </TableRow>
            <TableRow v-for="status in statuses" :key="status.id">
              <TableCell>{{ status.order }}</TableCell>
              <TableCell class="font-medium">{{ status.name }}</TableCell>
              <TableCell>
                <div class="flex items-center gap-2">
                  <div
                    class="h-8 w-8 rounded-md flex items-center justify-center border bg-muted/40"
                    :style="{ borderColor: status.color }"
                  >
                    <component
                      :is="getIcon(status.icon, status.name)"
                      class="h-4 w-4"
                      :style="{ color: status.color }"
                    />
                  </div>
                  <span class="text-xs text-muted-foreground">{{ status.icon || 'Auto' }}</span>
                </div>
              </TableCell>
              <TableCell>
                <div class="flex items-center gap-2">
                  <div
                    class="h-4 w-4 rounded-full border"
                    :style="{ backgroundColor: status.color }"
                  />
                  <span class="text-sm text-muted-foreground">{{ status.color }}</span>
                </div>
              </TableCell>
              <TableCell>
                <Badge variant="secondary">{{ status.leads_count }} leads</Badge>
              </TableCell>
              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm">
                      <MoreVertical class="w-4 h-4" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="openEditDialog(status)">
                      <Pencil class="w-4 h-4 mr-2" />
                      Editar
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="confirmDelete(status)" class="text-destructive">
                      <Trash2 class="w-4 h-4 mr-2" />
                      Eliminar
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </Card>

      <!-- Dialog Crear/Editar -->
      <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
        <DialogContent class="max-w-lg">
          <DialogHeader>
            <DialogTitle>{{ editingStatus ? 'Editar Estado' : 'Crear Estado' }}</DialogTitle>
            <DialogDescription>
              Completa la información del estado.
            </DialogDescription>
          </DialogHeader>
          <form @submit.prevent="submitForm" class="space-y-4">
            <div>
              <Label for="name">Nombre *</Label>
              <Input id="name" v-model="form.name" required placeholder="ej: Nuevo registro" class="mt-2" />
            </div>
            <div>
              <Label for="color">Color *</Label>
              <div class="flex gap-2 mt-2">
                <Input id="color" v-model="form.color" type="color" required class="w-20 h-10" />
                <Input v-model="form.color" placeholder="#6b7280" class="flex-1" />
              </div>
            </div>
            <div>
              <Label for="order">Orden *</Label>
              <Input id="order" v-model.number="form.order" type="number" required min="1" class="mt-2" />
            </div>
            <div>
              <Label for="icon">Icono</Label>
              <Select v-model="form.icon">
                <SelectTrigger class="mt-2">
                  <SelectValue placeholder="Selecciona un icono" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem :value="null">
                    <div class="flex items-center gap-2">
                      <span class="text-xs text-muted-foreground">Auto (por nombre)</span>
                    </div>
                  </SelectItem>
                  <SelectItem v-for="option in iconOptions" :key="option.value" :value="option.value">
                    <div class="flex items-center gap-2">
                      <component :is="option.icon" class="h-4 w-4" />
                      <span class="text-sm">{{ option.label }}</span>
                    </div>
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" @click="dialogOpen = false">Cancelar</Button>
              <Button type="submit" :disabled="processing">{{ editingStatus ? 'Actualizar' : 'Crear' }}</Button>
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
              Esta acción no se puede deshacer. Se eliminará permanentemente el estado
              <strong>{{ statusToDelete?.name }}</strong>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="deleteStatus" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Eliminar
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
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
import { 
  Plus, 
  Search, 
  MoreVertical, 
  Pencil, 
  Trash2,
  FileText,
  Phone,
  XCircle,
  Calendar,
  TrendingUp,
  CheckCircle2,
  Users,
  Mail,
  MessageCircle,
  Target,
  Zap,
  Star
} from 'lucide-vue-next'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'

const props = defineProps<{
  statuses: Array<any>
  filters: any
}>()

const { success, error } = useToast()

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const editingStatus = ref<any>(null)
const statusToDelete = ref<any>(null)
const processing = ref(false)

// Búsqueda
const searchQuery = ref(props.filters?.search || '')

const form = reactive({
  name: '',
  color: '#6b7280',
  order: 0,
  icon: null as string | null,
})

let searchTimeout: ReturnType<typeof setTimeout>

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get(route('lead-statuses.index'), {
      search: searchQuery.value || undefined,
    }, {
      preserveState: true,
      preserveScroll: true,
    })
  }, 500)
}

const openCreateDialog = () => {
  editingStatus.value = null
  form.name = ''
  form.color = '#6b7280'
  form.order = props.statuses.length + 1
  form.icon = null
  dialogOpen.value = true
}

const openEditDialog = (status: any) => {
  editingStatus.value = status
  form.name = status.name
  form.color = status.color
  form.order = status.order
  form.icon = status.icon ?? null
  dialogOpen.value = true
}

const submitForm = () => {
  processing.value = true

  if (editingStatus.value) {
    router.put(route('lead-statuses.update', editingStatus.value.id), form, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Estado actualizado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al actualizar el estado')
      },
      preserveScroll: true,
    })
  } else {
    router.post(route('lead-statuses.store'), form, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Estado creado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al crear el estado')
      },
      preserveScroll: true,
    })
  }
}

const confirmDelete = (status: any) => {
  statusToDelete.value = status
  deleteDialogOpen.value = true
}

const deleteStatus = () => {
  if (!statusToDelete.value) return

  router.delete(route('lead-statuses.destroy', statusToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      statusToDelete.value = null
      success('Estado eliminado exitosamente')
    },
    onError: () => {
      error('Error al eliminar el estado')
    },
    preserveScroll: true,
  })
}

watch(dialogOpen, (newValue) => {
  if (!newValue) {
    form.name = ''
    form.color = '#6b7280'
    form.order = 0
    form.icon = null
    editingStatus.value = null
  }
})

const iconOptions = [
  { value: 'FileText', label: 'Documento', icon: FileText },
  { value: 'Phone', label: 'Teléfono', icon: Phone },
  { value: 'XCircle', label: 'Descartado', icon: XCircle },
  { value: 'Calendar', label: 'Calendario', icon: Calendar },
  { value: 'TrendingUp', label: 'Tendencia', icon: TrendingUp },
  { value: 'CheckCircle2', label: 'Confirmado', icon: CheckCircle2 },
  { value: 'Users', label: 'Usuarios', icon: Users },
  { value: 'Mail', label: 'Email', icon: Mail },
  { value: 'MessageCircle', label: 'Mensajes', icon: MessageCircle },
  { value: 'Target', label: 'Objetivo', icon: Target },
  { value: 'Zap', label: 'Rápido', icon: Zap },
  { value: 'Star', label: 'Favorito', icon: Star },
]

const iconMap: Record<string, any> = {
  FileText,
  Phone,
  XCircle,
  Calendar,
  TrendingUp,
  CheckCircle2,
  Users,
  Mail,
  MessageCircle,
  Target,
  Zap,
  Star,
}

const getIcon = (iconName?: string | null, fallbackName?: string) => {
  if (iconName && iconMap[iconName]) return iconMap[iconName]
  const name = (fallbackName || '').toLowerCase()
  if (name.includes('nuevo')) return FileText
  if (name.includes('contactado')) return Phone
  if (name.includes('descartado')) return XCircle
  if (name.includes('reunión') || name.includes('reunion')) return Calendar
  if (name.includes('negociación') || name.includes('negociacion')) return TrendingUp
  if (name.includes('concretado')) return CheckCircle2
  return FileText
}
</script>
