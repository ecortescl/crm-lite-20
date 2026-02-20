<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from '@/lib/route';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('user-password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <AppLayout title="Contraseña">
        <h1 class="sr-only">Configuración de Contraseña</h1>

        <SettingsLayout>
            <div class="space-y-6">
                <Heading
                    title="Contraseña"
                    description="Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerse segura"
                />

                <Card>
                    <CardHeader>
                        <CardTitle>Actualizar Contraseña</CardTitle>
                        <CardDescription>
                            Cambia tu contraseña actual
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <Label for="current_password">Contraseña Actual</Label>
                                <Input
                                    id="current_password"
                                    v-model="form.current_password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                />
                            </div>

                            <div>
                                <Label for="password">Nueva Contraseña</Label>
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <div>
                                <Label for="password_confirmation">Confirmar Contraseña</Label>
                                <Input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>

                            <Button type="submit" :disabled="form.processing">
                                Actualizar Contraseña
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
