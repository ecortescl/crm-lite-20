<template>
  <div class="flex min-h-svh flex-col items-center justify-center bg-muted/40 p-6 md:p-10">
    <Head title="Iniciar Sesión" />

    <div class="w-full max-w-md">
      <Card>
        <CardHeader class="space-y-4">
          <div class="flex justify-center">
            <AppLogo />
          </div>
          <div class="space-y-2 text-center">
            <h1 class="text-2xl font-semibold tracking-tight">Bienvenido</h1>
            <p class="text-sm text-muted-foreground">
              Ingresa tus credenciales para acceder al sistema
            </p>
          </div>
        </CardHeader>

        <CardContent>
          <div v-if="status" class="mb-4 rounded-md bg-success/10 p-3 text-center text-sm text-success">
            {{ status }}
          </div>

          <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="space-y-4"
          >
            <div class="space-y-2">
              <Label for="email">Correo Electrónico</Label>
              <Input
                id="email"
                type="email"
                name="email"
                required
                autofocus
                autocomplete="email"
                placeholder="correo@ejemplo.cl"
              />
              <InputError :message="errors.email" />
            </div>

            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <Label for="password">Contraseña</Label>
                <TextLink
                  v-if="canResetPassword"
                  :href="request()"
                  class="text-sm"
                >
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
              <Label for="remember" class="text-sm font-normal cursor-pointer">
                Recordarme
              </Label>
            </div>

            <Button
              type="submit"
              class="w-full"
              :disabled="processing"
              data-test="login-button"
            >
              <Spinner v-if="processing" class="mr-2" />
              Iniciar Sesión
            </Button>
          </Form>
        </CardContent>

        <CardFooter class="flex flex-col space-y-4">
          <div class="text-center text-xs text-muted-foreground">
            © {{ new Date().getFullYear() }} CRM landings.cl. Todos los derechos reservados.
          </div>
        </CardFooter>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import InputError from '@/components/InputError.vue'
import TextLink from '@/components/TextLink.vue'
import AppLogo from '@/components/AppLogo.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card'
import { Checkbox } from '@/components/ui/checkbox'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Spinner } from '@/components/ui/spinner'
import { store } from '@/routes/login'
import { request } from '@/routes/password'

defineProps<{
  status?: string
  canResetPassword: boolean
  canRegister: boolean
}>()
</script>
