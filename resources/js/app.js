import "../css/app.css";
import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "ziggy-js";
import VCalendar from "v-calendar";
import "v-calendar/dist/style.css";
import PageTransition from "./Components/PageTransition.vue";

createInertiaApp({
    title: (title) => `${title} - KeyPerson`,
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        // Apply PageTransition as default layout if no layout is set
        if (!page.default.layout) {
            page.default.layout = PageTransition;
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VCalendar, {})
            .mount(el);
    },
    progress: {
        color: "#3b82f6", // Blue untuk konsistensi dengan design
        showSpinner: false, // Kita pakai skeleton sendiri
    },
});
