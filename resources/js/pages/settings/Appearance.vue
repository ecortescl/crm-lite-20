<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const { appearance, updateAppearance } = useAppearance();

const themes = [
    { value: 'light', label: 'Claro', description: 'Tema claro' },
    { value: 'dark', label: 'Oscuro', description: 'Tema oscuro' },
    { value: 'system', label: 'Sistema', description: 'Usar configuración del sistema' },
];
</script>

<template>
    <AppLayout title="Apariencia">
        <h1 class="sr-only">Configuración de Apariencia</h1>

        <SettingsLayout>
            <div class="space-y-6">
                <Heading
                    title="Apariencia"
                    description="Personaliza la apariencia de la aplicación"
                />

                <Card>
                    <CardHeader>
                        <CardTitle>Tema</CardTitle>
                        <CardDescription>
                            Selecciona el tema de la aplicación
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <RadioGroup :model-value="appearance" @update:model-value="updateAppearance">
                            <div v-for="theme in themes" :key="theme.value" class="flex items-center space-x-3 space-y-0">
                                <RadioGroupItem :value="theme.value" :id="theme.value" />
                                <Label :for="theme.value" class="font-normal cursor-pointer">
                                    <div class="font-medium">{{ theme.label }}</div>
                                    <div class="text-sm text-muted-foreground">{{ theme.description }}</div>
                                </Label>
                            </div>
                        </RadioGroup>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
