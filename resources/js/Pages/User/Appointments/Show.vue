<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
  Calendar,
  Clock,
  MapPin,
  Video,
  FileText,
  Hash,
  AlignLeft,
  User,
  Mail,
  ArrowLeft,
  CreditCard,
  CheckCircle2,
  AlertCircle,
  Timer,
  ExternalLink,
  Briefcase,
  Phone
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
  appointment: Object,
});

// --- HELPER FORMATTING ---
const formatDate = date =>
  new Date(date).toLocaleDateString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  });

const formatTime = date =>
  new Date(date).toLocaleTimeString("id-ID", {
    hour: "2-digit",
    minute: "2-digit",
  });

// --- STATUS HELPER ---
const getStatusBadge = status => {
  const config = {
    pending: {
      class: "bg-slate-100 text-slate-600 border-slate-200",
      label: "Waiting Payment",
    },
    need_confirmation: {
      class: "bg-orange-50 text-orange-700 border-orange-200",
      label: "Waiting Confirmation",
    },
    confirmed: {
      class: "bg-violet-50 text-violet-700 border-violet-200",
      label: "Scheduled",
    },
    progress: {
      class: "bg-blue-50 text-blue-700 border-blue-200",
      label: "Live Session",
    },
    completed: {
      class: "bg-emerald-50 text-emerald-700 border-emerald-200",
      label: "Completed",
    },
    declined: {
      class: "bg-red-50 text-red-700 border-red-200",
      label: "Declined",
    },
  };
  return (
    config[status] || { class: "bg-gray-100 text-gray-600", label: status }
  );
};

// --- ACTION LOGIC ---
const proceedToPayment = () => {
  // Arahkan ke halaman pembayaran (sesuaikan rute Anda)
  // router.visit(route('payment.show', props.appointment.payment_token));
  alert("Redirecting to payment gateway...");
};
</script>

