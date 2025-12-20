<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue'; // Sesuaikan path layout Anda
import {
    CreditCard, User, Smartphone, Mail, Calendar, Clock,
    ShieldCheck, ChevronRight, AlertCircle, CheckCircle2,
    Users
} from 'lucide-vue-next';

const props = defineProps({
    appointment: Object,
    paymentChannels: Array,
    user: Object
});

// Helper Currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR', 
        minimumFractionDigits: 0,
        maximumFractionDigits: 0 
    }).format(value);
};

// Helper Date
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        weekday: 'short', 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
    });
};

// Form Setup
const form = useForm({
    customer_name: props.user.name || '',
    customer_phone: props.user.phone || '', // Ambil dari user profile jika ada
    customer_email: props.user.email || '',
    paymentMethod: '',  // e.g., 'va', 'qris'
    paymentChannel: '', // e.g., 'bca', 'linkaja'
});

// UI State
const selectedCategory = ref(null);

// Actions
const selectChannel = (methodCode, channelCode) => {
    form.paymentMethod = methodCode;
    form.paymentChannel = channelCode;
};

const submit = () => {
    // REFACTOR: Gunakan route semantic 'payment.store'
    form.post(route('payment.store', props.appointment.id), {
        preserveScroll: true,
        onError: () => {
            // Optional: Scroll to error message
        }
    });
};
</script>

<template>
    <Head title="Complete Payment" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-28 pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-900">Checkout</h1>
                    <p class="text-slate-500 mt-2">Review your booking details and complete payment.</p>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-start">

                    <div class="lg:col-span-5 space-y-6 order-2 lg:order-1">
                        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xl shadow-slate-200/50 sticky top-28">

                            <h3 class="font-display text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <CreditCard class="w-5 h-5 text-violet-500" /> Order Summary
                            </h3>

                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                                <img :src="appointment.expert.user.picture || `https://ui-avatars.com/api/?name=${appointment.expert.user.name}&background=random`"
                                    class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-md">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Expert</p>
                                    <h4 class="font-bold text-slate-900 text-lg leading-tight">{{ appointment.expert.user.name }}</h4>
                                </div>
                            </div>

                            <div class="space-y-4 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div class="flex justify-between items-start text-sm">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <Calendar class="w-4 h-4 text-slate-400" /> Schedule
                                    </div>
                                    <span class="font-bold text-slate-900 text-right w-1/2">{{ formatDate(appointment.date_time) }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <Clock class="w-4 h-4 text-slate-400" /> Duration
                                    </div>
                                    <span class="font-bold text-slate-900">{{ appointment.hours }} Hour(s)</span>
                                </div>

                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <Users class="w-4 h-4 text-slate-400" /> Type
                                    </div>
                                    <span class="font-bold capitalize" 
                                        :class="appointment.type === 'group' ? 'text-blue-600' : 'text-violet-600'">
                                        {{ appointment.type }} Session
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-slate-500 text-sm">Total Amount</span>
                                    <span class="text-3xl font-bold text-slate-900">{{ formatCurrency(appointment.price) }}</span>
                                </div>
                                <p class="text-xs text-slate-400 text-right">Secure payment powered by iPaymu</p>
                            </div>

                        </div>
                    </div>

                    <div class="lg:col-span-7 space-y-6 order-1 lg:order-2">

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-900 text-white text-xs">1</span>
                                Billing Details
                            </h3>
                            
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Full Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <User class="w-4 h-4 text-slate-400" />
                                        </div>
                                        <input v-model="form.customer_name" type="text" readonly
                                            class="block w-full pl-9 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">WhatsApp Number</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Smartphone class="w-4 h-4 text-slate-400" />
                                        </div>
                                        <input v-model="form.customer_phone" type="text" placeholder="0812..."
                                            class="block w-full pl-9 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-violet-500 focus:border-violet-500 transition-all shadow-sm">
                                    </div>
                                    <p v-if="form.errors.customer_phone" class="text-red-500 text-xs mt-1">{{ form.errors.customer_phone }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Receipt</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <Mail class="w-4 h-4 text-slate-400" />
                                        </div>
                                        <input v-model="form.customer_email" type="email" readonly
                                            class="block w-full pl-9 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm">
                            <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-900 text-white text-xs">2</span>
                                Select Payment Method
                            </h3>

                            <div class="space-y-4">
                                <div v-for="category in paymentChannels" :key="category.id"
                                    class="border border-slate-200 rounded-2xl overflow-hidden transition-all duration-300"
                                    :class="selectedCategory === category.id ? 'ring-2 ring-violet-500 bg-violet-50/20' : 'bg-white hover:border-slate-300'">
                                    
                                    <button @click="selectedCategory = selectedCategory === category.id ? null : category.id"
                                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none">
                                        <div class="flex items-center gap-3">
                                            <div class="font-bold text-slate-800 text-sm md:text-base">{{ category.name }}</div>
                                        </div>
                                        <ChevronRight class="w-5 h-5 text-slate-400 transition-transform duration-300"
                                            :class="{ 'rotate-90': selectedCategory === category.id }" />
                                    </button>

                                    <div v-show="selectedCategory === category.id"
                                        class="px-5 pb-5 pt-0 border-t border-slate-100 bg-white">
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4">
                                            <div v-for="channel in category.channels" :key="channel.Code"
                                                @click="selectChannel(category.code, channel.Code)"
                                                class="cursor-pointer border rounded-xl p-4 flex flex-col items-center justify-center gap-3 transition-all duration-200 hover:shadow-md relative h-24"
                                                :class="form.paymentChannel === channel.Code
                                                    ? 'border-violet-500 bg-violet-50 text-violet-700 ring-1 ring-violet-500'
                                                    : 'border-slate-100 bg-slate-50/50 hover:bg-white hover:border-violet-300'">
                                                
                                                <img v-if="channel.Logo" :src="channel.Logo"
                                                    class="h-8 object-contain" :alt="channel.Name">
                                                <span v-else class="text-xs font-bold text-center">{{ channel.Name }}</span>

                                                <div v-if="form.paymentChannel === channel.Code"
                                                    class="absolute top-2 right-2 w-5 h-5 bg-violet-500 rounded-full flex items-center justify-center shadow-sm">
                                                    <CheckCircle2 class="w-3 h-3 text-white" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.errors.paymentChannel"
                                class="mt-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm flex items-center gap-2 border border-red-100">
                                <AlertCircle class="w-4 h-4" /> Please select a payment channel above.
                            </div>

                            <div class="mt-8 p-4 bg-blue-50 text-blue-800 text-xs rounded-xl border border-blue-100 flex items-start gap-3">
                                <ShieldCheck class="w-5 h-5 shrink-0 mt-0.5" />
                                <div>
                                    <p class="font-bold mb-1">Secure Transaction</p>
                                    <p class="opacity-90 leading-relaxed">
                                        Your payment is encrypted. Funds are held safely until the session is completed.
                                    </p>
                                </div>
                            </div>

                            <button @click="submit" :disabled="form.processing || !form.paymentChannel"
                                class="w-full mt-6 py-4 bg-slate-900 text-white rounded-xl font-bold text-lg hover:bg-violet-600 transition-all shadow-xl shadow-slate-900/10 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="form.processing"
                                    class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></span>
                                {{ form.processing ? 'Processing Secure Payment...' : 'Complete Payment' }}
                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </MainLayout>
</template>