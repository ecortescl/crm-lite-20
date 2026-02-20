<template>
  <AppLayout title="Roles">
    <div class="px-4 py-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <Heading
          title="Roles"
          description="Gestiona los roles y sus permisos"
        />
        <Button @click="openCreateDialog">
          <Plus class="w-4 h-4 mr-2" />
          Crear Rol
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
              <TableHead>Usuarios</TableHead>
              <TableHead>Permisos</TableHead>
              <TableHead class="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-if="roles.data.length === 0">
              <TableCell colspan="5" class="text-center py-8 text-muted-foreground">
                No se encontraron roles
              </TableCell>
            </TableRow>
            <TableRow v-for="role in roles.data" :key="role.id">
              <TableCell class="font-medium">{{ role.name }}</TableCell>
              <TableCell>{{ role.description || '-' }}</TableCell>
              <TableCell>
                <Badge variant="secondary">{{ role.users_count }} usuarios</Badge>
              </TableCell>
              <TableCell>
                <div class="flex flex-wrap gap-1">
                  <Badge v-for="permission in role.permissions.slice(0, 3)" :key="permission.id" variant="outline">
                    {{ permission.name }}
                  </Badge>
                  <Badge v-if="role.permissions.length > 3" variant="outline">
                    +{{ role.permissions.length - 3 }} más
                  </Badge>
                  <span v-if="role.permissions.length === 0" class="text-muted-foreground text-sm">Sin permisos</span>
                </div>
              </TableCell>
              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm">
                      <MoreVertical class="w-4 h-4" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end">
                    <DropdownMenuItem @click="openEditDialog(role)">
                      <Pencil class="w-4 h-4 mr-2" />
                      Editar
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="confirmDelete(role)" class="text-destructive">
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
        <div v-if="roles.data.length > 0" class="p-4 border-t">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              Mostrando {{ roles.from }} a {{ roles.to }} de {{ roles.total }} resultados
            </div>
            <div class="flex gap-2">
              <Button
                variant="outline"
                size="sm"
                :disabled="!roles.prev_page_url"
                @click="goToPage(roles.current_page - 1)"
              >
                <ChevronLeft class="w-4 h-4" />
                Anterior
              </Button>
              <Button
                variant="outline"
                size="sm"
                :disabled="!roles.next_page_url"
                @click="goToPage(roles.current_page + 1)"
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
        <DialogContent class="max-w-3xl max-h-[85vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{{ editingRole ? 'Editar Rol' : 'Crear Rol' }}</DialogTitle>
            <DialogDescription>
              Completa la información del rol y asigna permisos.
            </DialogDescription>
          </DialogHeader>
          <form @submit.prevent="submitForm" class="space-y-4">
            <div>
              <Label for="name">Nombre *</Label>
              <Input id="name" v-model="form.name" required class="mt-2" />
            </div>
            <div>
              <Label for="description">Descripción</Label>
              <Input id="description" v-model="form.description" class="mt-2" />
            </div>
            <div>
              <Label>Permisos</Label>
              <div class="grid grid-cols-2 gap-3 mt-2 p-4 border rounded-md max-h-60 overflow-y-auto">
                <div v-for="permission in permissions" :key="permission.id" class="flex items-center space-x-2">
                  <Checkbox
                    :id="`permission-${permission.id}`"
                    :checked="form.permissions.includes(permission.id)"
                    @update:checked="togglePermission(permission.id)"
                  />
                  <Label :for="`permission-${permission.id}`" class="font-normal text-sm">
                    {{ permission.description || permission.name }}
                  </Label>
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" @click="dialogOpen = false">Cancelar</Button>
              <Button type="submit" :disabled="processing">{{ editingRole ? 'Actualizar' : 'Crear' }}</Button>
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
              Esta acción no se puede deshacer. Se eliminará permanentemente el rol
              <strong>{{ roleToDelete?.name }}</strong>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="deleteRole" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
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
import { Checkbox } from '@/components/ui/checkbox'
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
  roles: any
  permissions: Array<any>
  filters: any
}>()

const { success, error } = useToast()

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const editingRole = ref<any>(null)
const roleToDelete = ref<any>(null)
const processing = ref(false)

// Búsqueda
const searchQuery = ref(props.filters?.search || '')

const form = reactive({
  name: '',
  description: '',
  permissions: [] as number[],
})

let searchTimeout: ReturnType<typeof setTimeout>

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get(route('roles.index'), {
      search: searchQuery.value || undefined,
    }, {
      preserveState: true,
      preserveScroll: true,
    })
  }, 500)
}

const goToPage = (page: number) => {
  router.get(route('roles.index'), {
    page,
    search: searchQuery.value || undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const openCreateDialog = () => {
  editingRole.value = null
  resetForm()
  dialogOpen.value = true
}

const openEditDialog = (role: any) => {
  editingRole.value = role
  form.name = role.name
  form.description = role.description || ''
  form.permissions = role.permissions.map((p: any) => p.id)
  dialogOpen.value = true
}

const resetForm = () => {
  form.name = ''
  form.description = ''
  form.permissions = []
}

const togglePermission = (permissionId: number) => {
  const index = form.permissions.indexOf(permissionId)
  if (index > -1) {
    form.permissions.splice(index, 1)
  } else {
    form.permissions.push(permissionId)
  }
}

const submitForm = () => {
  processing.value = true

  if (editingRole.value) {
    router.put(route('roles.update', editingRole.value.id), form, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Rol actualizado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al actualizar el rol')
      },
      preserveScroll: true,
    })
  } else {
    router.post(route('roles.store'), form, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Rol creado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al crear el rol')
      },
      preserveScroll: true,
    })
  }
}

const confirmDelete = (role: any) => {
  roleToDelete.value = role
  deleteDialogOpen.value = true
}

const deleteRole = () => {
  if (!roleToDelete.value) return

  router.delete(route('roles.destroy', roleToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      roleToDelete.value = null
      success('Rol eliminado exitosamente')
    },
    onError: () => {
      error('Error al eliminar el rol')
    },
    preserveScroll: true,
  })
}

watch(dialogOpen, (newValue) => {
  if (!newValue) {
    resetForm()
    editingRole.value = null
  }
})
</script>
