<script setup>
import { Link } from "@inertiajs/vue3";
import { X, PanelLeftClose, PanelLeftOpen } from "lucide-vue-next";

const props = defineProps({
    isSidebarExpanded: Boolean,
    isMobileMenuOpen: Boolean,
    activeTab: String,
    tabs: Array,
    assets: Object,
});

const emit = defineEmits(['toggleSidebar', 'closeMobileMenu', 'update:activeTab']);
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200 transition-all duration-300 ease-in-out flex flex-col"
        :class="[
            isSidebarExpanded ? 'w-64' : 'w-20',
            isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        ]"
    >
        <div
            class="h-20 flex items-center border-b border-slate-100"
            :class="isSidebarExpanded ? 'px-6 justify-between' : 'justify-center px-0'"
        >
            <Link :href="route('home')" class="flex items-center gap-2 overflow-hidden">
                <img :src="assets.logoSmallUrl" class="h-9 w-auto shrink-0" alt="Logo" />
                <span v-if="isSidebarExpanded" class="font-display font-bold text-xl text-slate-900 whitespace-nowrap">
                    Key<span class="text-violet-600">Person</span>
                </span>
            </Link>
            <button @click="emit('closeMobileMenu')" class="lg:hidden p-1 text-slate-400">
                <X class="w-5 h-5" />
            </button>
        </div>

        <div class="flex-1 py-6 space-y-1 custom-scrollbar" :class="isSidebarExpanded ? 'overflow-y-auto' : 'overflow-visible'">
            <template v-for="tab in tabs" :key="tab.id">
                <button
                    v-if="tab.show"
                    @click="emit('update:activeTab', tab.id); emit('closeMobileMenu')"
                    class="relative w-full flex items-center py-3 transition-all duration-200 group cursor-pointer"
                    :class="[
                        isSidebarExpanded ? 'px-6' : 'justify-center px-2',
                        activeTab === tab.id ? tab.activeClass : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900',
                    ]"
                >
                    <component
                        :is="tab.icon"
                        class="shrink-0 transition-colors duration-200 relative z-10"
                        :class="[
                            isSidebarExpanded ? 'w-5 h-5 mr-3' : 'w-6 h-6',
                            activeTab === tab.id ? '' : 'text-slate-400 group-hover:text-slate-600',
                        ]"
                    />
                    <span v-if="isSidebarExpanded" class="font-medium text-sm whitespace-nowrap">
                        {{ tab.label }}
                    </span>
                    
                    <div v-if="!isSidebarExpanded" class="absolute left-full ml-4 bg-slate-900 text-white text-xs font-bold px-3 py-2 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none z-60 whitespace-nowrap shadow-xl flex items-center -translate-x-2 group-hover:translate-x-0">
                        <div class="absolute -left-1 w-2 h-2 bg-slate-900 rotate-45"></div>
                        {{ tab.label }}
                    </div>
                </button>
            </template>
        </div>

        <div class="p-4 border-t border-slate-100">
            <button
                @click="emit('toggleSidebar')"
                class="hidden lg:flex w-full items-center py-2 transition-colors rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600"
                :class="isSidebarExpanded ? 'px-2' : 'justify-center'"
            >
                <component :is="isSidebarExpanded ? PanelLeftClose : PanelLeftOpen" class="w-5 h-5" />
                <span v-if="isSidebarExpanded" class="ml-3 text-xs font-bold uppercase tracking-wider">
                    Collapse
                </span>
            </button>
        </div>
    </aside>
</template>