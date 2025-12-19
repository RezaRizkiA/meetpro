<script setup>
import { Head, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
  Calendar,
  Clock,
  CreditCard,
  Wallet,
  Search,
  ShoppingBag,
  AlertCircle,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
  stats: Object,
});

const formatCurrency = amount => {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount || 0);
};
</script>

<template>
  <Head title="My Dashboard" />

  <div class="space-y-8">
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Welcome Back!</h1>
        <p class="text-slate-500 mt-1">
          Track your learning journey and upcoming sessions.
        </p>
      </div>

      <Link
        :href="route('home')"
        class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-slate-200">
        <Search class="w-4 h-4" />
        Find New Expert
      </Link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div
        class="relative overflow-hidden p-6 rounded-3xl border shadow-sm transition-all duration-300 hover:shadow-md group"
        :class="
          stats.pending_payments > 0
            ? 'bg-yellow-50 border-yellow-200 ring-1 ring-yellow-100'
            : 'bg-white border-slate-200'
        ">
        <div class="flex justify-between items-start relative z-10">
          <div>
            <p
              class="text-sm font-bold uppercase tracking-wider"
              :class="
                stats.pending_payments > 0
                  ? 'text-yellow-700'
                  : 'text-slate-500'
              ">
              Unpaid Booking
            </p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-2">
              {{ stats.pending_payments }}
            </h3>
            <p
              class="text-xs mt-1"
              :class="
                stats.pending_payments > 0
                  ? 'text-yellow-600'
                  : 'text-slate-400'
              ">
              Waiting for payment
            </p>
          </div>
          <div
            class="p-3 rounded-2xl"
            :class="
              stats.pending_payments > 0
                ? 'bg-yellow-100 text-yellow-600'
                : 'bg-slate-50 text-slate-400'
            ">
            <CreditCard class="w-6 h-6" />
          </div>
        </div>

        <Link
          v-if="stats.pending_payments > 0"
          :href="route('dashboard.appointments.index')"
          class="absolute inset-0 z-20"></Link>
      </div>

      <div
        class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden">
        <div
          class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>

        <div class="flex justify-between items-start relative z-10">
          <div>
            <p
              class="text-sm font-bold text-slate-500 uppercase tracking-wider">
              Upcoming
            </p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-2">
              {{ stats.upcoming_sessions }}
            </h3>
            <p class="text-xs text-slate-400 mt-1">Confirmed Sessions</p>
          </div>
          <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
            <Clock class="w-6 h-6" />
          </div>
        </div>
      </div>

      <div
        class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden">
        <div
          class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>

        <div class="flex justify-between items-start relative z-10">
          <div>
            <p
              class="text-sm font-bold text-slate-500 uppercase tracking-wider">
              Total Spent
            </p>
            <h3
              class="text-3xl font-extrabold text-slate-900 mt-2 truncate max-w-[180px]"
              :title="formatCurrency(stats.total_spent)">
              {{ formatCurrency(stats.total_spent) }}
            </h3>
            <p class="text-xs text-slate-400 mt-1">Investment in yourself</p>
          </div>
          <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
            <Wallet class="w-6 h-6" />
          </div>
        </div>
      </div>

      <div
        class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-md group relative overflow-hidden">
        <div
          class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>

        <div class="flex justify-between items-start relative z-10">
          <div>
            <p
              class="text-sm font-bold text-slate-500 uppercase tracking-wider">
              Total Orders
            </p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-2">
              {{ stats.total_bookings }}
            </h3>
            <p class="text-xs text-slate-400 mt-1">All time history</p>
          </div>
          <div class="p-3 bg-violet-50 text-violet-600 rounded-2xl">
            <ShoppingBag class="w-6 h-6" />
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="stats.total_bookings === 0"
      class="bg-linear-to-r from-violet-600 to-indigo-600 rounded-3xl p-8 text-center text-white shadow-xl shadow-violet-200">
      <div class="max-w-2xl mx-auto">
        <h3 class="text-2xl font-bold mb-2">Ready to start learning?</h3>
        <p class="text-violet-100 mb-6">
          Connect with top industry experts and accelerate your career growth
          today.
        </p>
        <Link
          :href="route('home')"
          class="inline-block px-8 py-3 bg-white text-violet-700 font-bold rounded-xl hover:bg-violet-50 transition-colors shadow-sm">
          Browse Experts
        </Link>
      </div>
    </div>
  </div>
</template>
