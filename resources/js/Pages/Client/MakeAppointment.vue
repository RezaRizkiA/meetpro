<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import MainLayout from "../../Layouts/MainLayout.vue";
import {
    Calendar as CalendarIcon,
    Clock,
    MessageSquare,
    CreditCard,
    ArrowLeft,
    Plus,
    Minus,
    AlertCircle,
    CheckCircle2,
} from "lucide-vue-next";
import { DatePicker } from "v-calendar";
import "v-calendar/style.css";

const props = defineProps({
    expert: Object,
    backUrl: String,
    // Nanti Anda bisa mengirim data ini dari Controller untuk menandai tanggal sibuk
    bookedSlots: {
        type: Object, // format: {"2023-10-25": ['09:00', '10:00']}
        default: () => ({}),
    },
});

const form = useForm({ hours: 1, topic: "", date: "", time: "" });

// State untuk Calendar
const availableTimeSlots = ref([]);

// Setup Format Currency
const formatCurrency = (value) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(value);
const totalPrice = computed(() => props.expert.price * form.hours);

// Helper untuk format tanggal ke YYYY-MM-DD (format database)
const formatDateToDB = (date) => {
    const d = new Date(date);
    const month = "" + (d.getMonth() + 1);
    const day = "" + d.getDate();
    const year = d.getFullYear();
    return [year, month.padStart(2, "0"), day.padStart(2, "0")].join("-");
};

watch(
    () => form.hours,
    () => {
        if (form.date) generateTimeSlots();
    }
);

const minDate = computed(() => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(0, 0, 0, 0); // Reset jam agar presisi
    return tomorrow;
});

// 2. Inisialisasi default langsung ke 'Besok'
// Kita ambil .value karena minDate adalah computed ref
const dateModel = ref(minDate.value);

// Generate Time Slots (Jam Kerja: 09:00 - 17:00)
const generateTimeSlots = () => {
    const slots = [];
    const startHour = 9; // Jam 09:00
    const endHour = 17; // Jam 17:00

    const selectedDateKey = formatDateToDB(dateModel.value);
    const busyTimes = props.bookedSlots[selectedDateKey] || [];

    for (let i = startHour; i < endHour; i++) {
        const timeString = `${i.toString().padStart(2, "0")}:00`;

        let isSlotAvailable = true;

        // LOGIKA BARU: Cek Ketersediaan Beruntun (Consecutive Check)
        // Jika user minta 2 jam, kita cek jam i DAN jam i+1
        for (let h = 0; h < form.hours; h++) {
            const checkHour = i + h;
            const checkTimeString = `${checkHour
                .toString()
                .padStart(2, "0")}:00`;

            // Syarat A: Tidak boleh melebihi jam tutup kantor
            if (checkHour >= endHour) {
                isSlotAvailable = false;
                break;
            }

            // Syarat B: Tidak boleh menabrak jadwal yang sudah ada (booked)
            if (busyTimes.includes(checkTimeString)) {
                isSlotAvailable = false;
                break;
            }
        }

        slots.push({
            time: timeString,
            isAvailable: isSlotAvailable,
        });
    }
    availableTimeSlots.value = slots;
};

// Watcher: Setiap tanggal berubah, update form.date & reset jam
watch(
    dateModel,
    (newDate) => {
        if (newDate) {
            form.date = formatDateToDB(newDate);
            form.time = ""; // Reset jam jika ganti tanggal
            generateTimeSlots(); // Regenerate slot (bisa ditambah logic cek hari libur disini)
        }
    },
    { immediate: true }
);

const selectTime = (slot) => {
    if (slot.isAvailable) {
        form.time = slot.time;
    }
};

const updateHours = (amount) => {
    const newVal = form.hours + amount;
    if (newVal >= 1 && newVal <= 8) form.hours = newVal;
};

const submit = () =>
    form.post(route("appointment_post", props.expert.id), {
        preserveScroll: true,
    });
</script>

