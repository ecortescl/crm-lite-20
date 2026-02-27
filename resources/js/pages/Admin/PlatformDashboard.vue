<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';

type GlobalStats = {
    total_tenants: number;
    total_users: number;
    total_leads: number;
    total_quotations: number;
    total_companies: number;
    total_meetings: number;
    total_api_tokens: number;
};

type Insight = {
    name: string;
    value: number;
} | null;

type TenantStat = {
    id: number;
    name: string;
    slug: string;
    users_count: number;
    leads_count: number;
    quotations_count: number;
    companies_count: number;
    meetings_count: number;
    api_tokens_count: number;
    leads_last_30_days: number;
    quotations_last_30_days: number;
    created_at: string | null;
};

defineProps<{
    globalStats: GlobalStats;
    platformInsights: {
        top_tenant_by_leads: Insight;
        top_tenant_by_quotations: Insight;
    };
    tenantStats: TenantStat[];
}>();
</script>

<template>
    <Head title="Panel Gestor Principal" />

    <AppLayout :breadcrumbs="[{ title: 'Panel Gestor', href: '/platform-admin' }]">
        <div class="space-y-6 p-4 md:p-6">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Panel Administrativo Global</h1>
                <p class="text-sm text-muted-foreground">
                    Vista de clientes registrados, uso de recursos y actividad comercial consolidada.
                </p>
            </div>

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-7">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total CRM (Tenants)</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ globalStats.total_tenants }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total Usuarios</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ globalStats.total_users }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total Leads</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ globalStats.total_leads }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total Cotizaciones</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ globalStats.total_quotations }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Total Empresas</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ globalStats.total_companies }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Reuniones Agendadas</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ globalStats.total_meetings }}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">Tokens API Activos</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-3xl font-bold">{{ globalStats.total_api_tokens }}</p>
                    </CardContent>
                </Card>
            </section>

            <section class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">CRM con más leads</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p v-if="platformInsights.top_tenant_by_leads" class="text-lg font-semibold">
                            {{ platformInsights.top_tenant_by_leads.name }}
                            <span class="text-sm text-muted-foreground">({{ platformInsights.top_tenant_by_leads.value }} leads)</span>
                        </p>
                        <p v-else class="text-sm text-muted-foreground">Sin datos</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">CRM con más cotizaciones</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p v-if="platformInsights.top_tenant_by_quotations" class="text-lg font-semibold">
                            {{ platformInsights.top_tenant_by_quotations.name }}
                            <span class="text-sm text-muted-foreground">({{ platformInsights.top_tenant_by_quotations.value }} cotizaciones)</span>
                        </p>
                        <p v-else class="text-sm text-muted-foreground">Sin datos</p>
                    </CardContent>
                </Card>
            </section>

            <Card>
                <CardHeader>
                    <CardTitle>Uso por Cliente (CRM)</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left text-muted-foreground">
                                    <th class="px-3 py-2 font-medium">CRM</th>
                                    <th class="px-3 py-2 font-medium">Usuarios</th>
                                    <th class="px-3 py-2 font-medium">Empresas</th>
                                    <th class="px-3 py-2 font-medium">Leads</th>
                                    <th class="px-3 py-2 font-medium">Leads 30d</th>
                                    <th class="px-3 py-2 font-medium">Cotizaciones</th>
                                    <th class="px-3 py-2 font-medium">Cotiz. 30d</th>
                                    <th class="px-3 py-2 font-medium">Reuniones</th>
                                    <th class="px-3 py-2 font-medium">Tokens API</th>
                                    <th class="px-3 py-2 font-medium">Creado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tenant in tenantStats" :key="tenant.id" class="border-b">
                                    <td class="px-3 py-2 font-medium">
                                        <div>{{ tenant.name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ tenant.slug }}</div>
                                    </td>
                                    <td class="px-3 py-2">{{ tenant.users_count }}</td>
                                    <td class="px-3 py-2">{{ tenant.companies_count }}</td>
                                    <td class="px-3 py-2">{{ tenant.leads_count }}</td>
                                    <td class="px-3 py-2">{{ tenant.leads_last_30_days }}</td>
                                    <td class="px-3 py-2">{{ tenant.quotations_count }}</td>
                                    <td class="px-3 py-2">{{ tenant.quotations_last_30_days }}</td>
                                    <td class="px-3 py-2">{{ tenant.meetings_count }}</td>
                                    <td class="px-3 py-2">{{ tenant.api_tokens_count }}</td>
                                    <td class="px-3 py-2 text-muted-foreground">{{ tenant.created_at ?? '-' }}</td>
                                </tr>
                                <tr v-if="tenantStats.length === 0">
                                    <td class="px-3 py-4 text-center text-muted-foreground" colspan="10">
                                        No hay CRM registrados todavía.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
