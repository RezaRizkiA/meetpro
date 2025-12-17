<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Users, Calendar, DollarSign, Clock } from 'lucide-vue-next';

// Menggunakan Persistent Layout
defineOptions({ layout: DashboardLayout });

const props = defineProps({
    stats: Object, // Dari DashboardController::getAdminStats
});

// Helper untuk format currency
const formatCurrency = (val) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(val);
</script>

<template>
    <Head title="Administrator Dashboard" />

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900">Welcome back, Administrator!</h2>
        <p class="text-slate-500 mt-1">Here is what's happening with your platform today.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <Users class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Users</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ stats.total_users }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-violet-50 flex items-center justify-center text-violet-600">
                <Calendar class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Appointments</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ stats.total_appointments }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <DollarSign class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Revenue</p>
                <h3 class="text-xl font-bold text-slate-900 truncate max-w-[150px]" :title="formatCurrency(stats.total_revenue)">
                    {{ formatCurrency(stats.total_revenue) }}
                </h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                <Clock class="w-6 h-6" />
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pending Actions</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ stats.pending_appointments }}</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-64 flex flex-col items-center justify-center text-slate-400">
            <span class="text-sm">Chart Placeholder (Use ApexCharts here)</span>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-64 flex flex-col items-center justify-center text-slate-400">
            <span class="text-sm">Recent Activity Placeholder</span>
        </div>
    </div>

</template>