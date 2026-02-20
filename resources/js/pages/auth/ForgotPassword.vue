<template>
  <div class="flex min-h-svh flex-col items-center justify-center bg-muted/40 p-6 md:p-10">
    <Head title="Recuperar Contraseña" />

    <div class="w-full max-w-md">
      <Card>
        <CardHeader class="space-y-4">
          <div class="flex justify-center">
            <AppLogo />
          </div>
          <div class="space-y-2 text-center">
            <h1 class="text-2xl font-semibold tracking-tight">¿Olvidaste tu contraseña?</h1>
            <p class="text-sm text-muted-foreground">
              Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña
            </p>
          </div>
        </CardHeader>

        <CardContent>
          <div v-if="status" class="mb-4 rounded-md bg-success/10 p-3 text-center text-sm text-success">
            {{ status }}
          </div>

          <Form v-bind="email.form()" v-slot="{ errors, processing }" class="space-y-4">
            <div class="space-y-2">
              <Label for="email">Correo Electrónico</Label>
              <Input
                id="email"
                type="email"
                name="email"
                autocomplete="email"
                autofocus
                placeholder="correo@ejemplo.cl"
              />
              <InputError :message="errors.email" />
            </div>

            <Button
              type="submit"
              class="w-full"
              :disabled="processing"
              data-test="email-password-reset-link-button"
            >
              <Spinner v-if="processing" class="mr-2" />
              Enviar enlace de recuperación
            </Button>
          </Form>
        </CardContent>

        <CardFooter class="flex flex-col space-y-4">
          <div class="text-center text-sm text-muted-foreground">
            ¿Recordaste tu contraseña?
            <TextLink :href="login()" class="ml-1">Iniciar sesión</TextLink>
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
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Spinner } from '@/components/ui/spinner'
import { login } from '@/routes'
import { email } from '@/routes/password'

defineProps<{
  status?: string
}>()
</script>
