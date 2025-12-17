<script setup>
import { ref, computed } from "vue";
import { CreditCard } from "lucide-vue-next";

// Import Komponen Card yang baru dibuat
import TransactionCard from "./Partials/Transaction/Card.vue";

const props = defineProps({
    transactions: Array, // Karena dari Index.vue dikirim transactions.data (Array)
    isExpert: Boolean,
    isAdmin: Boolean,    // Pastikan terima props ini
});

// State Filter (Opsional, untuk pengembangan selanjutnya)
const activeFilter = ref("all"); // 'all', 'in', 'out'

// Computed Filter Logic
const filteredTransactions = computed(() => {
    if (!props.transactions) return [];
    
    // Jika nanti ingin filter berdasarkan Income/Expense
    // return props.transactions.filter(...)
    
    return props.transactions;
});
</script>

<template>
    <div class="min-h-[400px]">
        
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-700">Transaction History</h3>
            <span class="text-xs font-bold bg-slate-100 text-slate-500 px-3 py-1 rounded-full">
                {{ filteredTransactions.length }} Records
            </span>
        </div>

        <div v-if="filteredTransactions.length > 0" class="space-y-3">
            <TransactionCard 
                v-for="transaction in filteredTransactions" 
                :key="transaction.id"
                :transaction="transaction"
                :is-expert="isExpert"
                :is-admin="isAdmin" 
            />
        </div>

        <div v-else class="flex flex-col items-center justify-center py-16 text-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                <CreditCard class="w-8 h-8 text-slate-300" />
            </div>
            <h3 class="text-slate-900 font-bold text-lg">No transactions yet</h3>
            <p class="text-slate-500 text-sm max-w-xs mx-auto mt-1">
                Your financial records will appear here once you make or receive a payment.
            </p>
        </div>

    </div>
</template>