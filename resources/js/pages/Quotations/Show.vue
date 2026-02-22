<template>
  <AppLayout title="Ver Cotización">
    <div class="px-4 py-6 space-y-6">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <Heading
          :title="`Cotización ${quotation.quotation_number}`"
          description="Vista previa de la cotización"
        />
        <div class="flex flex-wrap items-center gap-2">
          <Select v-model="currentStatus" @update:model-value="updateStatus">
            <SelectTrigger class="w-[180px]">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="draft">Borrador</SelectItem>
              <SelectItem value="sent">Enviada</SelectItem>
              <SelectItem value="accepted">Aceptada</SelectItem>
              <SelectItem value="rejected">Rechazada</SelectItem>
              <SelectItem value="expired">Expirada</SelectItem>
            </SelectContent>
          </Select>
          <Button variant="outline" size="sm" @click="downloadPdf">
            <FileText class="w-4 h-4 mr-2" />
            Descargar PDF
          </Button>
          <Button variant="outline" size="sm" @click="router.visit(route('quotations.edit', quotation.id))">
            <Pencil class="w-4 h-4 mr-2" />
            Editar
          </Button>
          <Button variant="outline" size="sm" @click="router.visit(route('quotations.index'))">
            <ArrowLeft class="w-4 h-4 mr-2" />
            Volver
          </Button>
        </div>
      </div>

      <!-- Invoice Template -->
      <Card class="overflow-hidden">
        <div class="p-4 md:p-8 bg-white" id="invoice-content">
          <!-- Header con Logo y Datos de Empresa -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-6 border-b-2 border-gray-900">
            <div>
              <img 
                v-if="companySettings.logo" 
                :src="companySettings.logo" 
                alt="Logo" 
                class="h-16 md:h-20 mb-4 object-contain"
              />
              <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">{{ companySettings.name || 'Empresa' }}</h1>
              <div class="text-sm text-gray-700 space-y-1">
                <p v-if="companySettings.rut" class="font-medium">RUT: {{ companySettings.rut }}</p>
                <p v-if="companySettings.giro">{{ companySettings.giro }}</p>
                <p v-if="companySettings.address" class="mt-2">{{ companySettings.address }}</p>
                <p v-if="companySettings.phone">Tel: {{ companySettings.phone }}</p>
                <p v-if="companySettings.email">{{ companySettings.email }}</p>
              </div>
            </div>
            <div class="md:text-right space-y-4">
              <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-1">COTIZACIÓN</h2>
                <p class="text-lg font-semibold text-gray-700">{{ quotation.quotation_number }}</p>
              </div>
              <div class="inline-block md:float-right bg-gray-100 px-4 py-3 rounded">
                <div class="text-sm space-y-1">
                  <div class="flex justify-between gap-8">
                    <span class="text-gray-600 font-medium">Fecha Emisión:</span>
                    <span class="font-semibold">{{ formatDate(quotation.issue_date) }}</span>
                  </div>
                  <div class="flex justify-between gap-8">
                    <span class="text-gray-600 font-medium">Válida Hasta:</span>
                    <span class="font-semibold">{{ formatDate(quotation.valid_until) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Cliente -->
          <div class="mb-8">
            <div class="bg-gray-50 px-4 py-3 border-l-4 border-gray-900">
              <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Señor(es)</h3>
              <div class="text-gray-900">
                <p class="font-bold text-lg mb-1">{{ quotation.client_name }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1 text-sm">
                  <p v-if="quotation.client_rut"><span class="font-medium">RUT:</span> {{ quotation.client_rut }}</p>
                  <p v-if="quotation.client_phone"><span class="font-medium">Teléfono:</span> {{ quotation.client_phone }}</p>
                  <p v-if="quotation.client_email"><span class="font-medium">Email:</span> {{ quotation.client_email }}</p>
                  <p v-if="quotation.client_address"><span class="font-medium">Dirección:</span> {{ quotation.client_address }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Items Table -->
          <div class="mb-8">
            <div class="overflow-x-auto">
              <table class="w-full min-w-[600px] border-collapse">
                <thead>
                  <tr class="bg-gray-900 text-white">
                    <th class="text-left py-3 px-4 text-sm font-bold uppercase">Descripción</th>
                    <th class="text-center py-3 px-4 text-sm font-bold uppercase w-24">Cant.</th>
                    <th class="text-right py-3 px-4 text-sm font-bold uppercase w-32">Precio Unit.</th>
                    <th class="text-right py-3 px-4 text-sm font-bold uppercase w-32">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in quotation.items" :key="index" class="border-b border-gray-200">
                    <td class="py-4 px-4 text-sm text-gray-900 align-top">
                      <div class="whitespace-pre-line">{{ item.description }}</div>
                    </td>
                    <td class="py-4 px-4 text-sm text-center text-gray-900 align-top">{{ formatNumber(item.quantity) }}</td>
                    <td class="py-4 px-4 text-sm text-right text-gray-900 align-top">${{ formatNumber(item.unit_price) }}</td>
                    <td class="py-4 px-4 text-sm text-right font-semibold text-gray-900 align-top">${{ formatNumber(item.subtotal) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Totales -->
          <div class="flex justify-end mb-8">
            <div class="w-full md:w-96">
              <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-3 space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-700 font-medium">Subtotal:</span>
                    <span class="font-semibold text-gray-900">${{ formatNumber(quotation.subtotal) }}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-700 font-medium">IVA ({{ quotation.tax_rate }}%):</span>
                    <span class="font-semibold text-gray-900">${{ formatNumber(quotation.tax_amount) }}</span>
                  </div>
                </div>
                <div class="bg-gray-900 text-white px-6 py-4">
                  <div class="flex justify-between items-center">
                    <span class="text-base font-bold uppercase">Total:</span>
                    <span class="text-2xl font-bold">${{ formatNumber(quotation.total) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Notas y Términos -->
          <div v-if="quotation.notes || quotation.terms" class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t-2 border-gray-200 pt-6 mb-6">
            <div v-if="quotation.notes" class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
              <h3 class="text-xs font-bold text-blue-900 uppercase tracking-wider mb-2">Notas Importantes</h3>
              <p class="text-sm text-gray-700 whitespace-pre-line">{{ quotation.notes }}</p>
            </div>
            <div v-if="quotation.terms" class="bg-amber-50 p-4 rounded border-l-4 border-amber-500">
              <h3 class="text-xs font-bold text-amber-900 uppercase tracking-wider mb-2">Términos y Condiciones</h3>
              <p class="text-sm text-gray-700 whitespace-pre-line">{{ quotation.terms }}</p>
            </div>
          </div>

          <!-- Footer -->
          <div class="border-t border-gray-200 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-xs text-gray-500">
              <div>
                <p class="font-medium text-gray-700">Emitida por: {{ quotation.user?.name }}</p>
                <p class="mt-1">Fecha de emisión: {{ formatDateTime(quotation.created_at) }}</p>
              </div>
              <div class="text-right">
                <p class="italic">Esta cotización fue generada electrónicamente</p>
                <p class="mt-1">y tiene validez sin firma ni timbre</p>
              </div>
            </div>
          </div>
        </div>
      </Card>

      <!-- Información Adicional -->
      <Card>
        <div class="p-6 space-y-4">
          <h3 class="text-lg font-semibold">Información Adicional</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <div class="space-y-1">
              <span class="text-muted-foreground block">Lead Asociado:</span>
              <span class="font-medium">{{ quotation.lead?.name || 'N/A' }}</span>
            </div>
            <div class="space-y-1">
              <span class="text-muted-foreground block">Empresa Asociada:</span>
              <span class="font-medium">{{ quotation.company?.display_name || 'N/A' }}</span>
            </div>
            <div class="space-y-1">
              <span class="text-muted-foreground block">Creada:</span>
              <span class="font-medium">{{ formatDateTime(quotation.created_at) }}</span>
            </div>
            <div class="space-y-1">
              <span class="text-muted-foreground block">Última actualización:</span>
              <span class="font-medium">{{ formatDateTime(quotation.updated_at) }}</span>
            </div>
          </div>
        </div>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { FileText, Pencil, ArrowLeft } from 'lucide-vue-next'
import { ref } from 'vue'
import Heading from '@/components/Heading.vue'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import AppLayout from '@/layouts/AppLayout.vue'
import { route } from '@/lib/route'

const props = defineProps<{
  quotation: any
  companySettings: any
}>()

const currentStatus = ref(props.quotation.status)

const downloadPdf = () => {
  window.location.href = route('quotations.pdf', props.quotation.id)
}

const updateStatus = (newStatus: any) => {
  if (!newStatus || typeof newStatus !== 'string') return
  
  router.patch(route('quotations.update-status', props.quotation.id), {
    status: newStatus,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      currentStatus.value = newStatus
    },
  })
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-CL')
}

const formatDateTime = (date: string) => {
  return new Date(date).toLocaleString('es-CL')
}

const formatNumber = (num: number) => {
  return new Intl.NumberFormat('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(num)
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
