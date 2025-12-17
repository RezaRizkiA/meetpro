<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { ref } from "vue";
import {
    Calendar,
    Clock,
    MapPin,
    User,
    FileText,
    ArrowLeft,
    CreditCard,
    CheckCircle,
    XCircle,
    AlertCircle,
    RefreshCw,
    Trash2,
} from "lucide-vue-next";

// Import Modal dari lokasi yang ada (Reuse component yang sudah ada)
import ActionModal from "@/Pages/Dashboard/Tabs/Partials/Appointment/ActionModal.vue";
import RescheduleModal from "@/Pages/Dashboard/Tabs/Partials/Appointment/RescheduleModal.vue";

// Menggunakan Persistent Layout
defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointment: Object,
});

// State untuk mengontrol visibilitas Modal
const showActionModal = ref(false);
const showRescheduleModal = ref(false);

// Helper Formatting (Format Tanggal & Rupiah Indonesia)
const formatDate = (date) =>
    new Date(date).toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
const formatTime = (date) =>
    new Date(date).toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
    });
const formatCurrency = (val) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(val);

// Konfigurasi Badge Status
const statusConfig = {
    pending: {
        class: "bg-yellow-100 text-yellow-700",
        label: "Pending Payment",
    },
    need_confirmation: {
        class: "bg-orange-100 text-orange-700",
        label: "Need Confirmation",
    },
    progress: {
        class: "bg-blue-100 text-blue-700",
        label: "In Progress / Confirmed",
    },
    confirmed: { class: "bg-green-100 text-green-700", label: "Confirmed" },
    completed: { class: "bg-slate-100 text-slate-700", label: "Completed" },
    declined: { class: "bg-red-100 text-red-700", label: "Cancelled" },
};

const getStatus = (status) =>
    statusConfig[status] || { class: "bg-gray-100", label: status };

// Fitur Delete Appointment (Khusus Admin)
const deleteForm = useForm({});
const deleteAppointment = () => {
    // Note: Pastikan route 'appointment.destroy' sudah dibuat di web.php
    if (
        confirm(
            "Are you sure you want to permanently delete this appointment? This action cannot be undone."
        )
    ) {
        deleteForm.delete(route("appointment.destroy", props.appointment.id), {
            onSuccess: () => {
                // Redirect otomatis ditangani oleh response controller (back or redirect)
            },
        });
    }
};
</script>

