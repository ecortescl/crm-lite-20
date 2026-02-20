<template>
  <AppLayout title="Permisos">
    <div class="px-4 py-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <Heading
          title="Permisos"
          description="Gestiona los permisos del sistema"
        />
        <Button @click="openCreateDialog">
          <Plus class="w-4 h-4 mr-2" />
          Crear Permiso
        </Button>
      </div>

      <!-- Búsqueda -->
      <Card>
        <div class="p-4">
          <div class="relative max-w-md">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
            <Input
              v-model="searchQuery"
              placeholder="Buscar por nombre o descripción..."
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
              <TableHead>Nombre</TableHead>
              <TableHead>Descripción</TableHead>
              <TableHead class="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-if="permissions.data.length === 0">
              <TableCell colspan="3" class="text-center py-8 text-muted-foreground">
                No se encontraron permisos
              </TableCell>
            </TableRow>
            <TableRow v-for="permission in permissions.data" :key="permission.id">
              <TableCell class="font-medium">{{ permission.name }}</TableCell>
              <TableCell>{{ permission.description || '-' }}</TableCell>
              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm">
                      <MoreVertical class="w-4 h-4" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="openEditDialog(permission)">
                      <Pencil class="w-4 h-4 mr-2" />
                      Editar
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="confirmDelete(permission)" class="text-destructive">
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
        <div v-if="permissions.data.length > 0" class="p-4 border-t">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              Mostrando {{ permissions.from }} a {{ permissions.to }} de {{ permissions.total }} resultados
            </div>
            <div class="flex gap-2">
              <Button
                variant="outline"
                size="sm"
                :disabled="!permissions.prev_page_url"
                @click="goToPage(permissions.current_page - 1)"
              >
                <ChevronLeft class="w-4 h-4" />
                Anterior
              </Button>
              <Button
                variant="outline"
                size="sm"
                :disabled="!permissions.next_page_url"
                @click="goToPage(permissions.current_page + 1)"
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
        <DialogContent class="max-w-lg">
          <DialogHeader>
            <DialogTitle>{{ editingPermission ? 'Editar Permiso' : 'Crear Permiso' }}</DialogTitle>
            <DialogDescription>
              Completa la información del permiso.
            </DialogDescription>
          </DialogHeader>
          <form @submit.prevent="submitForm" class="space-y-4">
            <div>
              <Label for="name">Nombre *</Label>
              <Input id="name" v-model="form.name" required placeholder="ej: view_leads" class="mt-2" />
            </div>
            <div>
              <Label for="description">Descripción</Label>
              <Input id="description" v-model="form.description" placeholder="ej: Ver leads" class="mt-2" />
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" @click="dialogOpen = false">Cancelar</Button>
              <Button type="submit" :disabled="processing">{{ editingPermission ? 'Actualizar' : 'Crear' }}</Button>
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
              Esta acción no se puede deshacer. Se eliminará permanentemente el permiso
              <strong>{{ permissionToDelete?.name }}</strong>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="deletePermission" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
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
import { Plus, Search, MoreVertical, Pencil, Trash2, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps<{
  permissions: any
  filters: any
}>()

const { success, error } = useToast()

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const editingPermission = ref<any>(null)
const permissionToDelete = ref<any>(null)
const processing = ref(false)

// Búsqueda
const searchQuery = ref(props.filters?.search || '')

const form = reactive({
  name: '',
  description: '',
})

let searchTimeout: ReturnType<typeof setTimeout>

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get(route('permissions.index'), {
      search: searchQuery.value || undefined,
    }, {
      preserveState: true,
      preserveScroll: true,
    })
  }, 500)
}

const goToPage = (page: number) => {
  router.get(route('permissions.index'), {
    page,
    search: searchQuery.value || undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const openCreateDialog = () => {
  editingPermission.value = null
  resetForm()
  dialogOpen.value = true
}

const openEditDialog = (permission: any) => {
  editingPermission.value = permission
  form.name = permission.name
  form.description = permission.description || ''
  dialogOpen.value = true
}

const resetForm = () => {
  form.name = ''
  form.description = ''
}

const submitForm = () => {
  processing.value = true

  if (editingPermission.value) {
    router.put(route('permissions.update', editingPermission.value.id), form, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Permiso actualizado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al actualizar el permiso')
      },
      preserveScroll: true,
    })
  } else {
    router.post(route('permissions.store'), form, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Permiso creado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al crear el permiso')
      },
      preserveScroll: true,
    })
  }
}

const confirmDelete = (permission: any) => {
  permissionToDelete.value = permission
  deleteDialogOpen.value = true
}

const deletePermission = () => {
  if (!permissionToDelete.value) return

  router.delete(route('permissions.destroy', permissionToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      permissionToDelete.value = null
      success('Permiso eliminado exitosamente')
    },
    onError: () => {
      error('Error al eliminar el permiso')
    },
    preserveScroll: true,
  })
}

watch(dialogOpen, (newValue) => {
  if (!newValue) {
    resetForm()
    editingPermission.value = null
  }
})
</script>
