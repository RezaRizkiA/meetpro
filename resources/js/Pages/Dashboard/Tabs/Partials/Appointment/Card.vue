<script setup>
import { Link } from "@inertiajs/vue3";
import {
    Clock,
    CheckCircle2,
    XCircle,
    Medal,
    Calendar,
    CalendarClock,
    ChevronRight,
} from "lucide-vue-next";

const props = defineProps({
    appointment: Object,
    isExpert: Boolean,
    isAdmin: Boolean,
});

const emit = defineEmits(["action", "reschedule"]);

// Helpers (Bisa dipindah ke utils.js global jika mau)
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("id-ID").format(amount || 0);
};
</script>

<template>
    <div
        class="group bg-white rounded-2xl p-5 border border-slate-200 hover:border-violet-200 hover:shadow-lg hover:shadow-slate-200/40 transition-all duration-300 relative overflow-hidden"
    >
        <div
            class="absolute left-0 top-0 bottom-0 w-1.5"
            :class="{
                'bg-yellow-400': ['need_confirmation', 'paid'].includes(
                    appointment.status
                ),
                'bg-green-500': ['confirmed', 'progress'].includes(
                    appointment.status
                ),
                'bg-blue-500': appointment.status === 'completed',
                'bg-red-500': ['cancelled', 'declined'].includes(
                    appointment.status
                ),
            }"
        ></div>

        <div
            class="pl-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6"
        >
            <div class="flex items-start gap-4">
                <div class="relative shrink-0">
                    <div
                        class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden"
                    >
                        <img
                            v-if="
                                isExpert
                                    ? appointment.user?.picture_url
                                    : appointment.expert?.user?.picture_url
                            "
                            :src="
                                isExpert
                                    ? appointment.user?.picture_url
                                    : appointment.expert?.user?.picture_url
                            "
                            class="w-full h-full object-cover"
                        />
                        <span v-else class="text-lg font-bold text-slate-600">
                            {{
                                isExpert
                                    ? appointment.user?.name?.charAt(0) || "U"
                                    : appointment.expert?.user?.name?.charAt(
                                          0
                                      ) || "E"
                            }}
                        </span>
                    </div>
                    <div
                        class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white flex items-center justify-center bg-white"
                    >
                        <Clock
                            v-if="
                                ['need_confirmation', 'paid'].includes(
                                    appointment.status
                                )
                            "
                            class="w-3 h-3 text-yellow-500 fill-yellow-100"
                        />
                        <CheckCircle2
                            v-else-if="
                                ['confirmed', 'progress'].includes(
                                    appointment.status
                                )
                            "
                            class="w-3 h-3 text-green-500 fill-green-100"
                        />
                        <Medal
                            v-else-if="appointment.status === 'completed'"
                            class="w-3 h-3 text-blue-500 fill-blue-100"
                        />
                        <XCircle
                            v-else
                            class="w-3 h-3 text-red-500 fill-red-100"
                        />
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <h4
                            class="font-bold text-slate-900 group-hover:text-violet-700 transition-colors"
                        >
                            <template v-if="isAdmin">
                                <span class="text-slate-600">{{
                                    appointment.user?.name
                                }}</span>
                                <span class="mx-1 text-slate-300">with</span>
                                <span class="text-violet-600">{{
                                    appointment.expert?.user?.name
                                }}</span>
                            </template>

                            <template v-else>
                                {{
                                    isExpert
                                        ? appointment.user?.name
                                        : appointment.expert?.user?.name
                                }}
                            </template>
                        </h4>
                        <span
                            class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500"
                        >
                            {{ appointment.status.replace("_", " ") }}
                        </span>
                    </div>
                    <div
                        class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-sm text-slate-500"
                    >
                        <div class="flex items-center gap-1.5">
                            <Calendar class="w-3.5 h-3.5" />
                            {{ formatDate(appointment.date_time) }}
                        </div>
                        <div
                            class="w-1 h-1 bg-slate-300 rounded-full hidden sm:block"
                        ></div>
                        <div>
                            <span class="font-medium text-slate-700"
                                >Rp
                                {{ formatCurrency(appointment.price) }}</span
                            >
                            <span class="text-xs ml-1"
                                >({{ appointment.hours }} Hours)</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-2 lg:mt-0">
                <Link
                    v-if="!isExpert"
                    :href="route('payment.show', appointment.id)"
                    class="px-4 py-2 bg-slate-50 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-100 transition-colors border border-slate-200"
                >
                    Details
                </Link>

                <div v-if="isExpert" class="flex items-center gap-2">
                    <template
                        v-if="
                            ['need_confirmation', 'paid'].includes(
                                appointment.status
                            )
                        "
                    >
                        <button
                            @click="$emit('reschedule', appointment)"
                            title="Reschedule"
                            class="p-2 text-slate-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                        >
                            <CalendarClock class="w-5 h-5" />
                        </button>
                        <button
                            @click="$emit('action', appointment.id, 'declined')"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-all"
                        >
                            Decline
                        </button>
                        <button
                            @click="$emit('action', appointment.id, 'progress')"
                            class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-violet-600 shadow-lg shadow-slate-900/20 transition-all flex items-center gap-2"
                        >
                            Accept <ChevronRight class="w-3 h-3" />
                        </button>
                    </template>

                    <template v-if="appointment.status === 'progress'">
                        <span
                            class="text-xs font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full animate-pulse border border-green-100"
                            >Session Active</span
                        >
                        <button
                            @click="
                                $emit('action', appointment.id, 'completed')
                            "
                            class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-green-600 hover:bg-green-700 shadow-lg shadow-green-600/20 transition-all flex items-center gap-2"
                        >
                            <Medal class="w-4 h-4" /> Complete
                        </button>
                    </template>

                    <span
                        v-if="
                            ['completed', 'declined', 'cancelled'].includes(
                                appointment.status
                            )
                        "
                        class="text-xs font-bold text-slate-400 px-4 py-2 bg-slate-50 rounded-xl border border-slate-100"
                    >
                        {{
                            appointment.status === "completed"
                                ? "Archived"
                                : "Closed"
                        }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
