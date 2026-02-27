<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Users, UserCog, Shield, Tag, Kanban, Building2, FileText, Settings, Calendar, ChartNoAxesCombined } from 'lucide-vue-next';
import { computed } from 'vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

const page = usePage();
const permissions = computed<string[]>(() => page.props.auth?.permissions ?? []);
const isPlatformAdmin = computed<boolean>(() => !!page.props.auth?.isPlatformAdmin);
const homeLink = computed<string>(() => (isPlatformAdmin.value ? '/platform-admin' : dashboard()));
const can = (permission: string) => permissions.value.includes(permission);

const mainNavItems = computed<NavItem[]>(() => [
    ...(isPlatformAdmin.value ? [{
        title: 'Panel Gestor',
        href: '/platform-admin',
        icon: ChartNoAxesCombined,
    }] : [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Leads',
            href: '/leads',
            icon: Users,
        },
        {
            title: 'Kanban',
            href: '/leads/kanban',
            icon: Kanban,
        },
        {
            title: 'Calendario',
            href: '/calendar',
            icon: Calendar,
        },
        {
            title: 'Empresas',
            href: '/companies',
            icon: Building2,
        },
        {
            title: 'Cotizaciones',
            href: '/quotations',
            icon: FileText,
        },
        ...(can('manage_users') || can('manage_roles') || can('manage_permissions') || can('manage_lead_statuses') ? [{
            title: 'Administración',
            icon: Settings,
            items: [
                ...(can('manage_users') ? [{
                    title: 'Usuarios',
                    href: '/users',
                    icon: UserCog,
                }] : []),
                ...(can('manage_roles') ? [{
                    title: 'Roles',
                    href: '/roles',
                    icon: Shield,
                }] : []),
                ...(can('manage_permissions') ? [{
                    title: 'Permisos',
                    href: '/permissions',
                    icon: Shield,
                }] : []),
                ...(can('manage_lead_statuses') ? [{
                    title: 'Estados',
                    href: '/lead-statuses',
                    icon: Tag,
                }] : []),
            ],
        }] : []),
    ]),
]);

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="homeLink">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
