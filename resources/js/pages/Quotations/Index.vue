<template>
  <AppLayout title="Cotizaciones">
    <div class="px-4 py-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <Heading
          title="Cotizaciones"
          description="Gestiona las cotizaciones de tus clientes"
        />
        <Button @click="router.visit(route('quotations.create'))">
          <Plus class="w-4 h-4 mr-2" />
          Nueva Cotización
        </Button>
      </div>

      <!-- Filtros -->
      <Card>
        <div class="p-4 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  v-model="searchQuery"
                  placeholder="Buscar por número, cliente, RUT..."
                  class="pl-9"
                  @input="debouncedSearch"
                />
              </div>
            </div>

            <Select v-model="filterStatus" @update:model-value="applyFilters">
              <SelectTrigger>
                <SelectValue placeholder="Todos los estados" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos los estados</SelectItem>
                <SelectItem value="draft">Borrador</SelectItem>
                <SelectItem value="sent">Enviada</SelectItem>
                <SelectItem value="accepted">Aceptada</SelectItem>
                <SelectItem value="rejected">Rechazada</SelectItem>
                <SelectItem value="expired">Expirada</SelectItem>
              </SelectContent>
            </Select>
          </div>

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
              <TableHead>Número</TableHead>
              <TableHead>Cliente</TableHead>
              <TableHead>Fecha Emisión</TableHead>
              <TableHead>Válida Hasta</TableHead>
              <TableHead>Total</TableHead>
              <TableHead>Estado</TableHead>
              <TableHead>Emitida Por</TableHead>
              <TableHead class="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-if="quotations.data.length === 0">
              <TableCell colspan="8" class="text-center py-8 text-muted-foreground">
                No se encontraron cotizaciones
              </TableCell>
            </TableRow>
            <TableRow v-for="quotation in quotations.data" :key="quotation.id">
              <TableCell class="font-medium">{{ quotation.quotation_number }}</TableCell>
              <TableCell>{{ quotation.client_name }}</TableCell>
              <TableCell>{{ formatDate(quotation.issue_date) }}</TableCell>
              <TableCell>{{ formatDate(quotation.valid_until) }}</TableCell>
              <TableCell>${{ formatNumber(quotation.total) }}</TableCell>
              <TableCell>
                <Badge :variant="getStatusVariant(quotation.status)">
                  {{ getStatusLabel(quotation.status) }}
                </Badge>
              </TableCell>
              <TableCell>{{ quotation.user?.name }}</TableCell>
              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm">
                      <MoreVertical class="w-4 h-4" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="router.visit(route('quotations.show', quotation.id))">
                      <Eye class="w-4 h-4 mr-2" />
                      Ver
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="router.visit(route('quotations.edit', quotation.id))">
                      <Pencil class="w-4 h-4 mr-2" />
                      Editar
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="downloadPdf(quotation.id)">
                      <FileText class="w-4 h-4 mr-2" />
                      Descargar PDF
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="confirmDelete(quotation)" class="text-destructive">
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
        <div v-if="quotations.data.length > 0" class="p-4 border-t">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              Mostrando {{ quotations.from }} a {{ quotations.to }} de {{ quotations.total }} resultados
            </div>
            <div class="flex gap-2">
              <Button
                variant="outline"
                size="sm"
                :disabled="!quotations.prev_page_url"
                @click="goToPage(quotations.current_page - 1)"
              >
                <ChevronLeft class="w-4 h-4" />
                Anterior
              </Button>
              <Button
                variant="outline"
                size="sm"
                :disabled="!quotations.next_page_url"
                @click="goToPage(quotations.current_page + 1)"
              >
                Siguiente
                <ChevronRight class="w-4 h-4" />
              </Button>
            </div>
          </div>
        </div>
      </Card>

      <!-- Alert Dialog -->
      <AlertDialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Estás seguro?</AlertDialogTitle>
            <AlertDialogDescription>
              Esta acción no se puede deshacer. Se eliminará permanentemente la cotización
              <strong>{{ quotationToDelete?.quotation_number }}</strong>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="deleteQuotation" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Eliminar
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import { useToast } from '@/composables/useToast'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Card } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
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
import { Plus, Search, X, MoreVertical, Pencil, Trash2, ChevronLeft, ChevronRight, Eye, FileText } from 'lucide-vue-next'

const props = defineProps<{
  quotations: any
  filters: any
}>()

const { success, error } = useToast()

const deleteDialogOpen = ref(false)
const quotationToDelete = ref<any>(null)

const searchQuery = ref(props.filters?.search || '')
const filterStatus = ref(props.filters?.status || 'all')

const hasActiveFilters = computed(() => {
  return searchQuery.value || filterStatus.value !== 'all'
})

let searchTimeout: ReturnType<typeof setTimeout>

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

const applyFilters = () => {
  router.get(route('quotations.index'), {
    search: searchQuery.value || undefined,
    status: filterStatus.value !== 'all' ? filterStatus.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const clearFilters = () => {
  searchQuery.value = ''
  filterStatus.value = 'all'
  router.get(route('quotations.index'))
}

const goToPage = (page: number) => {
  router.get(route('quotations.index'), {
    page,
    search: searchQuery.value || undefined,
    status: filterStatus.value !== 'all' ? filterStatus.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const confirmDelete = (quotation: any) => {
  quotationToDelete.value = quotation
  deleteDialogOpen.value = true
}

const deleteQuotation = () => {
  if (!quotationToDelete.value) return

  router.delete(route('quotations.destroy', quotationToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      quotationToDelete.value = null
      success('Cotización eliminada exitosamente')
    },
    onError: (errors) => {
      const errorMessage = Object.values(errors).flat().join(', ') || 'Error al eliminar la cotización'
      error(errorMessage)
    },
    preserveScroll: true,
  })
}

const downloadPdf = (id: number) => {
  window.open(route('quotations.pdf', id), '_blank')
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-CL')
}

const formatNumber = (num: number) => {
  return new Intl.NumberFormat('es-CL').format(num)
}

const getStatusLabel = (status: string) => {
  const labels: Record<string, string> = {
    draft: 'Borrador',
    sent: 'Enviada',
    accepted: 'Aceptada',
    rejected: 'Rechazada',
    expired: 'Expirada',
  }
  return labels[status] || status
}

const getStatusVariant = (status: string) => {
  const variants: Record<string, any> = {
    draft: 'secondary',
    sent: 'default',
    accepted: 'success',
    rejected: 'destructive',
    expired: 'outline',
  }
  return variants[status] || 'default'
}
</script>
