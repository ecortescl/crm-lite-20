<template>
  <div class="flex min-h-svh flex-col items-center justify-center bg-muted/40 p-6 md:p-10">
    <Head title="Restablecer Contraseña" />

    <div class="w-full max-w-md">
      <Card>
        <CardHeader class="space-y-4">
          <div class="flex justify-center">
            <AppLogo />
          </div>
          <div class="space-y-2 text-center">
            <h1 class="text-2xl font-semibold tracking-tight">Restablecer Contraseña</h1>
            <p class="text-sm text-muted-foreground">
              Ingresa tu nueva contraseña
            </p>
          </div>
        </CardHeader>

        <CardContent>
          <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="space-y-4"
          >
            <div class="space-y-2">
              <Label for="email">Correo Electrónico</Label>
              <Input
                id="email"
                type="email"
                name="email"
                autocomplete="email"
                v-model="inputEmail"
                readonly
                class="bg-muted"
              />
              <InputError :message="errors.email" />
            </div>

            <div class="space-y-2">
              <Label for="password">Nueva Contraseña</Label>
              <Input
                id="password"
                type="password"
                name="password"
                autocomplete="new-password"
                autofocus
                placeholder="••••••••"
              />
              <InputError :message="errors.password" />
            </div>

            <div class="space-y-2">
              <Label for="password_confirmation">Confirmar Contraseña</Label>
              <Input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                placeholder="••••••••"
              />
              <InputError :message="errors.password_confirmation" />
            </div>

            <Button
              type="submit"
              class="w-full"
              :disabled="processing"
              data-test="reset-password-button"
            >
              <Spinner v-if="processing" class="mr-2" />
              Restablecer Contraseña
            </Button>
          </Form>
        </CardContent>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import InputError from '@/components/InputError.vue'
import AppLogo from '@/components/AppLogo.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Spinner } from '@/components/ui/spinner'
import { update } from '@/routes/password'

const props = defineProps<{
  token: string
  email: string
}>()

const inputEmail = ref(props.email)
</script>
