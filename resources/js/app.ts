import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import '../css/app.css';
import '../css/sweetalert2-custom.css';
import { initializeTheme } from './composables/useAppearance';

createInertiaApp({
    title: (title) => {
        // Obtener el nombre de la plataforma desde las props compartidas
        const platformName = (window as any).__INERTIA_SHARED_PROPS__?.name || 'Laravel';
        return title ? `${title} - ${platformName}` : platformName;
    },
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        // Guardar las props compartidas para acceso global
        (window as any).__INERTIA_SHARED_PROPS__ = props.initialPage.props;
        
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
