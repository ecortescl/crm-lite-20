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
import { X } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    platformName: string;
    platformLogo: string;
}>();

const form = useForm({
    platform_name: props.platformName,
    platform_logo: null as File | null,
});

const logoPreview = ref<string | null>(
    props.platformLogo ? `/storage/${props.platformLogo}` : null
);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.platform_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.patch(route('platform.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset('platform_logo');
        },
    });
};

const deleteLogo = () => {
    form.delete(route('platform.logo.delete'), {
        preserveScroll: true,
        onSuccess: () => {
            logoPreview.value = null;
        },
    });
};
</script>

<template>
    <AppLayout title="Plataforma">
        <h1 class="sr-only">Configuración de Plataforma</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    title="Configuración de Plataforma"
                    description="Administra el nombre y logo de la plataforma"
                />

                <Card>
                    <CardHeader>
                        <CardTitle>Información General</CardTitle>
                        <CardDescription>
                            Configura el nombre y logo de la aplicación
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <Label for="platform_name">Nombre de la Plataforma</Label>
                                <Input
                                    id="platform_name"
                                    v-model="form.platform_name"
                                    type="text"
                                    required
                                />
                                <p class="text-xs text-muted-foreground mt-1">
                                    Este nombre aparecerá en el título y en el sidebar
                                </p>
                            </div>

                            <div>
                                <Label for="platform_logo">Logo de la Plataforma</Label>
                                
                                <div v-if="logoPreview" class="mt-2 mb-4">
                                    <div class="relative inline-block">
                                        <img 
                                            :src="logoPreview" 
                                            alt="Logo" 
                                            class="h-16 w-auto rounded border"
                                        />
                                        <Button
                                            type="button"
                                            variant="destructive"
                                            size="icon"
                                            class="absolute -top-2 -right-2 h-6 w-6"
                                            @click="deleteLogo"
                                        >
                                            <X class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>

                                <Input
                                    id="platform_logo"
                                    type="file"
                                    accept="image/*"
                                    @change="handleFileChange"
                                />
                                <p class="text-xs text-muted-foreground mt-1">
                                    Formatos: JPG, PNG, GIF, SVG. Máximo 2MB
                                </p>
                            </div>

                            <Button type="submit" :disabled="form.processing">
                                Guardar Cambios
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
