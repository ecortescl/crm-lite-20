<template>
  <AppLayout title="Empresas">
    <div class="px-4 py-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <Heading
          title="Empresas"
          description="Gestiona las empresas y sus datos"
        />
        <Button @click="openCreateDialog">
          <Plus class="w-4 h-4 mr-2" />
          Crear Empresa
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
                  placeholder="Buscar por razón social, RUT, email..."
                  class="pl-9"
                  @input="debouncedSearch"
                />
              </div>
            </div>

            <!-- Filtro por Región -->
            <Select v-model="filterRegion" @update:model-value="applyFilters">
              <SelectTrigger>
                <SelectValue placeholder="Todas las regiones" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todas las regiones</SelectItem>
                <SelectItem value="Región Metropolitana">Región Metropolitana</SelectItem>
                <SelectItem value="Región de Valparaíso">Región de Valparaíso</SelectItem>
                <SelectItem value="Región del Biobío">Región del Biobío</SelectItem>
              </SelectContent>
            </Select>

            <!-- Filtro por Tamaño -->
            <Select v-model="filterSize" @update:model-value="applyFilters">
              <SelectTrigger>
                <SelectValue placeholder="Todos los tamaños" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos los tamaños</SelectItem>
                <SelectItem value="micro">Microempresa</SelectItem>
                <SelectItem value="small">Pequeña</SelectItem>
                <SelectItem value="medium">Mediana</SelectItem>
                <SelectItem value="large">Grande</SelectItem>
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
              <TableHead>Razón Social</TableHead>
              <TableHead>RUT</TableHead>
              <TableHead>Nombre Fantasía</TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Teléfono</TableHead>
              <TableHead>Leads</TableHead>
              <TableHead class="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-if="companies.data.length === 0">
              <TableCell colspan="7" class="text-center py-8 text-muted-foreground">
                No se encontraron empresas
              </TableCell>
            </TableRow>
            <TableRow v-for="company in companies.data" :key="company.id">
              <TableCell class="font-medium">{{ company.business_name }}</TableCell>
              <TableCell>{{ company.formatted_rut }}</TableCell>
              <TableCell>{{ company.fantasy_name || '-' }}</TableCell>
              <TableCell>{{ company.email || '-' }}</TableCell>
              <TableCell>{{ company.phone || '-' }}</TableCell>
              <TableCell>
                <Badge variant="secondary">{{ company.leads_count }} leads</Badge>
              </TableCell>
              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm">
                      <MoreVertical class="w-4 h-4" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="openEditDialog(company)">
                      <Pencil class="w-4 h-4 mr-2" />
                      Editar
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="confirmDelete(company)" class="text-destructive">
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
        <div v-if="companies.data.length > 0" class="p-4 border-t">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              Mostrando {{ companies.from }} a {{ companies.to }} de {{ companies.total }} resultados
            </div>
            <div class="flex gap-2">
              <Button
                variant="outline"
                size="sm"
                :disabled="!companies.prev_page_url"
                @click="goToPage(companies.current_page - 1)"
              >
                <ChevronLeft class="w-4 h-4" />
                Anterior
              </Button>
              <Button
                variant="outline"
                size="sm"
                :disabled="!companies.next_page_url"
                @click="goToPage(companies.current_page + 1)"
              >
                Siguiente
                <ChevronRight class="w-4 h-4" />
              </Button>
            </div>
          </div>
        </div>
      </Card>

      <!-- Alert Dialog para confirmar eliminación -->
      <AlertDialog :open="deleteDialogOpen" @update:open="deleteDialogOpen = $event">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>¿Estás seguro?</AlertDialogTitle>
            <AlertDialogDescription>
              Esta acción no se puede deshacer. Se eliminará permanentemente la empresa
              <strong>{{ companyToDelete?.business_name }}</strong>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="deleteCompany" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Eliminar
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      <!-- Dialog Crear/Editar -->
      <Dialog :open="dialogOpen" @update:open="dialogOpen = $event">
        <DialogContent class="max-w-3xl max-h-[85vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{{ editingCompany ? 'Editar Empresa' : 'Crear Nueva Empresa' }}</DialogTitle>
            <DialogDescription>
              Completa la información de la empresa.
            </DialogDescription>
          </DialogHeader>

          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Información Legal -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Información Legal</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <Label for="business_name">Razón Social *</Label>
                  <Input id="business_name" v-model="form.business_name" required class="mt-2" />
                </div>
                <div>
                  <Label for="rut">RUT *</Label>
                  <Input 
                    id="rut" 
                    v-model="form.rut" 
                    required 
                    placeholder="76.123.456-7"
                    class="mt-2"
                  />
                </div>
                <div>
                  <Label for="fantasy_name">Nombre de Fantasía</Label>
                  <Input id="fantasy_name" v-model="form.fantasy_name" class="mt-2" />
                </div>
                <div>
                  <Label for="giro">Giro</Label>
                  <Input id="giro" v-model="form.giro" placeholder="Actividad económica" class="mt-2" />
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Contacto -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Información de Contacto</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <Label for="email">Email</Label>
                  <Input id="email" v-model="form.email" type="email" class="mt-2" />
                </div>
                <div>
                  <Label for="phone">Teléfono</Label>
                  <Input id="phone" v-model="form.phone" placeholder="+56 9 1234 5678" class="mt-2" />
                </div>
                <div class="col-span-2">
                  <Label for="website">Sitio Web</Label>
                  <Input id="website" v-model="form.website" type="url" placeholder="https://ejemplo.cl" class="mt-2" />
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Dirección -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Dirección</h3>
              <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                  <Label for="address">Dirección</Label>
                  <Input id="address" v-model="form.address" class="mt-2" />
                </div>
                <div>
                  <Label for="commune">Comuna</Label>
                  <Input id="commune" v-model="form.commune" class="mt-2" />
                </div>
                <div>
                  <Label for="city">Ciudad</Label>
                  <Input id="city" v-model="form.city" class="mt-2" />
                </div>
                <div class="col-span-2">
                  <Label for="region">Región</Label>
                  <Select v-model="form.region">
                    <SelectTrigger class="mt-2">
                      <SelectValue placeholder="Selecciona una región" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="unspecified">Sin especificar</SelectItem>
                      <SelectItem value="Región de Arica y Parinacota">Región de Arica y Parinacota</SelectItem>
                      <SelectItem value="Región de Tarapacá">Región de Tarapacá</SelectItem>
                      <SelectItem value="Región de Antofagasta">Región de Antofagasta</SelectItem>
                      <SelectItem value="Región de Atacama">Región de Atacama</SelectItem>
                      <SelectItem value="Región de Coquimbo">Región de Coquimbo</SelectItem>
                      <SelectItem value="Región de Valparaíso">Región de Valparaíso</SelectItem>
                      <SelectItem value="Región Metropolitana">Región Metropolitana</SelectItem>
                      <SelectItem value="Región del Libertador General Bernardo O'Higgins">Región del Libertador General Bernardo O'Higgins</SelectItem>
                      <SelectItem value="Región del Maule">Región del Maule</SelectItem>
                      <SelectItem value="Región de Ñuble">Región de Ñuble</SelectItem>
                      <SelectItem value="Región del Biobío">Región del Biobío</SelectItem>
                      <SelectItem value="Región de La Araucanía">Región de La Araucanía</SelectItem>
                      <SelectItem value="Región de Los Ríos">Región de Los Ríos</SelectItem>
                      <SelectItem value="Región de Los Lagos">Región de Los Lagos</SelectItem>
                      <SelectItem value="Región de Aysén">Región de Aysén</SelectItem>
                      <SelectItem value="Región de Magallanes">Región de Magallanes</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Información Adicional -->
            <div class="space-y-4">
              <h3 class="text-sm font-semibold">Información Adicional</h3>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <Label for="size">Tamaño de Empresa</Label>
                  <Select v-model="form.size">
                    <SelectTrigger class="mt-2">
                      <SelectValue placeholder="Selecciona el tamaño" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="unspecified">Sin especificar</SelectItem>
                      <SelectItem value="micro">Microempresa (1-9 trabajadores)</SelectItem>
                      <SelectItem value="small">Pequeña (10-49 trabajadores)</SelectItem>
                      <SelectItem value="medium">Mediana (50-199 trabajadores)</SelectItem>
                      <SelectItem value="large">Grande (200+ trabajadores)</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label for="industry">Industria/Sector</Label>
                  <Input id="industry" v-model="form.industry" placeholder="ej: Tecnología, Retail" class="mt-2" />
                </div>
                <div class="col-span-2">
                  <Label for="notes">Notas</Label>
                  <Textarea id="notes" v-model="form.notes" rows="3" class="mt-2" />
                </div>
              </div>
            </div>

            <DialogFooter>
              <Button type="button" variant="outline" @click="dialogOpen = false">
                Cancelar
              </Button>
              <Button type="submit" :disabled="processing">
                {{ editingCompany ? 'Actualizar' : 'Crear' }}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive, watch, computed } from 'vue'
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
  companies: any
  filters: any
}>()

