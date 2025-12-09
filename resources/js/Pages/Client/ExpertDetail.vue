<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import MainLayout from '../../Layouts/MainLayout.vue';
import {
    ArrowLeft,
    Briefcase,
    Award,
    CheckCircle,
    Share2,
    Star,
    FileText,
    Image as ImageIcon,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    expert: Object,
    backUrl: String // URL halaman sebelumnya dari Controller
});

const page = usePage();
const authUser = page.props.auth.user;

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
};

const safeJsonParse = (data) => {
    try { return typeof data === 'string' ? JSON.parse(data) : (data || []); }
    catch { return []; }
};

const activeTab = ref('about');
const experiences = safeJsonParse(props.expert.experiences);
const licenses = safeJsonParse(props.expert.licenses);
const gallerys = safeJsonParse(props.expert.gallerys);
const socials = safeJsonParse(props.expert.socials);
</script>

<template>

    <Head :title="`${expert.user.name} - Expert Profile`" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-24 pb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-2">
                    <Link :href="backUrl || '/'"
                        class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-violet-600 mb-6 transition-colors">
                        <ArrowLeft class="w-4 h-4 mr-1" />
                        Back to List
                    </Link>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-8 space-y-8">

                        <div
                            class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden">
                            <div
                                class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-violet-600 to-fuchsia-600">
                            </div>

                            <div class="relative flex flex-col md:flex-row gap-6 mt-12">
                                <div
                                    class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-white shrink-0 mx-auto md:mx-0">
                                    <img :src="expert.user.picture_url" :alt="expert.user.name"
                                        class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1 text-center md:text-left pt-2">
                                    <h1 class="text-3xl font-display font-bold text-slate-900 mb-1">{{ expert.user.name
                                        }}</h1>
                                    <p class="text-violet-600 font-bold text-lg mb-4">{{ expert.expertise }}</p>

                                    <div
                                        class="flex flex-wrap justify-center md:justify-start gap-4 text-sm text-slate-500">
                                        <div v-if="expert.price"
                                            class="flex items-center gap-1 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                            <Briefcase class="w-4 h-4 text-slate-400" />
                                            <span>{{ formatCurrency(expert.price) }} / Hour</span>
                                        </div>
                                        <div
                                            class="flex items-center gap-1 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                            <Star class="w-4 h-4 text-yellow-400 fill-yellow-400" />
                                            <span class="font-bold text-slate-700">4.9</span>
                                            <span>(120 Reviews)</span>
                                        </div>
                                    </div>
                                </div>

                                <button
                                    class="absolute top-0 right-0 p-2 text-white/80 hover:text-white transition-colors">
                                    <Share2 class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-2 overflow-x-auto pb-2 custom-scrollbar">
                            <button @click="activeTab = 'about'"
                                class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all"
                                :class="activeTab === 'about' ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100'">
                                Biography
                            </button>
                            <button v-if="experiences.length" @click="activeTab = 'experience'"
                                class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all"
                                :class="activeTab === 'experience' ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100'">
                                Experience
                            </button>
                            <button v-if="licenses.length" @click="activeTab = 'licenses'"
                                class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all"
                                :class="activeTab === 'licenses' ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100'">
                                Certifications
                            </button>
                            <button v-if="gallerys.length" @click="activeTab = 'gallery'"
                                class="px-5 py-2.5 rounded-full text-sm font-bold whitespace-nowrap transition-all"
                                :class="activeTab === 'gallery' ? 'bg-slate-900 text-white shadow-lg' : 'bg-white text-slate-500 hover:bg-slate-100'">
                                Gallery
                            </button>
                        </div>

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm min-h-[300px]">

                            <div v-if="activeTab === 'about'" class="space-y-6">
                                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed"
                                    v-html="expert.biography"></div>
                            </div>

                            <div v-if="activeTab === 'experience'" class="space-y-8">
                                <div class="relative border-l-2 border-slate-100 ml-3 space-y-8 pb-2">
                                    <div v-for="(exp, idx) in experiences" :key="idx" class="relative pl-8">
                                        <div
                                            class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-violet-100 border-2 border-violet-500">
                                        </div>
                                        <h4 class="font-bold text-lg text-slate-900">{{ exp.position }}</h4>
                                        <p class="text-violet-600 font-medium text-sm mb-2">{{ exp.company }} • {{
                                            exp.years }}</p>
                                        <p class="text-slate-500 text-sm">{{ exp.description }}</p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'licenses'" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div v-for="(lic, idx) in licenses" :key="idx"
                                        class="flex items-center p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-violet-200 transition-colors">
                                        <div class="p-3 bg-white rounded-lg shadow-sm mr-4">
                                            <CheckCircle class="w-6 h-6 text-green-500" />
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-slate-900 text-sm mb-1">{{ lic.certification }}
                                            </h5>
                                            <a v-if="lic.attachment" :href="`/storage/${lic.attachment}`"
                                                target="_blank"
                                                class="text-xs text-violet-600 hover:underline flex items-center gap-1">
                                                <FileText class="w-3 h-3" /> View Credential
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'gallery'" class="space-y-6">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div v-for="(gal, idx) in gallerys" :key="idx"
                                        class="group relative aspect-square bg-slate-100 rounded-xl overflow-hidden cursor-zoom-in">
                                        <img :src="`/storage/${gal.photos}`"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="lg:col-span-4 sticky top-24">
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xl shadow-slate-200/50">
                            <h3 class="text-lg font-bold text-slate-900 mb-6">Book an Appointment</h3>

                            <div
                                class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-6">
                                <span class="text-slate-500 text-sm font-medium">Session Rate</span>
                                <span class="text-slate-900 font-bold text-lg">{{ formatCurrency(expert.price) }}</span>
                            </div>

                            <div v-if="authUser && !authUser.calendar_connected"
                                class="p-4 bg-red-50 text-red-700 text-sm rounded-2xl mb-6 border border-red-100 flex items-start gap-3">
                                <AlertCircle class="w-5 h-5 shrink-0 mt-0.5" />
                                <div>
                                    <p class="font-bold mb-1">Calendar Required</p>
                                    <p class="mb-2 text-xs opacity-90">Please connect Google Calendar.</p>
                                    <a :href="route('google.calendar.connect', { redirect: $page.url })"
                                        class="inline-block text-xs font-bold underline hover:text-red-900">Connect
                                        Now</a>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <a :href="route('appointment', expert.id)"
                                    class="block w-full py-4 bg-slate-900 text-white rounded-xl font-bold text-center hover:bg-violet-600 transition-all shadow-lg shadow-slate-900/20">
                                    Select Schedule
                                </a>
                                <button
                                    class="block w-full py-4 bg-white text-slate-700 border border-slate-200 rounded-xl font-bold text-center hover:bg-slate-50 transition-all">
                                    Send Message
                                </button>
                            </div>

                            <div v-if="socials.length" class="mt-8 pt-6 border-t border-slate-100 text-center">
                                <p class="text-xs font-bold text-slate-400 uppercase mb-4 tracking-wider">Connect
                                    Socially</p>
                                <div class="flex justify-center gap-4">
                                    <a v-for="(soc, idx) in socials" :key="idx" :href="soc.value" target="_blank"
                                        class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-violet-50 hover:text-violet-600 transition-colors border border-slate-200 hover:border-violet-200">
                                        <i class="ti" :class="`ti-brand-${soc.key}`"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    height: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
</style>