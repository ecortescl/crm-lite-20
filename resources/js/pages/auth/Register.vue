<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { route } from '@/lib/route';
import { login } from '@/routes';

const form = useForm({
    company_name: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};

const benefits = [
    'Embudo de venta ordenado desde el día uno.',
    'Seguimiento de leads y reuniones en un solo lugar.',
    'Cotizaciones listas para enviar en minutos.',
    'Ideal para pymes, startups e independientes.',
];
</script>

<template>
    <Head title="Crear cuenta" />

    <div class="min-h-svh bg-[radial-gradient(circle_at_top_left,_#fde68a_0%,_#fff7ed_28%,_#f8fafc_60%,_#ecfeff_100%)] p-6 md:p-10">
        <div class="mx-auto grid w-full max-w-6xl items-stretch gap-6 lg:grid-cols-2">
            <section class="rounded-2xl border border-white/70 bg-white/90 p-6 shadow-sm backdrop-blur md:p-8">
                <div class="mb-6 flex items-center gap-3">
                    <div class="h-10 w-32">
                        <AppLogo />
                    </div>
                </div>

                <div class="space-y-1">
                    <p class="text-sm font-medium text-amber-700">Registro gratuito</p>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Crea tu cuenta</h1>
                    <p class="text-sm text-slate-600">Empieza a organizar tu proceso de venta en minutos.</p>
                </div>

                <form @submit.prevent="submit" class="mt-7 flex flex-col gap-4">
                    <div class="grid gap-2">
                        <Label for="company_name">Empresa o marca</Label>
                        <Input
                            id="company_name"
                            v-model="form.company_name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="organization"
                            placeholder="Ej: Servicios Martínez"
                        />
                        <InputError :message="form.errors.company_name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Tu nombre</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            :tabindex="2"
                            autocomplete="name"
                            placeholder="Nombre y apellido"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Correo electrónico</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            :tabindex="3"
                            autocomplete="email"
                            placeholder="tu@correo.com"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Contraseña</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            placeholder="Mínimo 8 caracteres"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirmar contraseña</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            :tabindex="5"
                            autocomplete="new-password"
                            placeholder="Repite tu contraseña"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <Button
                        type="submit"
                        class="mt-2 w-full bg-slate-900 text-white hover:bg-slate-800"
                        tabindex="6"
                        :disabled="form.processing"
                        data-test="register-user-button"
                    >
                        <Spinner v-if="form.processing" />
                        Crear cuenta gratis
                    </Button>
                </form>

                <div class="mt-5 text-center text-sm text-slate-600">
                    ¿Ya tienes una cuenta?
                    <TextLink
                        :href="login()"
                        class="underline underline-offset-4"
                        :tabindex="7"
                    >
                        Inicia sesión
                    </TextLink>
                </div>
            </section>

            <aside class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-900 p-7 text-white shadow-xl md:p-9">
                <div class="absolute -left-10 top-6 h-32 w-32 rounded-full bg-amber-400/20 blur-2xl" />
                <div class="absolute -right-10 bottom-8 h-36 w-36 rounded-full bg-cyan-300/20 blur-2xl" />

                <div class="relative space-y-6">
                    <div>
                        <p class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-wide text-amber-200">
                            Beneficios al registrarte
                        </p>
                        <h2 class="mt-4 text-3xl font-bold leading-tight">Todo tu proceso de ventas en un solo lugar</h2>
                        <p class="mt-3 text-sm text-slate-300">
                            Deja atrás planillas y mensajes sueltos. Gestiona oportunidades de forma profesional desde hoy.
                        </p>
                    </div>

                    <ul class="space-y-3">
                        <li
                            v-for="benefit in benefits"
                            :key="benefit"
                            class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100"
                        >
                            {{ benefit }}
                        </li>
                    </ul>

                    <div class="rounded-xl border border-amber-300/30 bg-amber-300/10 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-200">Plan inicial</p>
                        <p class="mt-1 text-sm text-amber-100">
                            Comienza gratis y escala cuando tu operación crezca.
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</template>
