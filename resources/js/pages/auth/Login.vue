<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import AppLogo from '@/components/AppLogo.vue'
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Spinner } from '@/components/ui/spinner'
import { route } from '@/lib/route'
import { store } from '@/routes/login'
import { request } from '@/routes/password'

defineProps<{
  status?: string
  canResetPassword: boolean
  canRegister: boolean
}>()

const highlights = [
  'Revisa tu embudo de venta en segundos.',
  'Haz seguimiento de oportunidades sin perder contexto.',
  'Controla cotizaciones y avance de tu equipo.',
  'Pensado para pymes e independientes.',
]
</script>

<template>
  <Head title="Iniciar sesión" />

  <div
    class="min-h-svh bg-[radial-gradient(circle_at_top_left,_#fde68a_0%,_#fff7ed_28%,_#f8fafc_60%,_#ecfeff_100%)] p-6 md:p-10"
  >
    <div class="mx-auto grid w-full max-w-6xl items-stretch gap-6 lg:grid-cols-2">
      <section class="rounded-2xl border border-white/70 bg-white/90 p-6 shadow-sm backdrop-blur md:p-8">
        <div class="mb-6 flex items-center gap-3">
          <div class="h-10 w-32">
            <AppLogo />
          </div>
        </div>

        <div class="space-y-1">
          <p class="text-sm font-medium text-amber-700">Acceso a tu CRM</p>
          <h1 class="text-3xl font-bold tracking-tight text-slate-900">Bienvenido otra vez</h1>
          <p class="text-sm text-slate-600">Inicia sesión para continuar con la gestión de tu proceso de venta.</p>
        </div>

        <div
          v-if="status"
          class="mt-4 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700"
        >
          {{ status }}
        </div>

        <Form
          v-bind="store.form()"
          :reset-on-success="['password']"
          v-slot="{ errors, processing }"
          class="mt-7 space-y-4"
        >
          <div class="grid gap-2">
            <Label for="email">Correo electrónico</Label>
            <Input
              id="email"
              type="email"
              name="email"
              required
              autofocus
              autocomplete="email"
              placeholder="tu@correo.com"
            />
            <InputError :message="errors.email" />
          </div>

          <div class="grid gap-2">
            <div class="flex items-center justify-between">
              <Label for="password">Contraseña</Label>
              <TextLink v-if="canResetPassword" :href="request()" class="text-sm underline underline-offset-4">
                ¿Olvidaste tu contraseña?
              </TextLink>
            </div>
            <Input
              id="password"
              type="password"
              name="password"
              required
              autocomplete="current-password"
              placeholder="••••••••"
            />
            <InputError :message="errors.password" />
          </div>

          <div class="flex items-center space-x-2">
            <Checkbox id="remember" name="remember" />
            <Label for="remember" class="cursor-pointer text-sm font-normal">Mantener sesión iniciada</Label>
          </div>

          <Button type="submit" class="w-full bg-slate-900 text-white hover:bg-slate-800" :disabled="processing" data-test="login-button">
            <Spinner v-if="processing" class="mr-2" />
            Iniciar sesión
          </Button>
        </Form>

        <div v-if="canRegister" class="mt-5 text-center text-sm text-slate-600">
          ¿Aún no tienes cuenta?
          <TextLink :href="route('register')" class="underline underline-offset-4">
            Regístrate gratis
          </TextLink>
        </div>

        <p class="mt-5 text-center text-xs text-slate-500">
          © {{ new Date().getFullYear() }} CRM Lite. Todos los derechos reservados.
        </p>
      </section>

      <aside class="relative overflow-hidden rounded-2xl border border-slate-200 bg-slate-900 p-7 text-white shadow-xl md:p-9">
        <div class="absolute -left-10 top-6 h-32 w-32 rounded-full bg-amber-400/20 blur-2xl" />
        <div class="absolute -right-10 bottom-8 h-36 w-36 rounded-full bg-cyan-300/20 blur-2xl" />

        <div class="relative space-y-6">
          <div>
            <p class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold tracking-wide text-amber-200">
              Sigue vendiendo con orden
            </p>
            <h2 class="mt-4 text-3xl font-bold leading-tight">Tu embudo de venta, bajo control</h2>
            <p class="mt-3 text-sm text-slate-300">
              Accede a tus oportunidades, reuniones y cotizaciones para mantener el ritmo de ventas.
            </p>
          </div>

          <ul class="space-y-3">
            <li
              v-for="highlight in highlights"
              :key="highlight"
              class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-100"
            >
              {{ highlight }}
            </li>
          </ul>

          <div class="rounded-xl border border-emerald-300/30 bg-emerald-300/10 p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-200">Tip de productividad</p>
            <p class="mt-1 text-sm text-emerald-100">
              Revisa cada día tu etapa de negociación para cerrar oportunidades más rápido.
            </p>
          </div>
        </div>
      </aside>
    </div>
  </div>
</template>
