<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import MainLayout from '../../../Layouts/MainLayout.vue';
import {
    Clock,
    Copy,
    Check,
    Download,
    ArrowLeft,
    RefreshCw,
    ShieldCheck
} from 'lucide-vue-next';

const props = defineProps({
    transaction: Object,
    qrCodeImage: String, // Base64 String dari Controller
});

// --- HELPER FORMATTING ---
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

// --- COUNTDOWN TIMER LOGIC ---
const timeLeft = ref('');
let timerInterval = null;

const startTimer = () => {
    const expiredDate = new Date(props.transaction.expired_date).getTime();

    timerInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = expiredDate - now;

        if (distance < 0) {
            clearInterval(timerInterval);
            timeLeft.value = "EXPIRED";
            // Optional: Auto reload or redirect
        } else {
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            timeLeft.value = `${hours}h ${minutes}m ${seconds}s`;
        }
    }, 1000);
};

onMounted(() => startTimer());
onUnmounted(() => clearInterval(timerInterval));

// --- COPY TO CLIPBOARD ---
const copied = ref(false);
const copyToClipboard = () => {
    navigator.clipboard.writeText(props.transaction.paymentNo);
    copied.value = true;
    setTimeout(() => copied.value = false, 2000);
};

// --- CHECK STATUS ---
const isChecking = ref(false);
const checkStatus = () => {
    isChecking.value = true;
    router.reload({
        onFinish: () => isChecking.value = false
    });
};
</script>

<template>

    <Head title="Payment Details" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-24 pb-20">
            <div class="max-w-xl mx-auto px-4">

                <div
                    class="bg-white rounded-3xl shadow-2xl shadow-slate-200/60 overflow-hidden border border-slate-100 relative">

                    <div class="h-2 bg-gradient-to-r from-violet-500 to-fuchsia-500"></div>

                    <div class="p-8 text-center">

                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-orange-50 rounded-full mb-6 animate-pulse">
                            <Clock class="w-8 h-8 text-orange-500" />
                        </div>

                        <h1 class="font-display text-2xl font-bold text-slate-900 mb-2">Payment Pending</h1>
                        <p class="text-slate-500 text-sm mb-6">Please complete your payment before</p>

                        <div
                            class="inline-block bg-orange-100 text-orange-700 font-mono font-bold text-lg px-4 py-2 rounded-xl mb-8 border border-orange-200">
                            {{ timeLeft }}
                        </div>

                        <div class="border-t border-b border-slate-100 py-6 mb-8">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-2">Total Amount</p>
                            <h2 class="text-3xl font-bold text-slate-900">{{ formatCurrency(transaction.total) }}</h2>
                        </div>

                        <div v-if="transaction.via === 'QRIS'" class="space-y-6">
                            <p class="text-sm font-medium text-slate-600">Scan QRIS to Pay</p>
                            <div class="p-4 bg-white border-2 border-slate-900 rounded-2xl inline-block shadow-sm">
                                <img :src="qrCodeImage" alt="QRIS Code" class="w-48 h-48 object-contain">
                            </div>

                            <div>
                                <a :href="qrCodeImage" :download="`QRIS-${transaction.trx_id}.png`"
                                    class="inline-flex items-center gap-2 text-sm font-bold text-violet-600 hover:text-violet-800 bg-violet-50 px-4 py-2 rounded-xl hover:bg-violet-100 transition-colors">
                                    <Download class="w-4 h-4" /> Download QR Image
                                </a>
                            </div>
                        </div>

                        <div v-else class="text-left bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase">{{ transaction.via }}
                                    Number</span>
                                <img v-if="transaction.channel_logo" :src="transaction.channel_logo"
                                    class="h-4 object-contain">
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <div
                                    class="font-mono text-xl md:text-2xl font-bold text-slate-900 tracking-wide truncate">
                                    {{ transaction.paymentNo }}
                                </div>
                                <button @click="copyToClipboard"
                                    class="shrink-0 flex items-center justify-center w-10 h-10 bg-white border border-slate-200 rounded-xl hover:border-violet-300 hover:text-violet-600 transition-all shadow-sm active:scale-95">
                                    <Check v-if="copied" class="w-5 h-5 text-green-500" />
                                    <Copy v-else class="w-5 h-5" />
                                </button>
                            </div>
                            <p v-if="copied" class="text-right text-xs text-green-600 font-bold mt-1">Copied to
                                clipboard!</p>
                        </div>

                    </div>

                    <div class="bg-slate-50 p-6 border-t border-slate-100 flex flex-col gap-3">
                        <button @click="checkStatus" :disabled="isChecking"
                            class="w-full py-3.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg flex items-center justify-center gap-2 disabled:opacity-70">
                            <RefreshCw class="w-4 h-4" :class="{ 'animate-spin': isChecking }" />
                            Check Payment Status
                        </button>

                        <Link :href="route('profile')"
                            class="w-full py-3.5 text-slate-500 font-bold text-sm hover:text-slate-800 hover:bg-slate-200 rounded-xl transition-all text-center">
                            I'll do it later
                        </Link>
                    </div>

                </div>

                <div class="mt-8 text-center">
                    <div
                        class="inline-flex items-center gap-2 text-xs text-slate-400 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100">
                        <ShieldCheck class="w-4 h-4 text-green-500" />
                        <span>Secured by iPaymu & Escrow System</span>
                    </div>
                </div>

            </div>
        </div>
    </MainLayout>
</template>