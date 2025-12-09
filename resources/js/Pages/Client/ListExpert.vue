<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '../../Layouts/MainLayout.vue';
import {
    Search,
    ArrowLeft,
    User,
    Award,
    Briefcase
} from 'lucide-vue-next';

const props = defineProps({
    client: Object,
    expertise: Object,
    experts: Array
});



// Fitur Search Sederhana (Client-side filtering)
const searchQuery = ref('');

const filteredExperts = computed(() => {
    if (!searchQuery.value) return props.experts;

    return props.experts.filter(expert => {
        const name = expert.user?.name?.toLowerCase() || '';
        const title = expert.expertise?.toLowerCase() || '';
        const query = searchQuery.value.toLowerCase();
        return name.includes(query) || title.includes(query);
    });
});
</script>

<template>
    <Head :title="`${expertise.name} Experts`" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-24 pb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="mb-10">
                    <Link :href="route('home_client', client.slug_page)" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-violet-600 mb-6 transition-colors">
                        <ArrowLeft class="w-4 h-4 mr-1" />
                        Back to {{ client.section_hero || 'Home' }}
                    </Link>
                    
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-violet-100 text-violet-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                    {{ expertise.name }}
                                </span>
                            </div>
                            <h1 class="font-display text-3xl md:text-4xl font-bold text-slate-900">
                                Finds your {{ expertise.name }}
                            </h1>
                        </div>

                        <div class="relative w-full md:w-72">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <Search class="h-5 w-5 text-slate-400" />
                            </div>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                class="block w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-violet-500 focus:border-violet-500 shadow-sm transition-all"
                                placeholder="Search expert name..."
                            >
                        </div>
                    </div>
                </div>

                <div v-if="filteredExperts.length > 0" class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
                    
                    <Link 
                        v-for="expert in filteredExperts" 
                        :key="expert.id"
                        :href="`${route('expert_detail', expert.id)}?back=${encodeURIComponent($page.url)}`"
                        class="group bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center"
                    >
                        <div class="relative w-28 h-28 mb-4">
                            <div class="w-full h-full rounded-full overflow-hidden border-4 border-slate-50 shadow-inner group-hover:border-violet-100 transition-colors">
                                <img 
                                    :src="expert.user?.picture_url" 
                                    :alt="expert.user?.name" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                >
                            </div>
                        </div>

                        <h3 class="font-display font-bold text-lg text-slate-900 mb-1 group-hover:text-violet-600 transition-colors line-clamp-1">
                            {{ expert.user?.name || 'Unknown Expert' }}
                        </h3>
                        
                        <p class="text-xs text-slate-500 font-medium mb-4 line-clamp-2 min-h-[2.5em]">
                            {{ expert.expertise || expertise.name }}
                        </p>

                        <div class="w-full pt-4 border-t border-slate-100 flex justify-between items-center text-sm">
                            <div class="flex items-center text-slate-500">
                                <Briefcase class="w-3.5 h-3.5 mr-1" />
                                <span>Expert</span>
                            </div>
                            <div class="font-bold text-slate-900">
                                IDR {{ Number(expert.price).toLocaleString('id-ID') }}
                            </div>
                        </div>

                        <div class="w-full mt-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold group-hover:bg-violet-600 transition-colors shadow-lg shadow-slate-200">
                            View Profile
                        </div>

                    </Link>

                </div>

                <div v-else class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-slate-300">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <User class="w-8 h-8 text-slate-300" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">No Experts Found</h3>
                    <p class="text-slate-500 text-sm max-w-md text-center">
                        {{ searchQuery ? `We couldn't find any experts matching "${searchQuery}".` : 'There are no experts registered in this category yet.' }}
                    </p>
                    <button v-if="searchQuery" @click="searchQuery = ''" class="mt-4 text-violet-600 font-bold text-sm hover:underline">
                        Clear Search
                    </button>
                </div>

            </div>
        </div>
    </MainLayout>
</template>