const { success, error } = useToast()

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const editingCompany = ref<any>(null)
const companyToDelete = ref<any>(null)
const processing = ref(false)

// Filtros
const searchQuery = ref(props.filters?.search || '')
const filterRegion = ref(props.filters?.region || 'all')
const filterSize = ref(props.filters?.size || 'all')

const hasActiveFilters = computed(() => {
  return searchQuery.value || filterRegion.value !== 'all' || filterSize.value !== 'all'
})

const form = reactive({
  business_name: '',
  rut: '',
  fantasy_name: '',
  giro: '',
  email: '',
  phone: '',
  website: '',
  address: '',
  commune: '',
  city: '',
  region: 'unspecified',
  notes: '',
  size: 'unspecified',
  industry: '',
})

let searchTimeout: ReturnType<typeof setTimeout>

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

const applyFilters = () => {
  router.get(route('companies.index'), {
    search: searchQuery.value || undefined,
    region: filterRegion.value !== 'all' ? filterRegion.value : undefined,
    size: filterSize.value !== 'all' ? filterSize.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const clearFilters = () => {
  searchQuery.value = ''
  filterRegion.value = 'all'
  filterSize.value = 'all'
  router.get(route('companies.index'))
}

const goToPage = (page: number) => {
  router.get(route('companies.index'), {
    page,
    search: searchQuery.value || undefined,
    region: filterRegion.value !== 'all' ? filterRegion.value : undefined,
    size: filterSize.value !== 'all' ? filterSize.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const openCreateDialog = () => {
  editingCompany.value = null
  resetForm()
  dialogOpen.value = true
}

const openEditDialog = (company: any) => {
  editingCompany.value = company
  form.business_name = company.business_name
  form.rut = company.formatted_rut
  form.fantasy_name = company.fantasy_name || ''
  form.giro = company.giro || ''
  form.email = company.email || ''
  form.phone = company.phone || ''
  form.website = company.website || ''
  form.address = company.address || ''
  form.commune = company.commune || ''
  form.city = company.city || ''
  form.region = company.region || 'unspecified'
  form.notes = company.notes || ''
  form.size = company.size || 'unspecified'
  form.industry = company.industry || ''
  dialogOpen.value = true
}

const resetForm = () => {
  form.business_name = ''
  form.rut = ''
  form.fantasy_name = ''
  form.giro = ''
  form.email = ''
  form.phone = ''
  form.website = ''
  form.address = ''
  form.commune = ''
  form.city = ''
  form.region = 'unspecified'
  form.notes = ''
  form.size = 'unspecified'
  form.industry = ''
}

const submitForm = () => {
  processing.value = true
  
  const data: any = {
    business_name: form.business_name,
    rut: form.rut,
    fantasy_name: form.fantasy_name || null,
    giro: form.giro || null,
    email: form.email || null,
    phone: form.phone || null,
    website: form.website || null,
    address: form.address || null,
    commune: form.commune || null,
    city: form.city || null,
    region: (form.region && form.region !== 'unspecified') ? form.region : null,
    notes: form.notes || null,
    size: (form.size && form.size !== 'unspecified') ? form.size : null,
    industry: form.industry || null,
  }

  if (editingCompany.value) {
    router.put(route('companies.update', editingCompany.value.id), data, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Empresa actualizada exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al actualizar la empresa')
      },
      preserveScroll: true,
    })
  } else {
    router.post(route('companies.store'), data, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Empresa creada exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al crear la empresa')
      },
      preserveScroll: true,
    })
  }
}

const confirmDelete = (company: any) => {
  companyToDelete.value = company
  deleteDialogOpen.value = true
}

const deleteCompany = () => {
  if (!companyToDelete.value) return

  router.delete(route('companies.destroy', companyToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      companyToDelete.value = null
      success('Empresa eliminada exitosamente')
    },
    onError: () => {
      error('Error al eliminar la empresa')
    },
    preserveScroll: true,
  })
}

watch(dialogOpen, (newValue) => {
  if (!newValue) {
    resetForm()
    editingCompany.value = null
  }
})
</script>
