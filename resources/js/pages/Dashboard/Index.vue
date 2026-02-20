<template>
  <AppLayout title="Dashboard">
    <div class="px-4 py-6 space-y-6">
      <Heading
        title="Dashboard"
        description="Resumen de leads y actividad"
      />

      <!-- Stats Cards Compactos -->
      <div class="grid gap-3 md:grid-cols-3 lg:grid-cols-6">
        <Card v-for="stat in stats" :key="stat.id" class="hover:shadow-md transition-shadow">
          <CardContent class="p-4">
            <div class="flex items-center gap-3">
              <div
                class="h-10 w-10 rounded-lg flex items-center justify-center shrink-0"
                :style="{ backgroundColor: stat.color + '20' }"
              >
                <component :is="getIcon(stat.name)" class="h-5 w-5" :style="{ color: stat.color }" />
              </div>
              <div class="min-w-0">
                <p class="text-xs text-muted-foreground truncate">{{ stat.name }}</p>
                <p class="text-xl font-bold">{{ stat.count }}</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

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
  CheckCircle2 
} from 'lucide-vue-next'

defineProps<{
  stats: Array<{ id: number; name: string; color: string; count: number }>
  kanbanStatuses: Array<any>
}>()

const getIcon = (statusName: string) => {
  const name = statusName.toLowerCase()
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
</script>
