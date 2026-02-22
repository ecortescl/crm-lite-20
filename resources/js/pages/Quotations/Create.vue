
<template>
  <AppLayout title="Nueva Cotización">
    <div class="px-4 py-6 space-y-6">
      <div class="flex items-center justify-between">
        <Heading
          title="Nueva Cotización"
          description="Crea una nueva cotización para un cliente"
        />
        <Button variant="outline" @click="router.visit(route('quotations.index'))">
          <ArrowLeft class="w-4 h-4 mr-2" />
          Volver
        </Button>
      </div>

      <form @submit.prevent="submitForm" class="space-y-6">
        <Card>
          <div class="p-6 space-y-6">
            <!-- Información Básica -->
            <div class="space-y-4">
              <h3 class="text-lg font-semibold">Información Básica</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                  <Label for="quotation_number">Número de Cotización *</Label>
                  <Input id="quotation_number" v-model="form.quotation_number" required class="mt-2" readonly />
                </div>
                <div>
                  <Label for="issue_date">Fecha de Emisión *</Label>
                  <Input id="issue_date" v-model="form.issue_date" type="date" required class="mt-2" />
                </div>
                <div>
                  <Label for="valid_until">Válida Hasta *</Label>
                  <Input id="valid_until" v-model="form.valid_until" type="date" required class="mt-2" />
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                  <Label for="tax_rate">Impuesto (%) *</Label>
                  <Input id="tax_rate" v-model.number="form.tax_rate" type="number" step="0.01" required class="mt-2" />
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Cliente -->
            <div class="space-y-4">
              <h3 class="text-lg font-semibold">Información del Cliente</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <Label for="lead_id">Lead Asociado</Label>
                  <Select v-model="form.lead_id" @update:model-value="onLeadChange">
                    <SelectTrigger class="mt-2">
                      <SelectValue placeholder="Selecciona un lead" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem :value="null">Sin lead</SelectItem>
                      <SelectItem v-for="lead in leads" :key="lead.id" :value="lead.id">
                        {{ lead.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label for="company_id">Empresa Asociada</Label>
                  <Select v-model="form.company_id" @update:model-value="onCompanyChange">
                    <SelectTrigger class="mt-2">
                      <SelectValue placeholder="Selecciona una empresa" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem :value="null">Sin empresa</SelectItem>
                      <SelectItem v-for="company in companies" :key="company.id" :value="company.id">
                        {{ company.display_name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2">
                  <Label for="client_name">Nombre del Cliente *</Label>
                  <Input id="client_name" v-model="form.client_name" required class="mt-2" />
                </div>
                <div>
                  <Label for="client_rut">RUT</Label>
                  <Input id="client_rut" v-model="form.client_rut" class="mt-2" placeholder="76.123.456-7" />
                </div>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                  <Label for="client_email">Email</Label>
                  <Input id="client_email" v-model="form.client_email" type="email" class="mt-2" />
                </div>
                <div>
                  <Label for="client_phone">Teléfono</Label>
                  <Input id="client_phone" v-model="form.client_phone" class="mt-2" />
                </div>
                <div>
                  <Label for="client_address">Dirección</Label>
                  <Input id="client_address" v-model="form.client_address" class="mt-2" />
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Items -->
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Servicios / Productos</h3>
                <Button type="button" size="sm" @click="addItem">
                  <Plus class="w-4 h-4 mr-2" />
                  Agregar Item
                </Button>
              </div>

              <div v-for="(item, index) in form.items" :key="index" class="p-4 bg-muted/30 rounded-lg space-y-4">
                <div class="flex items-start gap-4">
                  <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                      <Label>Descripción *</Label>
                      <Textarea v-model="item.description" rows="2" required class="mt-2" placeholder="Describe el servicio o producto..." />
                    </div>
                    <div>
                      <Label>Cantidad *</Label>
                      <Input v-model.number="item.quantity" type="number" step="0.01" required class="mt-2" placeholder="1" @input="calculateItemSubtotal(index)" />
                    </div>
                    <div>
                      <Label>Precio Unitario *</Label>
                      <Input v-model.number="item.unit_price" type="number" step="0.01" required class="mt-2" placeholder="0" @input="calculateItemSubtotal(index)" />
                    </div>
                  </div>
                  <Button type="button" variant="ghost" size="icon" @click="removeItem(index)" class="shrink-0 mt-8">
                    <Trash2 class="w-4 h-4 text-destructive" />
                  </Button>
                </div>
                <div class="text-right text-sm font-medium">
                  Subtotal: ${{ formatNumber(item.subtotal) }}
                </div>
              </div>

              <div v-if="form.items.length === 0" class="flex flex-col items-center justify-center py-12 text-muted-foreground border-2 border-dashed rounded-lg">
                <Plus class="w-12 h-12 mb-3 opacity-50" />
                <p class="text-sm">No hay items agregados</p>
                <p class="text-xs">Haz clic en "Agregar Item" para comenzar</p>
              </div>
            </div>

            <div class="border-t" />

            <!-- Totales -->
            <div class="flex justify-end">
              <div class="w-full md:w-96 space-y-3 p-4 bg-muted/30 rounded-lg">
                <div class="flex justify-between text-sm">
                  <span class="text-muted-foreground">Subtotal:</span>
                  <span class="font-medium">${{ formatNumber(totals.subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-muted-foreground">Impuesto ({{ form.tax_rate }}%):</span>
                  <span class="font-medium">${{ formatNumber(totals.tax) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-3">
                  <span>Total:</span>
                  <span class="text-primary">${{ formatNumber(totals.total) }}</span>
                </div>
              </div>
            </div>

            <div class="border-t" />

            <!-- Notas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <Label for="notes">Notas</Label>
                <Textarea id="notes" v-model="form.notes" rows="4" class="mt-2" placeholder="Información adicional para el cliente..." />
              </div>
              <div>
                <Label for="terms">Términos y Condiciones</Label>
                <Textarea id="terms" v-model="form.terms" rows="4" class="mt-2" placeholder="Condiciones de pago, garantías, etc..." />
              </div>
            </div>
          </div>
        </Card>

        <div class="flex justify-end gap-4">
          <Button type="button" variant="outline" @click="router.visit(route('quotations.index'))">
            Cancelar
          </Button>
          <Button type="submit" :disabled="processing || form.items.length === 0">
            <FileText class="w-4 h-4 mr-2" />
            Crear Cotización
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Plus, Trash2, ArrowLeft, FileText } from 'lucide-vue-next'
import { ref, reactive, computed, onMounted } from 'vue'
import Heading from '@/components/Heading.vue'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Textarea } from '@/components/ui/textarea'
import { useToast } from '@/composables/useToast'
import AppLayout from '@/layouts/AppLayout.vue'
import { route } from '@/lib/route'

const props = defineProps<{
  lead?: any
  company?: any
  companies: any[]
  leads: any[]
  nextQuotationNumber: string
  defaultTaxRate: number
}>()

const { success, error } = useToast()

const processing = ref(false)

const form = reactive({
  quotation_number: props.nextQuotationNumber,
  lead_id: props.lead?.id || null,
  company_id: props.company?.id || null,
  client_name: '',
  client_rut: '',
  client_email: '',
  client_phone: '',
  client_address: '',
  issue_date: new Date().toISOString().split('T')[0],
  valid_until: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
  items: [] as any[],
  tax_rate: props.defaultTaxRate,
  notes: '',
  terms: '',
})

onMounted(() => {
  if (props.lead) {
    form.client_name = props.lead.name
    form.client_email = props.lead.email || ''
    form.client_phone = props.lead.phone || ''
    if (props.lead.company) {
      form.company_id = props.lead.company.id
      form.client_rut = props.lead.company.rut || ''
      form.client_address = props.lead.company.address || ''
    }
  } else if (props.company) {
    form.client_name = props.company.display_name
    form.client_rut = props.company.rut || ''
    form.client_email = props.company.email || ''
    form.client_phone = props.company.phone || ''
    form.client_address = props.company.address || ''
  }
})

const totals = computed(() => {
  const subtotal = form.items.reduce((sum, item) => sum + (item.subtotal || 0), 0)
  const tax = subtotal * (form.tax_rate / 100)
  const total = subtotal + tax
  return { subtotal, tax, total }
})

const addItem = () => {
  form.items.push({
    description: '',
    quantity: 1,
    unit_price: 0,
    subtotal: 0,
  })
}

const removeItem = (index: number) => {
  form.items.splice(index, 1)
}

const calculateItemSubtotal = (index: number) => {
  const item = form.items[index]
  item.subtotal = (item.quantity || 0) * (item.unit_price || 0)
}

const onLeadChange = (leadId: any) => {
  const id = leadId === null ? null : Number(leadId)
  if (!id) return
  const lead = props.leads.find(l => l.id === id)
  if (lead) {
    form.client_name = lead.name
    form.client_email = lead.email || ''
    form.client_phone = lead.phone || ''
    if (lead.company) {
      form.company_id = lead.company.id
      form.client_rut = lead.company.rut || ''
      form.client_address = lead.company.address || ''
    }
  }
}

const onCompanyChange = (companyId: any) => {
  const id = companyId === null ? null : Number(companyId)
  if (!id) return
  const company = props.companies.find(c => c.id === id)
  if (company) {
    form.client_name = company.display_name
    form.client_rut = company.rut || ''
    form.client_email = company.email || ''
    form.client_phone = company.phone || ''
    form.client_address = company.address || ''
  }
}

const formatNumber = (num: number) => {
  return new Intl.NumberFormat('es-CL').format(num)
}

const submitForm = () => {
  processing.value = true

  router.post(route('quotations.store'), form, {
    onSuccess: () => {
      processing.value = false
      success('Cotización creada exitosamente')
    },
    onError: (errors) => {
      processing.value = false
      const errorMessage = Object.values(errors).flat().join(', ') || 'Error al crear la cotización'
      error(errorMessage)
    },
  })
}
</script>
