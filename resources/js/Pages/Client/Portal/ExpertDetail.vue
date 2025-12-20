<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Clock, MapPin, Star, BadgeCheck, MessageSquare } from 'lucide-vue-next';

// Props dari ClientPortalController@show
const props = defineProps({ 
    expert: Object,
    backUrl: String 
});

const formatCurrency = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);

// Helper untuk fallback gambar jika null
const profilePicture = props.expert.user.picture_url || `https://ui-avatars.com/api/?name=${props.expert.user.name}&background=random`;

</script>

<template>
    <Head :title="expert.user.name" />

    <div class="min-h-screen bg-slate-50 font-sans py-8 px-4">
        <div class="max-w-5xl mx-auto">

            <Link :href="backUrl || '/'" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 mb-6 font-medium transition-colors">
                <ArrowLeft class="w-4 h-4" /> Back to Experts
            </Link>

            <div class="grid lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm sticky top-8 text-center">
                        
                        <div class="relative inline-block mx-auto mb-4">
                            <img :src="profilePicture" 
                                class="w-32 h-32 rounded-full object-cover border-4 border-slate-50 shadow-inner">
                            <div class="absolute bottom-1 right-1 bg-green-500 w-6 h-6 rounded-full border-4 border-white" title="Available"></div>
                        </div>
                        
                        <h1 class="text-xl font-bold text-slate-900 flex items-center justify-center gap-1">
                            {{ expert.user.name }}
                            <BadgeCheck class="w-5 h-5 text-blue-500" />
                        </h1>
                        <p class="text-sm text-slate-500 mb-6 font-medium">{{ expert.title || 'Professional Consultant' }}</p>

                        <div class="grid grid-cols-2 gap-2 mb-8 text-left">
                            <div class="bg-slate-50 p-3 rounded-xl">
                                <p class="text-xs text-slate-400 uppercase font-bold">Experience</p>
                                <div class="flex items-center gap-1 font-bold text-slate-700">
                                    <Clock class="w-4 h-4" /> {{ expert.experience_years || 5 }}+ Years
                                </div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl">
                                <p class="text-xs text-slate-400 uppercase font-bold">Session</p>
                                <div class="flex items-center gap-1 font-bold text-slate-700">
                                    <MessageSquare class="w-4 h-4" /> Online
                                </div>
                            </div>
                        </div>

                        <Link :href="route('booking.create', expert.id) + `?back=${$page.url}`" 
                            class="block w-full py-4 bg-slate-900 text-white rounded-xl font-bold text-lg shadow-xl shadow-slate-900/20 hover:bg-violet-600 hover:shadow-violet-200 hover:-translate-y-1 transition-all duration-300">
                            Book Session
                        </Link>
                        
                        <p class="mt-4 text-xs text-slate-400">
                            Investment starts from <span class="font-bold text-slate-900">{{ formatCurrency(expert.price) }}</span>
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                        <h2 class="font-bold text-lg text-slate-900 mb-4 border-b border-slate-100 pb-2">About Me</h2>
                        <div class="prose prose-slate text-sm leading-relaxed text-slate-600 max-w-none">
                            <p v-if="expert.about">{{ expert.about }}</p>
                            <p v-else class="italic text-slate-400">Expert has not added a bio yet.</p>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                        <h2 class="font-bold text-lg text-slate-900 mb-4 border-b border-slate-100 pb-2">Expertise & Topics</h2>
                        
                        <div v-if="expert.skills && expert.skills.length > 0" class="flex flex-wrap gap-2">
                            <span v-for="skill in expert.skills" :key="skill.id" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 text-violet-700 rounded-lg text-xs font-bold border border-violet-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                                {{ skill.name }}
                            </span>
                        </div>
                        <p v-else class="text-slate-400 text-sm italic">No specific topics listed.</p>
                    </div>

                    <div v-if="expert.experiences && expert.experiences.length > 0" class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                        <h2 class="font-bold text-lg text-slate-900 mb-4 border-b border-slate-100 pb-2">Professional Experience</h2>
                        <ul class="space-y-4">
                            <li v-for="(exp, index) in expert.experiences" :key="index" class="flex gap-4">
                                <div class="w-2 h-2 mt-2 rounded-full bg-slate-300 shrink-0"></div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">{{ exp.role || 'Role' }}</p>
                                    <p class="text-xs text-slate-500">{{ exp.company || 'Company' }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </div>
</template>