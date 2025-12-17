<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import AppointmentTable from './Partials/AppointmentTable.vue'; // <-- Import component Table baru
import { Briefcase, Search, Filter } from 'lucide-vue-next';

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointments: Object,
});
</script>

<template>
    <Head title="Manage Appointments" />

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Appointments Data</h2>
                <p class="text-slate-500 text-sm mt-1">
                    Manage all sessions, status, and schedules efficiently.
                </p>
            </div>
            
            <div class="flex gap-2">
                <button class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">
                    <Filter class="w-4 h-4" />
                    Filter
                </button>
                </div>
        </div>

        <AppointmentTable :appointments="appointments" />

        <div v-if="appointments.links.length > 3" class="flex justify-center mt-6">
            <div class="flex flex-wrap gap-1 items-center justify-center bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
                </div>
        </div>
    </div>
</template>