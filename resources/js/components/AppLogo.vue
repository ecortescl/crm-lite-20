<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const page = usePage();
const platformName = page.props.name as string;
const platformLogo = page.props.platformLogo as string;
</script>

<template>
    <div
        v-if="!platformLogo"
        class="flex items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground h-12 w-full px-2"
    >
        <AppLogoIcon class="h-8 w-8 fill-current text-white dark:text-black" />
    </div>
    <div
        v-else
        class="flex items-center justify-center rounded-md overflow-hidden h-12 w-full px-2"
    >
        <img :src="platformLogo" :alt="platformName" class="max-h-full w-full object-contain" />
    </div>
</template>