<template>
    <Head :title="`Appointment #${appointment.id}`" />

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <Link
                :href="route('dashboard.appointments')"
                class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 transition-colors"
            >
                <ArrowLeft class="w-4 h-4" />
                <span>Back to Appointments</span>
            </Link>

            <div class="flex items-center gap-3">
                <div
                    class="px-4 py-1.5 rounded-full text-sm font-bold flex items-center gap-2"
                    :class="getStatus(appointment.status).class"
                >
                    <span class="w-2 h-2 rounded-full bg-current"></span>
                    {{ getStatus(appointment.status).label }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
                >
                    <div class="p-6 border-b border-slate-100">
                        <h3
                            class="font-bold text-slate-900 flex items-center gap-2"
                        >
                            <FileText class="w-5 h-5 text-violet-600" />
                            Session Details
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-400 uppercase mb-1"
                                >Topic</label
                            >
                            <p
                                class="text-lg font-medium text-slate-900 leading-relaxed"
                            >
                                {{
                                    appointment.topic ||
                                    "No topic details provided."
                                }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                class="bg-slate-50 p-4 rounded-xl border border-slate-100"
                            >
                                <label
                                    class="block text-xs font-bold text-slate-400 uppercase mb-1"
                                    >Date & Time</label
                                >
                                <div
                                    class="flex items-center gap-2 text-slate-700 font-medium"
                                >
                                    <Calendar class="w-4 h-4 text-violet-500" />
                                    {{ formatDate(appointment.date_time) }}
                                </div>
                                <div
                                    class="flex items-center gap-2 text-slate-700 font-medium mt-1"
                                >
                                    <Clock class="w-4 h-4 text-violet-500" />
                                    {{ formatTime(appointment.date_time) }}
                                </div>
                            </div>

                            <div
                                class="bg-slate-50 p-4 rounded-xl border border-slate-100"
                            >
                                <label
                                    class="block text-xs font-bold text-slate-400 uppercase mb-1"
                                    >Meeting Location</label
                                >
                                <div v-if="appointment.location_url">
                                    <a
                                        :href="appointment.location_url"
                                        target="_blank"
                                        class="flex items-center gap-2 text-violet-600 hover:underline font-medium break-all"
                                    >
                                        <MapPin class="w-4 h-4 shrink-0" />
                                        Link Meeting
                                    </a>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Click to join
                                    </p>
                                </div>
                                <div
                                    v-else
                                    class="flex items-center gap-2 text-slate-500"
                                >
                                    <MapPin class="w-4 h-4" />
                                    <span>Online / Not set yet</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
                >
                    <div
                        class="p-6 border-b border-slate-100 flex justify-between items-center"
                    >
                        <h3
                            class="font-bold text-slate-900 flex items-center gap-2"
                        >
                            <CreditCard class="w-5 h-5 text-emerald-600" />
                            Transaction
                        </h3>
                        <span
                            v-if="appointment.transaction"
                            class="text-xs font-bold px-2 py-1 rounded uppercase"
                            :class="
                                appointment.transaction.status === 'paid'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-slate-100 text-slate-600'
                            "
                        >
                            {{ appointment.transaction.status }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div v-if="appointment.transaction" class="space-y-4">
                            <div
                                class="flex justify-between items-center py-2 border-b border-slate-50"
                            >
                                <span class="text-slate-500">Invoice ID</span>
                                <span
                                    class="font-mono font-medium text-slate-700"
                                    >#{{
                                        appointment.transaction.invoice_id
                                    }}</span
                                >
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-slate-50"
                            >
                                <span class="text-slate-500">Amount</span>
                                <span
                                    class="font-bold text-slate-900 text-lg"
                                    >{{
                                        formatCurrency(
                                            appointment.transaction.amount
                                        )
                                    }}</span
                                >
                            </div>
                        </div>
                        <div v-else class="text-center py-8 text-slate-400">
                            <AlertCircle
                                class="w-8 h-8 mx-auto mb-2 opacity-50"
                            />
                            <p>No transaction data found.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200"
                >
                    <h4
                        class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4"
                    >
                        Booked By (User)
                    </h4>
                    <div class="flex items-center gap-4">
                        <img
                            :src="
                                appointment.user.picture_url ||
                                `https://ui-avatars.com/api/?name=${appointment.user.name}`
                            "
                            class="w-12 h-12 rounded-full object-cover border border-slate-100"
                        />
                        <div>
                            <p class="font-bold text-slate-900">
                                {{ appointment.user.name }}
                            </p>
                            <p class="text-sm text-slate-500">
                                {{ appointment.user.email }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200"
                >
                    <h4
                        class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4"
                    >
                        Expert
                    </h4>
                    <div class="flex items-center gap-4">
                        <img
                            :src="
                                appointment.expert.user.picture_url ||
                                `https://ui-avatars.com/api/?name=${appointment.expert.user.name}`
                            "
                            class="w-12 h-12 rounded-full object-cover border border-slate-100"
                        />
                        <div>
                            <p class="font-bold text-slate-900">
                                {{ appointment.expert.user.name }}
                            </p>
                            <p class="text-sm text-violet-600 font-medium">
                                {{
                                    appointment.expert.expertise?.name ||
                                    "Expert"
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-lg">
                    <h4 class="font-bold text-lg mb-1">Admin Control</h4>
                    <p class="text-slate-400 text-xs mb-4">
                        Manage this session directly.
                    </p>

                    <div class="space-y-3">
                        <button
                            @click="showActionModal = true"
                            class="w-full py-2.5 px-4 bg-violet-600 hover:bg-violet-700 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2"
                        >
                            <CheckCircle class="w-4 h-4" />
                            Update Status
                        </button>

                        <button
                            @click="showRescheduleModal = true"
                            class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-sm font-medium transition-all flex items-center justify-center gap-2"
                        >
                            <RefreshCw class="w-4 h-4" />
                            Reschedule
                        </button>

                        <button
                            @click="deleteAppointment"
                            class="w-full py-2.5 px-4 border border-slate-700 hover:bg-red-900/30 text-red-400 hover:text-red-300 rounded-xl text-sm font-medium transition-all flex items-center justify-center gap-2"
                        >
                            <Trash2 class="w-4 h-4" />
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <ActionModal
            :show="showActionModal"
            :appointment="appointment"
            @close="showActionModal = false"
        />

        <RescheduleModal
            :show="showRescheduleModal"
            :appointment="appointment"
            @close="showRescheduleModal = false"
        />
    </div>
</template>
