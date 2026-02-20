<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

defineProps<{
    twoFactorEnabled: boolean;
}>();
</script>

<template>
    <AppLayout title="Autenticación de Dos Factores">
        <h1 class="sr-only">Configuración de Autenticación de Dos Factores</h1>

        <SettingsLayout>
            <div class="space-y-6">
                <Heading
                    title="Autenticación de Dos Factores"
                    description="Agrega seguridad adicional a tu cuenta usando autenticación de dos factores"
                />

                <Card>
                    <CardHeader>
                        <CardTitle>Autenticación 2FA</CardTitle>
                        <CardDescription>
                            Protege tu cuenta con un segundo factor de autenticación
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <p class="text-sm text-muted-foreground">
                                La autenticación de dos factores agrega una capa adicional de seguridad a tu cuenta.
                            </p>
                            <Button>
                                {{ twoFactorEnabled ? 'Desactivar' : 'Activar' }} 2FA
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
