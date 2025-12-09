<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import MainLayout from '../../../Layouts/MainLayout.vue';
import {
    CreditCard,
    User,
    Smartphone,
    Mail,
    Calendar,
    Clock,
    ShieldCheck,
    ChevronRight,
    AlertCircle,
    Loader2
} from 'lucide-vue-next';

const props = defineProps({
    appointment: Object,
    paymentChannels: Array,
    user: Object
});

// Helper Currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

// Helper Date
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

// Form Setup
const form = useForm({
    customer_name: props.user.name || '',
    customer_phone: props.user.phone || '',
    customer_email: props.user.email || '',
    paymentMethod: '',  // e.g., 'va', 'qris', 'cstore'
    paymentChannel: '', // e.g., 'bca', 'mandiri', 'alfamart'
});

// State UI
const selectedCategory = ref(null); // Kategori yang sedang dibuka (misal: Virtual Account)

// Method untuk memilih channel
const selectChannel = (methodCode, channelCode) => {
    form.paymentMethod = methodCode;
    form.paymentChannel = channelCode;
};

// Submit
const submit = () => {
    form.post(route('payment.process', props.appointment.id), {
        preserveScroll: true,
    });
};
</script>

<template>

    <Head title="Complete Payment" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-24 pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-5 space-y-6">
                        <div
                            class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xl shadow-slate-200/50 sticky top-24">

                            <h3 class="font-display text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <CreditCard class="w-5 h-5 text-violet-500" /> Order Summary
                            </h3>

                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                <img :src="appointment.expert.user.picture_url"
                                    class="w-14 h-14 rounded-full object-cover border-2 border-slate-100">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Expert</p>
                                    <h4 class="font-bold text-slate-900">{{ appointment.expert.user.name }}</h4>
                                </div>
                            </div>

                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <Calendar class="w-4 h-4 text-slate-400" /> Date
                                    </div>
                                    <span class="font-medium text-slate-900">{{ formatDate(appointment.date_time)
                                        }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <Clock class="w-4 h-4 text-slate-400" /> Duration
                                    </div>
                                    <span class="font-medium text-slate-900">{{ appointment.hours }} Hour(s)</span>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-slate-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-slate-500 text-sm">Total Payment</span>
                                    <span class="text-2xl font-bold text-slate-900">{{ formatCurrency(appointment.price
                                        * appointment.hours) }}</span>
                                </div>
                                <p class="text-xs text-slate-400 text-right">Inc. Tax & Service Fee</p>
                            </div>

                        </div>
                    </div>

                    <div class="lg:col-span-7 space-y-6">

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <h3 class="font-bold text-slate-900 mb-6">Customer Details</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Name</label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <User class="w-4 h-4 text-slate-400" />
                                        </div>
                                        <input v-model="form.customer_name" type="text"
                                            class="block w-full pl-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-violet-500 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Phone</label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Smartphone class="w-4 h-4 text-slate-400" />
                                        </div>
                                        <input v-model="form.customer_phone" type="text"
                                            class="block w-full pl-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-violet-500 transition-all">
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email for
                                        Receipt</label>
                                    <div class="relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Mail class="w-4 h-4 text-slate-400" />
                                        </div>
                                        <input v-model="form.customer_email" type="email"
                                            class="block w-full pl-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-violet-500 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <h3 class="font-bold text-slate-900 mb-6">Select Payment Method</h3>

                            <div class="space-y-4">
                                <div v-for="category in paymentChannels" :key="category.id"
                                    class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                                    :class="selectedCategory === category.id ? 'ring-2 ring-violet-500 bg-violet-50/30' : 'bg-white hover:border-slate-300'">
                                    <button
                                        @click="selectedCategory = selectedCategory === category.id ? null : category.id"
                                        class="w-full flex items-center justify-between p-5 text-left">
                                        <div class="flex items-center gap-3">
                                            <div class="font-bold text-slate-800">{{ category.name }}</div>
                                        </div>
                                        <ChevronRight class="w-5 h-5 text-slate-400 transition-transform duration-300"
                                            :class="{ 'rotate-90': selectedCategory === category.id }" />
                                    </button>

                                    <div v-show="selectedCategory === category.id"
                                        class="px-5 pb-5 pt-0 border-t border-slate-100 bg-white">
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4">
                                            <div v-for="channel in category.channels" :key="channel.Code"
                                                @click="selectChannel(category.code, channel.Code)"
                                                class="cursor-pointer border rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all duration-200 hover:shadow-md relative"
                                                :class="form.paymentChannel === channel.Code
                                                    ? 'border-violet-500 bg-violet-50 text-violet-700'
                                                    : 'border-slate-200 hover:border-violet-300'">
                                                <img v-if="channel.Logo" :src="channel.Logo"
                                                    class="h-6 object-contain mb-1" :alt="channel.Name">
                                                <span v-else class="text-sm font-bold">{{ channel.Name }}</span>

                                                <div v-if="form.paymentChannel === channel.Code"
                                                    class="absolute top-2 right-2 w-4 h-4 bg-violet-500 rounded-full flex items-center justify-center">
                                                    <i class="ti ti-check text-white text-[10px]"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.errors.paymentChannel"
                                class="mt-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm flex items-center gap-2">
                                <AlertCircle class="w-4 h-4" /> Please select a payment channel.
                            </div>

                            <div
                                class="mt-8 p-4 bg-blue-50 text-blue-800 text-xs rounded-xl border border-blue-100 flex items-start gap-3">
                                <ShieldCheck class="w-5 h-5 shrink-0 mt-0.5" />
                                <div>
                                    <p class="font-bold mb-1">Escrow Secured Transaction</p>
                                    <p class="opacity-90 leading-relaxed">
                                        Your payment is held safely. The expert will only receive funds after the
                                        session is completed. You can convert to Escrow manually via email confirmation.
                                    </p>
                                </div>
                            </div>

                            <button @click="submit" :disabled="form.processing || !form.paymentChannel"
                                class="w-full mt-6 py-4 bg-slate-900 text-white rounded-xl font-bold text-lg hover:bg-violet-600 transition-all shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="form.processing"
                                    class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></span>
                                {{ form.processing ? 'Processing...' : 'Pay Now' }}
                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </MainLayout>
</template>