<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { route } from '@/lib/route';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const props = defineProps<{
    mustVerifyEmail: boolean;
    status?: string;
}>();

const page = usePage();
const user = page.props.auth.user as any;

const form = useForm({
    name: user.name || '',
    email: user.email || '',
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Perfil">
        <h1 class="sr-only">Configuración de Perfil</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    title="Perfil"
                    description="Actualiza tu información de perfil y dirección de correo electrónico"
                />

                <Card>
                    <CardHeader>
                        <CardTitle>Información del Perfil</CardTitle>
                        <CardDescription>
                            Actualiza la información de tu cuenta
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <Label for="name">Nombre</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                />
                            </div>

                            <div>
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                />
                            </div>

                            <Button type="submit" :disabled="form.processing">
                                Guardar
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
