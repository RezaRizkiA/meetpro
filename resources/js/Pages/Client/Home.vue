<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';
import {
    ArrowRight,
    Users,
    Quote,
    CheckCircle,
    LayoutTemplate
} from 'lucide-vue-next';

const props = defineProps({
    client: Object,
    expertises: Array
});

// Ambil Assets Global
const page = usePage();
const assets = page.props.assets;

// Helper untuk background branding dinamis
const getBrandStyle = (opacity = 1) => {
    const color = props.client.color || '#7c3aed'; // Default Violet
    return {
        backgroundColor: color,
        // Jika butuh variasi style lain berbasis warna client
    };
};
</script>

<template>

    <Head :title="`${client.banner_title} - ${client.author_name}`" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans">

            <section class="relative h-[600px] lg:h-[700px] flex items-center justify-center overflow-hidden">

                <div class="absolute inset-0 z-0">
                    <img :src="client.banner_url || assets.bannerPlaceholderUrl" alt="Banner"
                        class="w-full h-full object-cover transition-transform duration-1000 hover:scale-105" />
                    <div class="absolute inset-0 bg-linear-to-r from-slate-900/90 via-slate-900/80 to-slate-900/40">
                    </div>
                    <div class="absolute inset-0 bg-linear-to-t from-slate-50 via-transparent to-transparent"></div>
                </div>

                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-16">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">

                        <div class="text-white space-y-8 animate-fade-in-up">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold uppercase tracking-wider text-violet-200">
                                <LayoutTemplate class="w-3 h-3" />
                                {{ client.section_hero || 'Featured Program' }}
                            </div>

                            <h1
                                class="font-display text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight tracking-tight">
                                {{ client.banner_title }}
                            </h1>

                            <p
                                class="text-lg md:text-xl text-slate-300 max-w-xl leading-relaxed border-l-4 border-white/20 pl-6">
                                {{ client.banner_desc }}
                            </p>

                            <div
                                class="flex items-center gap-4 bg-white/10 backdrop-blur-lg p-4 rounded-2xl border border-white/10 max-w-md hover:bg-white/20 transition-colors cursor-default">
                                <div class="relative">
                                    <img :src="client.photo_url || assets.userPlaceholderUrl" alt="Manager"
                                        class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-md">
                                    <div
                                        class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1 border-2 border-slate-900">
                                        <CheckCircle class="w-3 h-3 text-white" />
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-0.5">Program
                                        Managed By</p>
                                    <p class="text-white font-bold text-lg">{{ client.author_name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="hidden lg:block relative">
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full blur-[100px] opacity-40 mix-blend-screen animate-pulse"
                                :style="getBrandStyle()">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-20 -mt-20 relative z-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                    <div>
                        <h2 class="font-display text-3xl font-bold text-slate-900">Available Programs</h2>
                        <p class="text-slate-500 mt-2 text-lg">Select a category to explore professional experts.</p>
                    </div>
                    <div class="h-1.5 w-24 rounded-full" :style="getBrandStyle()"></div>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <Link v-for="(expertise, index) in expertises" :key="expertise.id"
                        :href="route('list_conselor', [client.slug_page, expertise.slug])"
                        class="group relative bg-white rounded-[2rem] p-3 border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-violet-200/50 hover:-translate-y-2 transition-all duration-500 flex flex-col h-full"
                        :class="{ 'lg:mt-8': index % 2 !== 0 }">
                        <div class="relative h-56 overflow-hidden rounded-[1.5rem]">
                            <img :src="assets.blogPlaceholderUrl" alt="Expertise"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent">
                            </div>

                            <div class="absolute top-4 left-4">
                                <span
                                    class="bg-white/90 backdrop-blur-md text-slate-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                    <Users class="w-3 h-3" />
                                    {{ expertise.experts_count }} Experts
                                </span>
                            </div>

                            <div class="absolute bottom-4 left-4 right-4">
                                <p class="text-xs text-white/80 font-bold uppercase tracking-wider mb-1">
                                    {{ expertise.parent?.name || 'Specialization' }}
                                </p>
                                <h3
                                    class="text-2xl font-bold text-white leading-tight group-hover:text-violet-200 transition-colors">
                                    {{ expertise.name }}
                                </h3>
                            </div>
                        </div>

                        <div class="p-5 mt-auto flex justify-between items-center">
                            <span
                                class="text-sm font-medium text-slate-500 group-hover:text-slate-900 transition-colors">
                                View Specialists
                            </span>
                            <div
                                class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-violet-600 group-hover:text-white transition-all duration-300">
                                <ArrowRight class="w-5 h-5" />
                            </div>
                        </div>
                    </Link>
                </div>
            </section>

            <section class="py-20 lg:py-28 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto rounded-[3rem] overflow-hidden relative shadow-2xl">
                    <div class="absolute inset-0" :style="getBrandStyle()"></div>

                    <div class="absolute inset-0 bg-[url('/assets/images/backgrounds/line-bg.svg')] opacity-20"></div>
                    <div
                        class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-20">
                    </div>

                    <div class="relative z-10 px-8 py-16 md:p-20 text-center">
                        <div
                            class="inline-flex p-5 bg-white rounded-3xl shadow-xl mb-10 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                            <img :src="client.logo_url || assets.clientLogoPlaceholderUrl" alt="Logo"
                                class="h-16 w-auto object-contain">
                        </div>

                        <h2
                            class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8 leading-tight">
                            {{ client.banner_footer }}
                        </h2>

                        <div class="flex justify-center mb-10">
                            <div class="relative">
                                <Quote class="absolute -top-4 -left-6 w-8 h-8 text-white/30 transform -scale-x-100" />
                                <p
                                    class="text-xl md:text-2xl text-white/90 font-light max-w-3xl leading-relaxed italic">
                                    {{ client.banner_footer_desc }}
                                </p>
                                <Quote class="absolute -bottom-4 -right-6 w-8 h-8 text-white/30" />
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="/"
                                class="px-10 py-4 bg-white text-slate-900 rounded-full font-bold text-lg hover:bg-slate-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                                Get Started Now
                            </a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </MainLayout>
</template>

<style scoped>
/* Animasi Masuk */
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>