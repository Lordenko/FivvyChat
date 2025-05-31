import './bootstrap';
import 'nprogress/nprogress.css'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link, router } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import Layout from './Layouts/Layout.vue'
import NProgress from 'nprogress'


createInertiaApp({
    title: (title) => `My App - ${title}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = page.default.layout || Layout
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('Head', Head)
            .component('Link', Link)
            .mount(el)
    },
    progress: false,
})

let showingProgress = false

NProgress.configure({
    showSpinner: true,     // Прибрати кружечок праворуч
    trickleSpeed: 300,      // Швидкість поступового прогресу (ms)
    minimum: 0.01,           // Мінімальна початкова довжина (0.1 = 10%)
    easing: 'ease',         // CSS easing
    speed: 100              // Тривалість анімації закінчення (ms)
})

router.on('start', (event) => {
    if (!event.detail.visit.data?.skipProgress) {
        showingProgress = true
        NProgress.start()
    }
})

router.on('finish', () => {
    if (showingProgress) {
        NProgress.done()
        showingProgress = false
    }
})
