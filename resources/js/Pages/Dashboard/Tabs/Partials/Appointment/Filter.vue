<script setup>
import { Search } from "lucide-vue-next";

const props = defineProps({
    activeFilter: String,
    searchQuery: String,
});

const emit = defineEmits(['update:activeFilter', 'update:searchQuery']);

const filters = [
    { id: 'all', label: 'All' },
    { id: 'pending', label: 'Pending Request' },
    { id: 'upcoming', label: 'Upcoming' },
    { id: 'completed', label: 'History' },
];
</script>

<template>
    <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md pt-1 pb-4 mb-6 border-b border-slate-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-1 overflow-x-auto no-scrollbar py-1">
                <button v-for="filter in filters" :key="filter.id" 
                    @click="$emit('update:activeFilter', filter.id)"
                    class="px-4 py-2 rounded-full text-sm font-semibold transition-all whitespace-nowrap" 
                    :class="activeFilter === filter.id
                        ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20'
                        : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'">
                    {{ filter.label }}
                </button>
            </div>

            <div class="relative w-full md:w-64 shrink-0">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                <input 
                    :value="searchQuery"
                    @input="$emit('update:searchQuery', $event.target.value)"
                    type="text" 
                    placeholder="Search name..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
            </div>
        </div>
    </div>
</template>