<template>
    <Head title="Book Appointment" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen font-sans pt-28 pb-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Link
                        :href="backUrl || route('expert_detail', expert.id)"
                        class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-violet-600 transition-colors"
                    >
                        <ArrowLeft class="w-4 h-4 mr-2" /> Cancel Booking
                    </Link>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-start">
                    <div class="lg:col-span-7">
                        <div
                            class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 shadow-xl shadow-slate-200/40"
                        >
                            <h2
                                class="font-display text-2xl font-bold text-slate-900 mb-8 flex items-center gap-3"
                            >
                                <div
                                    class="w-10 h-10 rounded-full bg-violet-50 flex items-center justify-center text-violet-600"
                                >
                                    <CalendarIcon class="w-5 h-5" />
                                </div>
                                Select Schedule
                            </h2>

                            <form
                                @submit.prevent="submit"
                                id="appointmentForm"
                                class="space-y-8"
                            >
                                <div class="grid md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1"
                                            >1. Pick a Date</label
                                        >
                                        <div
                                            class="border border-slate-100 rounded-2xl overflow-hidden p-4 bg-white shadow-sm flex justify-center"
                                        >
                                            <DatePicker
                                                v-model="dateModel"
                                                mode="date"
                                                :min-date="minDate"
                                                color="purple"
                                                borderless
                                                transparent
                                                class="font-sans w-full"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.date"
                                            class="text-red-500 text-xs mt-1 ml-1"
                                        >
                                            {{ form.errors.date }}
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1"
                                            >2. Pick a Time</label
                                        >

                                        <div
                                            v-if="form.date"
                                            class="grid grid-cols-2 gap-3 max-h-[320px] overflow-y-auto pr-2 custom-scrollbar"
                                        >
                                            <button
                                                v-for="slot in availableTimeSlots"
                                                :key="slot.time"
                                                type="button"
                                                @click="selectTime(slot)"
                                                :disabled="!slot.isAvailable"
                                                :class="[
                                                    'py-3 px-4 rounded-xl text-sm font-bold border transition-all duration-200 flex items-center justify-between group',
                                                    !slot.isAvailable
                                                        ? 'bg-slate-100 border-transparent text-slate-400 cursor-not-allowed line-through opacity-60' // Style untuk jam penuh
                                                        : form.time ===
                                                          slot.time
                                                        ? 'bg-violet-600 border-violet-600 text-white shadow-lg shadow-violet-200' // Style terpilih
                                                        : 'bg-white border-slate-200 text-slate-700 hover:border-violet-300 hover:bg-violet-50', // Style default
                                                ]"
                                            >
                                                <span>{{ slot.time }}</span>
                                                <span
                                                    v-if="!slot.isAvailable"
                                                    class="text-[10px] uppercase font-normal"
                                                    >Booked</span
                                                >
                                                <CheckCircle2
                                                    v-else-if="
                                                        form.time === slot.time
                                                    "
                                                    class="w-4 h-4"
                                                />
                                            </button>
                                        </div>

                                        <p
                                            v-if="form.errors.time"
                                            class="text-red-500 text-xs mt-1 ml-1"
                                        >
                                            {{ form.errors.time }}
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 ml-1"
                                        >3. Topic Discussion</label
                                    >
                                    <div class="relative">
                                        <MessageSquare
                                            class="absolute top-4 left-4 w-5 h-5 text-slate-400"
                                        />
                                        <textarea
                                            v-model="form.topic"
                                            rows="3"
                                            required
                                            placeholder="Briefly describe what you want to discuss..."
                                            class="block w-full pl-12 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all text-sm resize-none"
                                        ></textarea>
                                    </div>
                                    <p
                                        v-if="form.errors.topic"
                                        class="text-red-500 text-xs mt-1 ml-1"
                                    >
                                        {{ form.errors.topic }}
                                    </p>
                                </div>

                                <div
                                    class="p-4 bg-blue-50 border border-blue-100 rounded-2xl flex gap-3 text-blue-800 text-xs leading-relaxed items-start"
                                >
                                    <AlertCircle
                                        class="w-5 h-5 shrink-0 mt-0.5"
                                    />
                                    <span>
                                        <strong>Note:</strong> Times are
                                        displayed in your local timezone. Please
                                        ensure you are available at the selected
                                        time.
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="lg:col-span-5 sticky top-28">
                        <div
                            class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-2xl shadow-slate-200/50"
                        >
                            <h3
                                class="font-display text-lg font-bold text-slate-900 mb-6"
                            >
                                Booking Summary
                            </h3>

                            <div
                                class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100"
                            >
                                <img
                                    :src="expert.user.picture_url"
                                    class="w-14 h-14 rounded-2xl object-cover border border-slate-200"
                                />
                                <div>
                                    <h4 class="font-bold text-slate-900">
                                        {{ expert.user.name }}
                                    </h4>
                                    <p
                                        class="text-xs font-bold text-violet-600 bg-violet-50 px-2 py-0.5 rounded inline-block mt-1"
                                    >
                                        {{ expert.expertise }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4 mb-8">
                                <div
                                    class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-white flex items-center justify-center border border-slate-200 text-slate-400"
                                        >
                                            <CalendarIcon class="w-4 h-4" />
                                        </div>
                                        <div class="text-sm">
                                            <p
                                                class="text-xs text-slate-500 font-bold uppercase"
                                            >
                                                Date
                                            </p>
                                            <p class="font-bold text-slate-900">
                                                {{
                                                    form.date
                                                        ? new Date(
                                                              form.date
                                                          ).toLocaleDateString(
                                                              "id-ID",
                                                              {
                                                                  weekday:
                                                                      "long",
                                                                  day: "numeric",
                                                                  month: "long",
                                                              }
                                                          )
                                                        : "-"
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-white flex items-center justify-center border border-slate-200 text-slate-400"
                                        >
                                            <Clock class="w-4 h-4" />
                                        </div>
                                        <div class="text-sm">
                                            <p
                                                class="text-xs text-slate-500 font-bold uppercase"
                                            >
                                                Time
                                            </p>
                                            <p class="font-bold text-slate-900">
                                                {{ form.time || "-" }} WIB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <span class="text-sm font-medium text-slate-600"
                                    >Duration</span
                                >
                                <div
                                    class="flex items-center bg-slate-50 rounded-xl border border-slate-200 p-1"
                                >
                                    <button
                                        @click="updateHours(-1)"
                                        :disabled="form.hours <= 1"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-white shadow-sm disabled:opacity-50 hover:text-violet-600 transition-colors"
                                    >
                                        <Minus class="w-4 h-4" />
                                    </button>
                                    <span
                                        class="w-10 text-center font-bold text-slate-900 text-sm"
                                        >{{ form.hours }}h</span
                                    >
                                    <button
                                        @click="updateHours(1)"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-white shadow-sm hover:text-violet-600 transition-colors"
                                    >
                                        <Plus class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <div
                                class="flex justify-between items-center mb-8 pt-6 border-t border-dashed border-slate-200"
                            >
                                <span class="text-base font-bold text-slate-900"
                                    >Total Payment</span
                                >
                                <span
                                    class="text-2xl font-display font-bold text-violet-600"
                                    >{{ formatCurrency(totalPrice) }}</span
                                >
                            </div>

                            <button
                                type="submit"
                                form="appointmentForm"
                                :disabled="
                                    form.processing || !form.date || !form.time
                                "
                                class="w-full py-4 bg-slate-900 text-white rounded-xl font-bold text-base hover:bg-violet-600 transition-all shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                            >
                                <span
                                    v-if="form.processing"
                                    class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"
                                ></span>
                                <CreditCard v-else class="w-5 h-5" />
                                {{
                                    form.processing
                                        ? "Processing..."
                                        : "Proceed to Payment"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
