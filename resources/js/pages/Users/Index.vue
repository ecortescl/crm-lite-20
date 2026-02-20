<template>
  <AppLayout title="Usuarios">
    <div class="px-4 py-6 space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <Heading
          title="Usuarios"
          description="Gestiona los usuarios del sistema"
        />
        <Button @click="openCreateDialog">
          <Plus class="w-4 h-4 mr-2" />
          Crear Usuario
        </Button>
      </div>

      <!-- Filtros y Búsqueda -->
      <Card>
        <div class="p-4 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Búsqueda -->
            <div class="md:col-span-2">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  v-model="searchQuery"
                  placeholder="Buscar por nombre o email..."
                  class="pl-9"
                  @input="debouncedSearch"
                />
              </div>
            </div>

            <!-- Filtro por Rol -->
            <Select v-model="filterRole" @update:model-value="applyFilters">
              <SelectTrigger>
                <SelectValue placeholder="Todos los roles" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos los roles</SelectItem>
                <SelectItem v-for="role in roles" :key="role.id" :value="role.id.toString()">
                  {{ role.name }}
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
              <TableHead>Roles</TableHead>
              <TableHead class="text-right">Acciones</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-if="users.data.length === 0">
              <TableCell colspan="4" class="text-center py-8 text-muted-foreground">
                No se encontraron usuarios
              </TableCell>
            </TableRow>
            <TableRow v-for="user in users.data" :key="user.id">
              <TableCell class="font-medium">{{ user.name }}</TableCell>
              <TableCell>{{ user.email }}</TableCell>
              <TableCell>
                <div class="flex gap-1 flex-wrap">
                  <Badge v-for="role in user.roles" :key="role.id" variant="secondary">
                    {{ role.name }}
                  </Badge>
                  <span v-if="user.roles.length === 0" class="text-muted-foreground text-sm">Sin roles</span>
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
                    <DropdownMenuItem @click="openEditDialog(user)">
                      <Pencil class="w-4 h-4 mr-2" />
                      Editar
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="confirmDelete(user)" class="text-destructive">
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
        <div v-if="users.data.length > 0" class="p-4 border-t">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              Mostrando {{ users.from }} a {{ users.to }} de {{ users.total }} resultados
            </div>
            <div class="flex gap-2">
              <Button
                variant="outline"
                size="sm"
                :disabled="!users.prev_page_url"
                @click="goToPage(users.current_page - 1)"
              >
                <ChevronLeft class="w-4 h-4" />
                Anterior
              </Button>
              <Button
                variant="outline"
                size="sm"
                :disabled="!users.next_page_url"
                @click="goToPage(users.current_page + 1)"
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
        <DialogContent class="max-w-2xl">
          <DialogHeader>
            <DialogTitle>{{ editingUser ? 'Editar Usuario' : 'Crear Usuario' }}</DialogTitle>
            <DialogDescription>
              Completa la información del usuario.
            </DialogDescription>
          </DialogHeader>
          <form @submit.prevent="submitForm" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <Label for="name">Nombre *</Label>
                <Input id="name" v-model="form.name" required class="mt-2" />
              </div>
              <div>
                <Label for="email">Email *</Label>
                <Input id="email" v-model="form.email" type="email" required class="mt-2" />
              </div>
            </div>
            <div>
              <Label for="password">{{ editingUser ? 'Nueva Contraseña (opcional)' : 'Contraseña *' }}</Label>
              <Input id="password" v-model="form.password" type="password" :required="!editingUser" class="mt-2" />
            </div>
            <div>
              <Label>Roles</Label>
              <div class="grid grid-cols-2 gap-2 mt-2">
                <div v-for="role in roles" :key="role.id" class="flex items-center space-x-2">
                  <Checkbox
                    :id="`role-${role.id}`"
                    :checked="form.roles.includes(role.id)"
                    @update:checked="toggleRole(role.id)"
                  />
                  <Label :for="`role-${role.id}`" class="font-normal">
                    {{ role.name }}
                  </Label>
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" @click="dialogOpen = false">Cancelar</Button>
              <Button type="submit" :disabled="processing">{{ editingUser ? 'Actualizar' : 'Crear' }}</Button>
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
              Esta acción no se puede deshacer. Se eliminará permanentemente el usuario
              <strong>{{ userToDelete?.name }}</strong>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancelar</AlertDialogCancel>
            <AlertDialogAction @click="deleteUser" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">
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
import { Plus, Search, X, MoreVertical, Pencil, Trash2, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps<{
  users: any
  roles: Array<any>
  filters: any
}>()

const { success, error } = useToast()

const dialogOpen = ref(false)
const deleteDialogOpen = ref(false)
const editingUser = ref<any>(null)
const userToDelete = ref<any>(null)
const processing = ref(false)

// Filtros
const searchQuery = ref(props.filters?.search || '')
const filterRole = ref(props.filters?.role || 'all')

const hasActiveFilters = computed(() => {
  return searchQuery.value || filterRole.value !== 'all'
})

const form = reactive({
  name: '',
  email: '',
  password: '',
  roles: [] as number[],
})

let searchTimeout: ReturnType<typeof setTimeout>

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

const applyFilters = () => {
  router.get(route('users.index'), {
    search: searchQuery.value || undefined,
    role: filterRole.value !== 'all' ? filterRole.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const clearFilters = () => {
  searchQuery.value = ''
  filterRole.value = 'all'
  router.get(route('users.index'))
}

const goToPage = (page: number) => {
  router.get(route('users.index'), {
    page,
    search: searchQuery.value || undefined,
    role: filterRole.value !== 'all' ? filterRole.value : undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const openCreateDialog = () => {
  editingUser.value = null
  resetForm()
  dialogOpen.value = true
}

const openEditDialog = (user: any) => {
  editingUser.value = user
  form.name = user.name
  form.email = user.email
  form.password = ''
  form.roles = user.roles.map((r: any) => r.id)
  dialogOpen.value = true
}

const resetForm = () => {
  form.name = ''
  form.email = ''
  form.password = ''
  form.roles = []
}

const toggleRole = (roleId: number) => {
  const index = form.roles.indexOf(roleId)
  if (index > -1) {
    form.roles.splice(index, 1)
  } else {
    form.roles.push(roleId)
  }
}

const submitForm = () => {
  processing.value = true
  const data: any = { ...form }
  if (editingUser.value && !data.password) {
    data.password = undefined
  }

  if (editingUser.value) {
    router.put(route('users.update', editingUser.value.id), data, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Usuario actualizado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al actualizar el usuario')
      },
      preserveScroll: true,
    })
  } else {
    router.post(route('users.store'), data, {
      onSuccess: () => {
        dialogOpen.value = false
        processing.value = false
        success('Usuario creado exitosamente')
      },
      onError: () => {
        processing.value = false
        error('Error al crear el usuario')
      },
      preserveScroll: true,
    })
  }
}

const confirmDelete = (user: any) => {
  userToDelete.value = user
  deleteDialogOpen.value = true
}

const deleteUser = () => {
  if (!userToDelete.value) return

  router.delete(route('users.destroy', userToDelete.value.id), {
    onSuccess: () => {
      deleteDialogOpen.value = false
      userToDelete.value = null
      success('Usuario eliminado exitosamente')
    },
    onError: () => {
      error('Error al eliminar el usuario')
    },
    preserveScroll: true,
  })
}

watch(dialogOpen, (newValue) => {
  if (!newValue) {
    resetForm()
    editingUser.value = null
  }
})
</script>
