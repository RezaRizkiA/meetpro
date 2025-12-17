<script setup>
import { ArrowUpRight, ArrowDownLeft, DollarSign } from "lucide-vue-next";

const props = defineProps({
    transaction: Object,
    isExpert: Boolean,
    isAdmin: Boolean,
});

const formatCurrency = (amount) => new Intl.NumberFormat("id-ID").format(amount || 0);
const formatDate = (dateString) => new Date(dateString).toLocaleDateString("en-US", {
    year: 'numeric', month: 'short', day: 'numeric'
});
</script>

<template>
    <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:border-violet-100 transition-all hover:shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                :class="isAdmin 
                    ? 'bg-slate-100 text-slate-600' 
                    : (isExpert ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600')">
                
                <DollarSign v-if="isAdmin" class="w-5 h-5" />
                <ArrowDownLeft v-else-if="isExpert" class="w-5 h-5" />
                <ArrowUpRight v-else class="w-5 h-5" />
            </div>

            <div class="min-w-0">
                <h4 class="font-bold text-slate-900 text-sm truncate">
                    <template v-if="isAdmin">
                        <span class="text-violet-600">{{ transaction.user?.name || 'Unknown' }}</span>
                        <span class="text-slate-400 mx-1 text-xs">paid</span>
                        <span>{{ transaction.appointment?.expert?.user?.name || 'Unknown' }}</span>
                    </template>
                    
                    <template v-else-if="isExpert">
                        Received from {{ transaction.user?.name || 'Client' }}
                    </template>
                    
                    <template v-else>
                        Payment to {{ transaction.appointment?.expert?.user?.name || 'Expert' }}
                    </template>
                </h4>

                <p class="text-xs text-slate-500 mt-0.5 truncate">
                    {{ formatDate(transaction.created_at) }} • #{{ transaction.invoice_id || transaction.id }}
                </p>
            </div>
        </div>

        <div class="text-right shrink-0 ml-4">
            <span class="block font-bold text-sm sm:text-base" 
                :class="isAdmin 
                    ? 'text-slate-900' 
                    : (isExpert ? 'text-green-600' : 'text-slate-900')">
                <span v-if="!isAdmin && isExpert">+</span>
                Rp {{ formatCurrency(transaction.amount) }}
            </span>
            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md uppercase mt-1"
                :class="transaction.status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600'">
                {{ transaction.status }}
            </span>
        </div>
    </div>
</template>