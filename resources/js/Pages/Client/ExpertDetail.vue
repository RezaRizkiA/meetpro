<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import MainLayout from '../../Layouts/MainLayout.vue';
import { ArrowLeft, Briefcase, Award, CheckCircle, Share2, Star, Clock, Calendar, ShieldCheck, MapPin } from 'lucide-vue-next';

const props = defineProps({ expert: Object, backUrl: String });

const formatCurrency = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
const safeJsonParse = (data) => { try { return typeof data === 'string' ? JSON.parse(data) : (data || []); } catch { return []; } };

const activeTab = ref('about');
const experiences = safeJsonParse(props.expert.experiences);
const licenses = safeJsonParse(props.expert.licenses);
const gallerys = safeJsonParse(props.expert.gallerys);
const socials = safeJsonParse(props.expert.socials);
</script>

<template>

    <Head :title="`${expert.user.name} - Expert Profile`" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-28 pb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <Link :href="backUrl || '/'"
                    class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-violet-600 mb-8 transition-colors group">
                    <ArrowLeft class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" /> Back to List
                </Link>

                <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                    <div class="lg:col-span-8">
                        <div
                            class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 relative overflow-hidden mb-8">
                            <div class="absolute top-0 right-0 p-6">
                                <button
                                    class="p-2 rounded-full bg-slate-50 text-slate-400 hover:bg-violet-50 hover:text-violet-600 transition-colors">
                                    <Share2 class="w-5 h-5" />
                                </button>
                            </div>

                            <div
                                class="flex flex-col md:flex-row gap-8 items-center md:items-start text-center md:text-left">
                                <div class="relative shrink-0">
                                    <div
                                        class="w-32 h-32 md:w-40 md:h-40 rounded-[2rem] overflow-hidden shadow-lg border-4 border-white">
                                        <img :src="expert.user.picture_url" class="w-full h-full object-cover">
                                    </div>
                                    <div
                                        class="absolute -bottom-3 -right-3 bg-white px-3 py-1 rounded-full shadow-sm border border-slate-100 flex items-center gap-1">
                                        <Star class="w-4 h-4 text-yellow-400 fill-current" />
                                        <span class="text-sm font-bold text-slate-800">5.0</span>
                                    </div>
                                </div>

                                <div class="flex-1 pt-2">
                                    <div class="mb-1 text-violet-600 text-sm font-bold uppercase tracking-wider">{{
                                        expert.expertise }}</div>
                                    <h1 class="text-3xl md:text-4xl font-display font-bold text-slate-900 mb-4">{{
                                        expert.user.name }}</h1>

                                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                                        <div
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 text-sm font-medium text-slate-600 border border-slate-100">
                                            <Briefcase class="w-4 h-4 text-slate-400" /> {{ experiences.length }} Yrs
                                            Exp
                                        </div>
                                        <div
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 text-sm font-medium text-slate-600 border border-slate-100">
                                            <MapPin class="w-4 h-4 text-slate-400" /> Jakarta, ID
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-2 no-scrollbar">
                            <button v-for="tab in ['about', 'experience', 'licenses', 'gallery']" :key="tab"
                                @click="activeTab = tab"
                                class="px-6 py-2.5 rounded-full text-sm font-bold capitalize transition-all whitespace-nowrap"
                                :class="activeTab === tab ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100 border border-transparent hover:border-slate-200'">
                                {{ tab }}
                            </button>
                        </div>

                        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm min-h-[300px]">
                            <div v-if="activeTab === 'about'"
                                class="prose prose-slate max-w-none text-slate-600 leading-loose"
                                v-html="expert.biography"></div>

                            <div v-if="activeTab === 'experience'" class="space-y-8">
                                <div v-for="(exp, idx) in experiences" :key="idx"
                                    class="relative pl-8 border-l-2 border-slate-100 last:border-0 pb-8 last:pb-0">
                                    <div
                                        class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-violet-100 border-2 border-violet-500">
                                    </div>
                                    <h4 class="font-bold text-lg text-slate-900">{{ exp.position }}</h4>
                                    <p class="text-sm font-semibold text-violet-600 mb-2">{{ exp.company }} • {{
                                        exp.years }}</p>
                                    <p class="text-sm text-slate-500 leading-relaxed">{{ exp.description }}</p>
                                </div>
                            </div>

                            <div v-if="activeTab === 'licenses'" class="grid gap-4">
                                <div v-for="(lic, idx) in licenses" :key="idx"
                                    class="flex items-center p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <div class="p-3 bg-white rounded-lg shadow-sm mr-4 text-green-500">
                                        <Award class="w-6 h-6" />
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-900">{{ lic.certification }}</h5>
                                        <a v-if="lic.attachment" :href="`/storage/${lic.attachment}`" target="_blank"
                                            class="text-xs font-bold text-violet-600 hover:underline mt-1 inline-block">View
                                            Certificate</a>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'gallery'" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div v-for="(gal, idx) in gallerys" :key="idx"
                                    class="aspect-square rounded-2xl overflow-hidden bg-slate-100">
                                    <img :src="`/storage/${gal.photos}`"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 sticky top-28">
                        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-2xl shadow-slate-200/60">
                            <div class="mb-6 pb-6 border-b border-slate-100">
                                <p class="text-slate-500 text-sm font-medium mb-1">Session Rate</p>
                                <div class="flex items-end gap-1">
                                    <span class="text-3xl font-display font-bold text-slate-900">{{
                                        formatCurrency(expert.price) }}</span>
                                    <span class="text-slate-400 font-medium mb-1">/ hour</span>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <Clock class="w-4 h-4 text-violet-500" /> <span>60 Minutes Session</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <Calendar class="w-4 h-4 text-violet-500" /> <span>Google Calendar Sync</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <ShieldCheck class="w-4 h-4 text-violet-500" /> <span>Secure Payment</span>
                                </div>
                            </div>

                            <Link :href="`${route('appointment', expert.id)}?back=${encodeURIComponent($page.url)}`"
                                class="block w-full py-4 rounded-xl bg-slate-900 text-white font-bold text-center hover:bg-violet-600 hover:shadow-lg hover:shadow-violet-200 transition-all duration-300">
                                Book Appointment
                            </Link>

                            <p class="text-xs text-center text-slate-400 mt-4">100% Satisfaction Guaranteed</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </MainLayout>
</template>