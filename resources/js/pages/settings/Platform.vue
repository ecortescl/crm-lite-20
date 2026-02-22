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
import { X, AlertCircle } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { Alert, AlertDescription } from '@/components/ui/alert';

const props = defineProps<{
    platformName: string;
    platformLogo: string;
    companyName: string;
    companyRut: string;
    companyGiro: string;
    companyAddress: string;
    companyEmail: string;
    companyPhone: string;
    companyLogo: string;
    taxRate: number;
}>();

const page = usePage();
const errorMessage = computed(() => page.props.flash?.error as string | undefined);

const form = useForm({
    platform_name: props.platformName,
    platform_logo: null as File | null,
    company_name: props.companyName,
    company_rut: props.companyRut,
    company_giro: props.companyGiro,
    company_address: props.companyAddress,
    company_email: props.companyEmail,
    company_phone: props.companyPhone,
    company_logo: null as File | null,
    tax_rate: props.taxRate,
});

const logoPreview = ref<string | null>(
    props.platformLogo || null
);

const companyLogoPreview = ref<string | null>(
    props.companyLogo || null
);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.platform_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleCompanyLogoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.company_logo = file;
        companyLogoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('platform.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            form.reset('platform_logo', 'company_logo');
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
                            <!-- Mensaje de error general -->
                            <Alert v-if="errorMessage" variant="destructive">
                                <AlertCircle class="h-4 w-4" />
                                <AlertDescription>
                                    {{ errorMessage }}
                                </AlertDescription>
                            </Alert>

                            <div>
                                <Label for="platform_name">Nombre de la Plataforma</Label>
                                <Input
                                    id="platform_name"
                                    v-model="form.platform_name"
                                    type="text"
                                    required
                                    :class="{ 'border-destructive': form.errors.platform_name }"
                                />
                                <p v-if="form.errors.platform_name" class="text-xs text-destructive mt-1">
                                    {{ form.errors.platform_name }}
                                </p>
                                <p v-else class="text-xs text-muted-foreground mt-1">
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
                                    :class="{ 'border-destructive': form.errors.platform_logo }"
                                />
                                <p v-if="form.errors.platform_logo" class="text-xs text-destructive mt-1">
                                    {{ form.errors.platform_logo }}
                                </p>
                                <p v-else class="text-xs text-muted-foreground mt-1">
                                    Formatos: JPG, PNG, GIF, SVG. Máximo 2MB
                                </p>
                            </div>

                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                            </Button>
                        </form>
                    </CardContent>
                </Card>

                <!-- Información de la Empresa -->
                <Card>
                    <CardHeader>
                        <CardTitle>Información de la Empresa</CardTitle>
                        <CardDescription>
                            Datos que aparecerán en las cotizaciones y documentos
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <Label for="company_name">Nombre de la Empresa</Label>
                                    <Input
                                        id="company_name"
                                        v-model="form.company_name"
                                        type="text"
                                    />
                                </div>
                                <div>
                                    <Label for="company_rut">RUT</Label>
                                    <Input
                                        id="company_rut"
                                        v-model="form.company_rut"
                                        type="text"
                                        placeholder="76.123.456-7"
                                    />
                                </div>
                                <div class="col-span-2">
                                    <Label for="company_giro">Giro</Label>
                                    <Input
                                        id="company_giro"
                                        v-model="form.company_giro"
                                        type="text"
                                    />
                                </div>
                                <div class="col-span-2">
                                    <Label for="company_address">Dirección</Label>
                                    <Input
                                        id="company_address"
                                        v-model="form.company_address"
                                        type="text"
                                    />
                                </div>
                                <div>
                                    <Label for="company_email">Email</Label>
                                    <Input
                                        id="company_email"
                                        v-model="form.company_email"
                                        type="email"
                                    />
                                </div>
                                <div>
                                    <Label for="company_phone">Teléfono</Label>
                                    <Input
                                        id="company_phone"
                                        v-model="form.company_phone"
                                        type="text"
                                    />
                                </div>
                                <div>
                                    <Label for="tax_rate">Impuesto (%)</Label>
                                    <Input
                                        id="tax_rate"
                                        v-model.number="form.tax_rate"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                    />
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Porcentaje de impuesto para cotizaciones (ej: 19 para IVA)
                                    </p>
                                </div>
                            </div>

                            <div>
                                <Label for="company_logo">Logo de la Empresa</Label>
                                
                                <div v-if="companyLogoPreview" class="mt-2 mb-4">
                                    <div class="relative inline-block">
                                        <img 
                                            :src="companyLogoPreview" 
                                            alt="Logo Empresa" 
                                            class="h-16 w-auto rounded border"
                                        />
                                        <Button
                                            type="button"
                                            variant="destructive"
                                            size="icon"
                                            class="absolute -top-2 -right-2 h-6 w-6"
                                            @click="companyLogoPreview = null; form.company_logo = null"
                                        >
                                            <X class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>

                                <Input
                                    id="company_logo"
                                    type="file"
                                    accept="image/*"
                                    @change="handleCompanyLogoChange"
                                />
                                <p class="text-xs text-muted-foreground mt-1">
                                    Logo que aparecerá en las cotizaciones. Formatos: JPG, PNG, GIF, SVG. Máximo 2MB
                                </p>
                            </div>

                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