<template>
  <Head title="Appointment Details" />

  <div class="space-y-6">
    <div class="flex items-center gap-4">
      <Link
        :href="route('dashboard.appointments.index')"
        class="p-2 hover:bg-slate-100 rounded-lg transition-colors text-slate-500">
        <ArrowLeft class="w-5 h-5" />
      </Link>
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Session Details</h1>
        <p class="text-sm text-slate-500">
          Consultation with
          <span class="font-bold text-slate-900">{{
            appointment.expert.user.name
          }}</span>
        </p>
      </div>
      <div class="ml-auto">
        <span
          class="px-4 py-2 rounded-xl text-sm font-bold border flex items-center gap-2"
          :class="getStatusBadge(appointment.status).class">
          <span class="w-2 h-2 rounded-full bg-current opacity-75"></span>
          {{ getStatusBadge(appointment.status).label }}
        </span>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-6">
        <div
          class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60 relative overflow-hidden">
          <div
            class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-bl-full -mr-4 -mt-4 opacity-50 pointer-events-none"></div>

          <h3
            class="font-bold text-slate-900 mb-6 flex items-center gap-3 relative z-10">
            <div
              class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-violet-600">
              <Calendar class="w-4 h-4" />
            </div>
            Session Schedule
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
              class="p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-violet-200 transition-colors">
              <div class="flex items-start gap-3">
                <div class="mt-1">
                  <Calendar
                    class="w-4 h-4 text-slate-400 group-hover:text-violet-500 transition-colors" />
                </div>
                <div>
                  <span
                    class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-violet-400"
                    >Date</span
                  >
                  <div class="text-base font-bold text-slate-900 mt-0.5">
                    {{ formatDate(appointment.date_time) }}
                  </div>
                </div>
              </div>
            </div>

            <div
              class="p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-blue-200 transition-colors">
              <div class="flex items-start gap-3">
                <div class="mt-1">
                  <Clock
                    class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors" />
                </div>
                <div>
                  <span
                    class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-blue-400"
                    >Time</span
                  >
                  <div class="text-base font-bold text-slate-900 mt-0.5">
                    {{ formatTime(appointment.date_time) }} WIB
                  </div>
                </div>
              </div>
            </div>

            <div
              class="p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-orange-200 transition-colors">
              <div class="flex items-start gap-3">
                <div class="mt-1">
                  <Timer
                    class="w-4 h-4 text-slate-400 group-hover:text-orange-500 transition-colors" />
                </div>
                <div>
                  <span
                    class="text-xs font-bold text-slate-400 uppercase tracking-wider group-hover:text-orange-400"
                    >Duration</span
                  >
                  <div class="text-base font-bold text-slate-900 mt-0.5">
                    {{ appointment.hours || 1 }} Hour{{
                      (appointment.hours || 1) > 1 ? "s" : ""
                    }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            v-if="['confirmed', 'progress'].includes(appointment.status)"
            class="mt-6 pt-6 border-t border-slate-100">
            <div class="flex items-center gap-2 mb-3">
              <MapPin class="w-4 h-4 text-slate-400" />
              <span
                class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                >Meeting Location</span
              >
            </div>
            <div
              class="p-4 rounded-2xl border border-blue-100 bg-blue-50/50 flex items-center gap-3 group hover:bg-blue-50 transition-colors">
              <div
                class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-blue-600">
                <Video class="w-5 h-5" />
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs text-blue-600 font-bold uppercase mb-0.5">
                  Online Meeting Link
                </div>
                <a
                  v-if="appointment.location_url"
                  :href="appointment.location_url"
                  target="_blank"
                  class="text-sm font-medium text-slate-700 truncate hover:text-blue-600 transition-colors block">
                  {{ appointment.location_url }}
                </a>
                <span v-else class="text-sm font-medium text-slate-400 italic">
                  Link will be provided by Expert soon.
                </span>
              </div>
              <a
                v-if="appointment.location_url"
                :href="appointment.location_url"
                target="_blank"
                class="p-2 hover:bg-white rounded-lg transition-colors text-slate-400 hover:text-blue-600">
                <ExternalLink class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>

        <div
          class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60">
          <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-3">
            <div
              class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center text-pink-600">
              <FileText class="w-4 h-4" />
            </div>
            Consultation Topic
          </h3>

          <div class="flex flex-wrap items-center gap-2 mb-6">
            <span
              class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold text-slate-600">
              {{ appointment.skill.sub_category.category.name || "Category" }}
            </span>
            <span class="text-slate-300">/</span>
            <span
              class="px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold text-slate-600">
              {{ appointment.skill.sub_category.name }}
            </span>
          </div>

          <div
            class="relative bg-linear-to-br from-violet-600 to-indigo-600 p-6 rounded-2xl shadow-lg shadow-violet-200 text-white overflow-hidden mb-6">
            <div class="absolute top-0 right-0 p-4 opacity-10">
              <Hash class="w-24 h-24" />
            </div>
            <span
              class="relative z-10 text-xs font-bold text-violet-200 uppercase tracking-wider border border-violet-400/30 px-2 py-1 rounded">
              Skill Focus
            </span>
            <h2 class="relative z-10 text-2xl font-bold mt-2">
              {{ appointment.skill.name }}
            </h2>
          </div>

          <div>
            <div class="flex items-center gap-2 mb-3">
              <AlignLeft class="w-4 h-4 text-slate-400" />
              <span
                class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                >Your Notes</span
              >
            </div>
            <div
              class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-slate-600 leading-relaxed text-sm relative">
              <div class="absolute top-4 left-4 text-slate-200">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                  <path
                    d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.0166 21L5.0166 18C5.0166 16.8954 5.91203 16 7.0166 16H10.0166C10.5689 16 11.0166 15.5523 11.0166 15V9C11.0166 8.44772 10.5689 8 10.0166 8H6.0166C5.46432 8 5.0166 8.44772 5.0166 9V11C5.0166 11.5523 4.56889 12 4.0166 12H3.0166V5H13.0166V15C13.0166 18.3137 10.3303 21 7.0166 21H5.0166Z"></path>
                </svg>
              </div>
              <div class="relative z-10 pl-2">
                {{ appointment.topic || "No notes provided." }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1 space-y-6">
        <div
          class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm ring-1 ring-slate-100">
          <h3
            class="font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">
            My Actions
          </h3>

          <div
            v-if="appointment.payment_status === 'pending'"
            class="space-y-3">
            <div
              class="p-3 bg-yellow-50 text-yellow-800 text-xs rounded-xl border border-yellow-100 flex gap-2">
              <AlertCircle class="w-4 h-4 shrink-0" />
              <p>Please complete payment to confirm your booking.</p>
            </div>
            <button
              @click="proceedToPayment"
              class="w-full flex items-center justify-center gap-2 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-violet-600 transition-all shadow-lg shadow-slate-900/20">
              <CreditCard class="w-4 h-4" /> Pay Now
            </button>
          </div>

          <div
            v-else-if="['confirmed', 'progress'].includes(appointment.status)"
            class="space-y-3">
            <div
              class="p-3 bg-green-50 text-green-800 text-xs rounded-xl border border-green-100 flex gap-2">
              <CheckCircle2 class="w-4 h-4 shrink-0" />
              <p>Your session is confirmed. Please join on time.</p>
            </div>

            <a
              v-if="appointment.location_url"
              :href="appointment.location_url"
              target="_blank"
              class="w-full flex items-center justify-center gap-2 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
              <Video class="w-4 h-4" /> Join Meeting
            </a>
            <button
              v-else
              disabled
              class="w-full flex items-center justify-center gap-2 py-3 bg-slate-100 text-slate-400 rounded-xl font-bold text-sm cursor-not-allowed">
              <Video class="w-4 h-4" /> Link Not Ready
            </button>
          </div>

          <div
            v-else-if="appointment.status === 'need_confirmation'"
            class="text-center py-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
            <p class="text-sm text-slate-500">Waiting for expert to accept.</p>
          </div>

          <div v-else class="text-center py-4 bg-slate-50 rounded-xl">
            <p class="text-sm text-slate-500">No actions available.</p>
          </div>
        </div>

        <div
          class="bg-linear-to-br from-slate-50 to-violet-50 p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
          <div
            class="absolute top-0 right-0 w-32 h-32 bg-violet-200/20 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>

          <h3
            class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider flex items-center gap-2 relative z-10">
            <Briefcase class="w-5 h-5 text-violet-600" /> Expert Profile
          </h3>

          <div class="flex items-start gap-4 mb-6 relative z-10">
            <div class="relative shrink-0">
              <img
                :src="
                  appointment.expert.user.picture ||
                  `https://ui-avatars.com/api/?name=${appointment.expert.user.name}&background=random`
                "
                class="w-16 h-16 rounded-2xl object-cover shadow-md shadow-violet-200/50 border-2 border-white"
                alt="Expert Avatar" />
            </div>
            <div>
              <div class="font-bold text-slate-900 text-lg leading-tight">
                {{ appointment.expert.user.name }}
              </div>
              <div
                class="text-sm text-slate-500 mt-1 flex items-center gap-1.5">
                <span
                  class="inline-block w-2 h-2 rounded-full bg-violet-500"></span>
                Verified Expert
              </div>
            </div>
          </div>

          <div
            class="space-y-3 pt-4 border-t border-violet-100/50 relative z-10">
            <div class="flex items-center gap-3 text-sm text-slate-600 group">
              <div
                class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center shadow-sm group-hover:border-violet-200 transition-colors shrink-0">
                <Mail
                  class="w-4 h-4 text-slate-400 group-hover:text-violet-600 transition-colors" />
              </div>
              <a
                :href="`mailto:${appointment.expert.user.email}`"
                class="hover:text-violet-600 transition-colors truncate font-medium">
                {{ appointment.expert.user.email }}
              </a>
            </div>

            <div class="flex items-center gap-3 text-sm text-slate-600 group">
              <div
                class="w-8 h-8 rounded-lg bg-white border border-slate-100 flex items-center justify-center shadow-sm group-hover:border-violet-200 transition-colors shrink-0">
                <Phone
                  class="w-4 h-4 text-slate-400 group-hover:text-violet-600 transition-colors" />
              </div>
              <span class="font-medium">{{
                appointment.expert.user.phone || "-"
              }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
