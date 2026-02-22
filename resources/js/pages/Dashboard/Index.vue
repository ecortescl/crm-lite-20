<template>
  <AppLayout title="Dashboard">
    <div class="px-4 py-6 space-y-6">
      <Heading
        title="Dashboard"
        description="Resumen de leads y actividad"
      />

      <!-- Métricas de Conversión -->
      <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
        <Card class="hover:shadow-md transition-shadow">
          <CardContent class="p-4">
            <p class="text-xs text-muted-foreground">Tasa de Conversión</p>
            <p class="text-2xl font-bold">{{ formatPercent(metrics.conversionRate) }}</p>
            <p class="text-[11px] text-muted-foreground">
              {{ metrics.successCount }} ganados / {{ metrics.successCount + metrics.discardedCount }} cerrados
            </p>
          </CardContent>
        </Card>
        <Card class="hover:shadow-md transition-shadow">
          <CardContent class="p-4">
            <p class="text-xs text-muted-foreground">Total Cotizado</p>
            <p class="text-2xl font-bold">{{ formatCurrency(metrics.totalQuoted) }}</p>
            <p class="text-[11px] text-muted-foreground">Suma de todas las cotizaciones</p>
          </CardContent>
        </Card>
        <Card class="hover:shadow-md transition-shadow">
          <CardContent class="p-4">
            <p class="text-xs text-muted-foreground">% en Negociación</p>
            <p class="text-2xl font-bold">{{ formatPercent(metrics.negotiationRate) }}</p>
            <p class="text-[11px] text-muted-foreground">{{ metrics.negotiationCount }} en estado negociación</p>
          </CardContent>
        </Card>
        <Card class="hover:shadow-md transition-shadow">
          <CardContent class="p-4">
            <p class="text-xs text-muted-foreground">% con Reunión Agendada</p>
            <p class="text-2xl font-bold">{{ formatPercent(metrics.scheduledRate) }}</p>
            <p class="text-[11px] text-muted-foreground">{{ metrics.scheduledCount }} con cita definida</p>
          </CardContent>
        </Card>
      </div>

      <!-- Stats Cards Compactos -->
      <div class="grid gap-3 md:grid-cols-3 lg:grid-cols-6">
        <Card v-for="stat in stats" :key="stat.id" class="hover:shadow-md transition-shadow">
          <CardContent class="p-4">
            <div class="flex items-center gap-3">
              <div
                class="h-10 w-10 rounded-lg flex items-center justify-center shrink-0"
                :style="{ backgroundColor: stat.color + '20' }"
              >
                <component :is="getIcon(stat.icon, stat.name)" class="h-5 w-5" :style="{ color: stat.color }" />
              </div>
              <div class="min-w-0">
                <p class="text-xs text-muted-foreground truncate">{{ stat.name }}</p>
                <p class="text-xl font-bold">{{ stat.count }}</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Distribución Compacta -->
      <Card>
        <CardHeader>
          <CardTitle>Distribución del Kanban</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <div
              v-for="stat in kanbanChart"
              :key="stat.id"
              class="flex items-center gap-3 rounded-md border px-3 py-2"
            >
              <div
                class="h-9 w-9 rounded-md flex items-center justify-center shrink-0"
                :style="{ backgroundColor: stat.color + '20' }"
              >
                <component :is="getIcon(stat.icon, stat.name)" class="h-4 w-4" :style="{ color: stat.color }" />
              </div>
              <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-2">
                  <p class="text-xs font-medium truncate">{{ stat.name }}</p>
                  <p class="text-xs text-muted-foreground shrink-0">
                    {{ stat.count }} · {{ getPercent(stat.count).toFixed(1) }}%
                  </p>
                </div>
                <div class="mt-1 h-1.5 rounded-full bg-muted overflow-hidden">
                  <div
                    class="h-1.5 rounded-full"
                    :style="{
                      width: getPercent(stat.count) + '%',
                      backgroundColor: stat.color
                    }"
                  />
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Kanban Minimalista -->
      <Card>
        <CardHeader>
          <div class="flex items-center justify-between">
            <CardTitle>Pipeline de Leads</CardTitle>
            <Button as-child variant="outline" size="sm">
              <Link :href="route('leads.kanban')">Ver Kanban Completo</Link>
            </Button>
          </div>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <div
              v-for="status in kanbanStatuses"
              :key="status.id"
              class="space-y-2"
            >
              <div class="flex items-center gap-2">
                <div
                  class="h-2 w-2 rounded-full"
                  :style="{ backgroundColor: status.color }"
                />
                <span class="text-xs font-medium truncate">{{ status.name }}</span>
              </div>
              <div class="space-y-1.5">
                <div
                  v-for="lead in status.leads.slice(0, 3)"
                  :key="lead.id"
                  class="bg-muted rounded-md p-2 text-xs hover:bg-muted/80 transition-colors cursor-pointer"
                  @click="goToLead(lead.id)"
                >
                  <p class="font-medium truncate">{{ lead.name }}</p>
                  <p class="text-muted-foreground truncate text-[10px]">{{ lead.company || lead.email }}</p>
                </div>
                <div
                  v-if="status.leads.length > 3"
                  class="text-center text-[10px] text-muted-foreground py-1"
                >
                  +{{ status.leads.length - 3 }} más
                </div>
                <div
                  v-if="status.leads.length === 0"
                  class="text-center text-[10px] text-muted-foreground py-3"
                >
                  Sin leads
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3'
import { route } from '@/lib/route'
import AppLayout from '@/layouts/AppLayout.vue'
import Heading from '@/components/Heading.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { 
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

const props = defineProps<{
  stats: Array<{ id: number; name: string; color: string; count: number; icon?: string | null }>
  kanbanStatuses: Array<any>
  kanbanChart: Array<{ id: number; name: string; color: string; count: number; icon?: string | null }>
  metrics: {
    totalLeads: number
    successCount: number
    discardedCount: number
    conversionRate: number
    totalQuoted: number
    negotiationCount: number
    negotiationRate: number
    scheduledCount: number
    scheduledRate: number
  }
}>()

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

const goToLead = (leadId: number) => {
  router.visit(route('leads.kanban'))
}

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-CL', {
    style: 'currency',
    currency: 'CLP',
    maximumFractionDigits: 0,
  }).format(value ?? 0)
}

const formatPercent = (value: number) => {
  return `${(value ?? 0).toFixed(2)}%`
}

const getPercent = (count: number) => {
  const total = props.metrics.totalLeads || 0
  if (total === 0) return 0
  return (count / total) * 100
}
</script>
