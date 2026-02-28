<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { route } from '@/lib/route';

defineProps<{
    canRegister: boolean;
    canLogin: boolean;
}>();

const benefits = [
    {
        title: 'Embudos de venta por etapa',
        description: 'Define cada paso de tu proceso: captura, contacto, reunión, negociación y cierre.',
    },
    {
        title: 'Gestión del proceso de venta',
        description: 'Haz seguimiento con contexto, responsables y próximos pasos en cada oportunidad.',
    },
    {
        title: 'Cotizaciones y seguimiento',
        description: 'Emite cotizaciones rápidas y controla su estado dentro de tu flujo comercial.',
    },
    {
        title: 'Trabajo en equipo',
        description: 'Invita usuarios, asigna roles y ordena el trabajo de ventas sin perder visibilidad.',
    },
];

const funnelSteps = [
    { name: 'Captura', detail: 'Todos tus leads llegan a una sola bandeja.' },
    { name: 'Calificación', detail: 'Prioriza oportunidades reales.' },
    { name: 'Reunión', detail: 'Agenda y seguimiento desde el CRM.' },
    { name: 'Propuesta', detail: 'Cotizaciones conectadas al embudo.' },
    { name: 'Cierre', detail: 'Mide conversiones y mejora tu proceso.' },
];
</script>

<template>
    <div class="min-h-screen bg-[radial-gradient(circle_at_top,_#fef3c7_0%,_#fff7ed_22%,_#f8fafc_58%,_#ecfeff_100%)] text-slate-900">
        <Head title="CRM gratis para pymes e independientes" />

        <header class="sticky top-0 z-20 border-b border-white/80 bg-white/85 backdrop-blur">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="text-xl font-black tracking-tight">CRM Lite</span>
                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">Gratis</span>
                </div>
                <nav class="hidden items-center gap-6 text-sm font-medium text-slate-700 md:flex">
                    <a href="#beneficios" class="hover:text-slate-950">Beneficios</a>
                    <a href="#embudo" class="hover:text-slate-950">Embudo</a>
                    <a href="#faq" class="hover:text-slate-950">FAQ</a>
                </nav>
                <div class="flex items-center gap-2">
                    <Link v-if="canLogin" :href="route('login')">
                        <Button variant="ghost">Iniciar sesión</Button>
                    </Link>
                    <Link v-if="canRegister" :href="route('register')">
                        <Button class="bg-slate-900 text-white hover:bg-slate-800">Crear cuenta gratis</Button>
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl px-6 pb-20 pt-10 md:pt-14">
            <section class="grid items-center gap-10 md:grid-cols-[1.15fr_0.85fr]">
                <div class="space-y-6">
                    <p class="inline-flex rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-800">
                        CRM gratis para pymes e independientes
                    </p>
                    <h1 class="text-4xl font-black leading-tight md:text-6xl">
                        Ordena tu embudo de venta y
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">gestiona mejor tu proceso</span>
                    </h1>
                    <p class="max-w-2xl text-lg text-slate-700">
                        Empieza gratis, centraliza tus oportunidades y mejora la conversión etapa por etapa con un flujo claro de ventas.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <Link v-if="canRegister" :href="route('register')">
                            <Button size="lg" class="bg-slate-900 text-white hover:bg-slate-800">Empezar gratis</Button>
                        </Link>
                        <Link v-if="canLogin" :href="route('login')">
                            <Button size="lg" class="border border-slate-300 bg-white text-slate-900 hover:bg-slate-100">Entrar a mi cuenta</Button>
                        </Link>
                    </div>
                </div>

                <div id="embudo" class="rounded-2xl border border-slate-200 bg-white/90 p-6 shadow-xl backdrop-blur">
                    <h2 class="text-base font-bold">Embudo de venta sugerido</h2>
                    <p class="mt-1 text-sm text-slate-600">Diseñado para vender con orden y seguimiento continuo.</p>
                    <div class="mt-4 space-y-3">
                        <div
                            v-for="(step, index) in funnelSteps"
                            :key="step.name"
                            class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3"
                        >
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                {{ index + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold">{{ step.name }}</p>
                                <p class="text-xs text-slate-600">{{ step.detail }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="beneficios" class="mt-14 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <article
                    v-for="benefit in benefits"
                    :key="benefit.title"
                    class="rounded-xl border border-slate-200 bg-white/90 p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md"
                >
                    <h3 class="text-base font-bold">{{ benefit.title }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ benefit.description }}</p>
                </article>
            </section>

            <section class="mt-14 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-8 md:p-10">
                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Plan gratuito</p>
                        <h3 class="mt-2 text-3xl font-bold text-emerald-900">Comienza hoy con tu CRM sin costo</h3>
                        <p class="mt-2 text-sm text-emerald-800">Ideal para iniciar y ordenar tu proceso de venta desde el primer cliente.</p>
                    </div>
                    <Link v-if="canRegister" :href="route('register')">
                        <Button size="lg" class="bg-emerald-700 text-white hover:bg-emerald-600">Crear cuenta gratis</Button>
                    </Link>
                </div>
            </section>
        </main>

        <footer id="faq" class="border-t border-slate-200 bg-white/90">
            <div class="mx-auto grid w-full max-w-7xl gap-8 px-6 py-10 md:grid-cols-2">
                <div>
                    <p class="text-lg font-black">CRM Lite</p>
                    <p class="mt-2 text-sm text-slate-600">
                        CRM gratis para gestionar embudos de venta y procesos comerciales de pymes e independientes.
                    </p>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">Preguntas frecuentes</p>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li>¿El CRM es gratis? Sí, puedes empezar sin costo.</li>
                        <li>¿Puedo invitar a mi equipo? Sí, con roles y permisos.</li>
                        <li>¿Puedo emitir cotizaciones? Sí, dentro del flujo de venta.</li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</template>
