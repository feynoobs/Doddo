import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { DefineComponent } from 'vue';
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

createInertiaApp({
    resolve: (name: string): DefineComponent => {
        const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        return page;
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia()
        pinia.use(piniaPluginPersistedstate)
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .mount(el);
    },
});