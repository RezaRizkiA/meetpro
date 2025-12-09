<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';
import {
    ArrowRight,
    Users,
    MessageSquare,
    LayoutTemplate
} from 'lucide-vue-next';

const props = defineProps({
    client: Object,
    expertises: Array
});

// Ambil Global Assets untuk Fallback (Jika client belum upload gambar)
const page = usePage();
const assets = page.props.assets;

</script>

<template>

    <Head :title="`${client.banner_title} - ${client.author_name}`" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans">

            <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div
                    class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
                    <div class="grid lg:grid-cols-2 min-h-[600px]">

                        <div class="p-8 md:p-16 flex flex-col justify-center order-2 lg:order-1">
                            <div class="mb-6 flex items-center gap-2">
                                <span
                                    class="bg-violet-50 text-violet-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                    <LayoutTemplate class="w-3 h-3" />
                                    {{ client.section_hero || 'Our Program' }}
                                </span>
                            </div>

                            <h1
                                class="font-display text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 mb-6 leading-tight">
                                {{ client.banner_title }}
                            </h1>

                            <p class="text-lg text-slate-500 mb-8 leading-relaxed whitespace-pre-line">
                                {{ client.banner_desc }}
                            </p>

                            <div class="flex items-center gap-4 mt-auto pt-8 border-t border-slate-100">
                                <img :src="client.photo_url || assets.userPlaceholderUrl" alt="Author"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md">
                                <div>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Managed By</p>
                                    <p class="text-slate-900 font-bold">{{ client.author_name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative h-64 lg:h-auto order-1 lg:order-2 overflow-hidden bg-slate-900">
                            <img :src="client.banner_url || assets.bannerPlaceholderUrl" alt="Banner"
                                class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-overlay hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent"></div>

                            <div
                                class="absolute bottom-8 right-8 bg-white/90 backdrop-blur-md px-6 py-3 rounded-2xl shadow-lg border border-white/20 hidden md:block">
                                <div class="flex items-center gap-3">
                                    <div class="flex -space-x-2">
                                        <div class="w-8 h-8 rounded-full bg-violet-500 border-2 border-white"></div>
                                        <div class="w-8 h-8 rounded-full bg-fuchsia-500 border-2 border-white"></div>
                                        <div class="w-8 h-8 rounded-full bg-blue-500 border-2 border-white"></div>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 font-bold uppercase">Trusted By</p>
                                        <p class="text-sm font-bold text-slate-900">Many Professionals</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                <div class="flex justify-between items-end mb-10">
                    <div>
                        <h2 class="font-display text-3xl font-bold text-slate-900">Available Programs</h2>
                        <p class="text-slate-500 mt-2">Choose a category to find the right expert.</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <Link v-for="expertise in expertises" :key="expertise.id"
                        :href="route('list_conselor', [client.slug_page, expertise.slug])"
                        class="group bg-white rounded-3xl p-2 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="relative h-48 overflow-hidden rounded-[1.2rem]">
                            <img :src="assets.blogPlaceholderUrl" alt="Expertise"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60">
                            </div>
                            <div class="absolute top-4 left-4">
                                <span
                                    class="bg-white/90 backdrop-blur text-slate-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                    {{ expertise.name }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                                    <MessageSquare class="w-4 h-4" />
                                    <span>{{ expertise.parent?.name || 'Core' }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 text-xs font-semibold text-violet-600 bg-violet-50 px-2 py-1 rounded-lg">
                                    <Users class="w-4 h-4" />
                                    <span>{{ expertise.experts_count }} Experts</span>
                                </div>
                            </div>

                            <h3
                                class="text-xl font-bold text-slate-900 mb-2 group-hover:text-violet-600 transition-colors">
                                {{ expertise.name }} Specialists
                            </h3>

                            <div class="flex items-center text-violet-600 font-bold text-sm mt-4">
                                Find Expert
                                <ArrowRight class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </div>
                    </Link>
                </div>
            </section>

            <section class="mt-20">
                <div class="py-24 px-4 text-center relative overflow-hidden"
                    :style="{ backgroundColor: client.color || '#1e293b' }">

                    <div class="absolute top-0 left-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2">
                    </div>
                    <div
                        class="absolute bottom-0 right-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl translate-y-1/2">
                    </div>

                    <div class="relative z-10 max-w-4xl mx-auto">
                        <div class="inline-flex p-4 bg-white rounded-2xl shadow-xl mb-8">
                            <img :src="client.logo_url || assets.clientLogoPlaceholderUrl" alt="Logo"
                                class="h-12 w-auto object-contain">
                        </div>

                        <h2 class="font-display text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">
                            {{ client.banner_footer }}
                        </h2>

                        <p class="text-lg text-white/80 max-w-2xl mx-auto mb-10 leading-relaxed">
                            {{ client.banner_footer_desc }}
                        </p>

                        <div class="flex justify-center gap-4">
                            <a href="/"
                                class="px-8 py-4 bg-white text-slate-900 rounded-xl font-bold hover:bg-slate-100 transition-colors shadow-lg shadow-black/20">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </MainLayout>
</template>