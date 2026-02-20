<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import AppLayout from '@/layouts/AppLayout.vue'
import SettingsLayout from '@/layouts/settings/Layout.vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { Plus, Key, Copy, Check, Trash2, AlertCircle, FileText } from 'lucide-vue-next'
import Swal from 'sweetalert2'

const props = defineProps<{
  tokens: Array<any>
}>()

const page = usePage()
const showTokenModal = ref(false)
const displayToken = ref('')
const copied = ref(false)

const form = useForm({
  name: '',
})

const apiBaseUrl = computed(() => {
  return window.location.origin + '/api'
})

const apiDocsUrl = computed(() => {
  return window.location.origin + '/api/documentation'
})

// Watch para detectar cuando llega el token
watch(() => page.props.flash, (flash: any) => {
  if (flash?.newToken) {
    displayToken.value = flash.newToken
    showTokenModal.value = true
  }
}, { deep: true, immediate: true })

const createToken = () => {
  form.post(route('api-tokens.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
  })
}

const closeModal = () => {
  showTokenModal.value = false
  displayToken.value = ''
}

const copyToken = async () => {
  try {
    await navigator.clipboard.writeText(displayToken.value)
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  } catch (err) {
    console.error('Error al copiar:', err)
  }
}

const deleteToken = (tokenId: number) => {
  Swal.fire({
    title: '¿Eliminar token?',
    text: 'Las aplicaciones que usen este token dejarán de funcionar',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#ef4444',
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(route('api-tokens.destroy', tokenId), {
        preserveScroll: true,
      })
    }
  })
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('es-CL', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(date)
}
</script>

<template>
  <AppLayout title="API Tokens">
    <h1 class="sr-only">API Tokens</h1>
    
    <SettingsLayout>
      <div class="space-y-6">
        <Heading
          title="API Tokens"
          description="Gestiona tus tokens de acceso a la API"
        />

        <!-- Información de la API -->
        <Card>
          <CardHeader>
            <CardTitle>Documentación de la API</CardTitle>
            <CardDescription>
              Accede a la documentación completa de la API en Swagger
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div class="flex items-center gap-4">
              <Button as-child variant="outline">
                <a :href="apiDocsUrl" target="_blank" class="flex items-center gap-2">
                  <FileText class="h-4 w-4" />
                  Ver Documentación Swagger
                </a>
              </Button>
              <div class="text-sm text-muted-foreground">
                <code class="bg-muted px-2 py-1 rounded">{{ apiBaseUrl }}</code>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Crear nuevo token -->
        <Card>
          <CardHeader>
            <CardTitle>Crear Nuevo Token</CardTitle>
            <CardDescription>
              Los tokens te permiten acceder a la API de forma segura
            </CardDescription>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="createToken" class="space-y-4">
              <div>
                <Label for="token_name">Nombre del Token</Label>
                <Input
                  id="token_name"
                  v-model="form.name"
                  placeholder="Mi aplicación"
                  required
                />
                <p class="text-xs text-muted-foreground mt-1">
                  Elige un nombre descriptivo para identificar este token
                </p>
              </div>

              <Button type="submit" :disabled="form.processing">
                <Plus class="h-4 w-4 mr-2" />
                Crear Token
              </Button>
            </form>
          </CardContent>
        </Card>

        <!-- Modal de token creado -->
        <Dialog :open="showTokenModal" @update:open="closeModal">
          <DialogContent class="sm:max-w-2xl">
            <DialogHeader>
              <DialogTitle>Token Creado Exitosamente</DialogTitle>
              <DialogDescription>
                Copia este token ahora. No podrás verlo nuevamente.
              </DialogDescription>
            </DialogHeader>

            <div class="space-y-4 py-4">
              <Alert>
                <AlertCircle class="h-4 w-4" />
                <AlertTitle>Importante</AlertTitle>
                <AlertDescription>
                  Guarda este token en un lugar seguro. Por razones de seguridad, no podrás verlo nuevamente.
                </AlertDescription>
              </Alert>

              <div class="space-y-2">
                <Label for="api-token">Tu Token de API</Label>
                <div class="flex gap-2 items-center">
                  <Input
                    id="api-token"
                    v-model="displayToken"
                    readonly
                    class="font-mono text-sm flex-1"
                  />
                  <Button
                    type="button"
                    variant="outline"
                    size="icon"
                    @click="copyToken"
                    :title="copied ? 'Copiado!' : 'Copiar token'"
                  >
                    <Check v-if="copied" class="h-4 w-4 text-green-600" />
                    <Copy v-else class="h-4 w-4" />
                  </Button>
                </div>
              </div>

              <div class="space-y-2">
                <Label>Ejemplo de uso</Label>
                <div class="bg-muted p-3 rounded-md">
                  <pre class="text-xs overflow-x-auto"><code>curl -X GET {{ apiBaseUrl }}/leads \
  -H "Authorization: Bearer {{ displayToken }}" \
  -H "Accept: application/json"</code></pre>
                </div>
              </div>
            </div>

            <DialogFooter>
              <Button type="button" @click="closeModal">Entendido</Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>

        <!-- Lista de tokens -->
        <Card>
          <CardHeader>
            <CardTitle>Tokens Activos</CardTitle>
            <CardDescription>
              Estos son tus tokens de API activos
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div v-if="tokens.length === 0" class="text-center py-8 text-muted-foreground">
              <Key class="h-12 w-12 mx-auto mb-4 opacity-50" />
              <p>No tienes tokens activos</p>
              <p class="text-sm">Crea uno para comenzar a usar la API</p>
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="token in tokens"
                :key="token.id"
                class="flex items-center justify-between p-4 border rounded-lg"
              >
                <div class="flex-1">
                  <div class="flex items-center gap-2">
                    <Key class="h-4 w-4 text-muted-foreground" />
                    <span class="font-medium">{{ token.name }}</span>
                  </div>
                  <div class="flex items-center gap-4 mt-1 text-sm text-muted-foreground">
                    <span>Creado: {{ formatDate(token.created_at) }}</span>
                    <span v-if="token.last_used_at">
                      Último uso: {{ formatDate(token.last_used_at) }}
                    </span>
                    <span v-else class="text-yellow-600">Nunca usado</span>
                  </div>
                </div>

                <Button
                  variant="destructive"
                  size="sm"
                  @click="deleteToken(token.id)"
                >
                  <Trash2 class="h-4 w-4" />
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
