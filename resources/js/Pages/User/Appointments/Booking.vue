<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import { 
    User, Users, ArrowRight, ArrowLeft, 
    Calendar as IconCalendar, Clock, Plus, Trash2,
    CheckCircle2, AlertCircle
} from 'lucide-vue-next';

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    expert: Object,
    bookedSlots: Object, // Data slot sibuk dari backend
    backUrl: String,
});

// --- STATE MANAGEMENT ---
const step = ref(1); // 1 = Selection, 2 = Booking Form
const selectedDate = ref(null);

// Form Utama (Inertia)
const form = useForm({
    expert_id: props.expert.id,
    type: 'individual', // 'individual' or 'group'
    date: '',
    time: '',
    hours: 1,
    topic: '',
    guests: [], // Array email untuk group
});

// --- LOGIC STEP 1: SELECTION ---
const selectType = (type) => {
    form.type = type;
    // Reset guests jika pindah ke individual
    if (type === 'individual') {
        form.guests = [];
    } else {
        // Jika group, minimal sediakan 1 input kosong
        if (form.guests.length === 0) addGuest(); 
    }
    step.value = 2; // Lanjut ke step 2
};

// --- LOGIC STEP 2: GUEST MANAGEMENT (Dynamic Input) ---
const addGuest = () => {
    if (form.guests.length < 5) {
        form.guests.push(''); // Push string kosong untuk email
    }
};

const removeGuest = (index) => {
    form.guests.splice(index, 1);
};

// --- LOGIC CALENDAR & TIME SLOTS ---
// Format Date ke YYYY-MM-DD untuk form
watch(selectedDate, (val) => {
    if (val) {
        const year = val.getFullYear();
        const month = String(val.getMonth() + 1).padStart(2, '0');
        const day = String(val.getDate()).padStart(2, '0');
        form.date = `${year}-${month}-${day}`;
        form.time = ''; // Reset jam jika ganti tanggal
    }
});

// Generate Time Slots (09:00 - 17:00)
const timeSlots = computed(() => {
    const slots = [];
    if (!form.date) return slots;

    // Cek slot yang sudah dibooking dari props backend
    const busyTimes = props.bookedSlots[form.date] || [];

    for (let i = 9; i < 17; i++) {
        const hour = String(i).padStart(2, '0');
        const time = `${hour}:00`;
        
        // Cek ketersediaan
        // Logic sederhana: jika jam X ada di busyTimes, disable.
        // (Logic detail durasi 2 jam dst sudah dihandle backend conflict check,
        // di sini kita visualisasi slot awal saja)
        const isBusy = busyTimes.includes(time);
        
        slots.push({ time, isBusy });
    }
    return slots;
});

// --- SUBMIT ---
const submit = () => {
    form.post(route('booking.store'), {
        preserveScroll: true,
        onError: () => {
            // Jika ada error validasi backend, user tetap di step 2
        }
    });
};
</script>

<template>
    <Head title="Book Appointment" />

    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex items-center gap-4 mb-8">
            <button v-if="step === 2" @click="step = 1" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
                <ArrowLeft class="w-6 h-6 text-slate-500" />
            </button>
            <Link v-else :href="backUrl" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
                <ArrowLeft class="w-6 h-6 text-slate-500" />
            </Link>
            
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    {{ step === 1 ? 'Select Session Type' : 'Finalize Booking' }}
                </h1>
                <p class="text-slate-500 text-sm">Booking with <span class="font-bold text-violet-600">{{ expert.user.name }}</span></p>
            </div>
        </div>

        <div v-if="step === 1" class="grid md:grid-cols-2 gap-6">
            
            <button @click="selectType('individual')" 
                class="group relative overflow-hidden bg-white p-8 rounded-3xl border-2 border-slate-100 hover:border-violet-500 hover:shadow-xl transition-all duration-300 text-left">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <User class="w-32 h-32 text-violet-600" />
                </div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-violet-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <User class="w-7 h-7 text-violet-600" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Personal Session</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">
                        One-on-one consultation focused on your personal growth, career path, or specific technical skills.
                    </p>
                    <span class="inline-flex items-center gap-2 text-sm font-bold text-violet-600 group-hover:translate-x-1 transition-transform">
                        Select Personal <ArrowRight class="w-4 h-4" />
                    </span>
                </div>
            </button>

            <button @click="selectType('group')" 
                class="group relative overflow-hidden bg-white p-8 rounded-3xl border-2 border-slate-100 hover:border-blue-500 hover:shadow-xl transition-all duration-300 text-left">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <Users class="w-32 h-32 text-blue-600" />
                </div>
                
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <Users class="w-7 h-7 text-blue-600" />
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Team Session</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">
                        Invite up to 5 colleagues. Perfect for team conflict resolution, brainstorming, or group training.
                    </p>
                    <span class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                        Select Team <ArrowRight class="w-4 h-4" />
                    </span>
                </div>
            </button>
        </div>

        <div v-if="step === 2" class="grid lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">1. Pick a Date</label>
                    <div class="flex justify-center">
                        <DatePicker 
                            v-model="selectedDate" 
                            mode="date" 
                            :min-date="new Date()"
                            color="violet"
                            borderless
                            transparent
                            class="w-full"
                        />
                    </div>
                </div>

                <div v-if="form.date" class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">2. Pick a Time</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button v-for="slot in timeSlots" :key="slot.time"
                            @click="!slot.isBusy && (form.time = slot.time)"
                            :disabled="slot.isBusy"
                            :class="[
                                'py-2 px-3 rounded-xl text-sm font-bold border transition-all',
                                slot.isBusy 
                                    ? 'bg-slate-50 text-slate-300 border-transparent cursor-not-allowed line-through' 
                                    : form.time === slot.time
                                        ? 'bg-violet-600 text-white border-violet-600 shadow-md'
                                        : 'bg-white text-slate-700 border-slate-200 hover:border-violet-300 hover:bg-violet-50'
                            ]"
                        >
                            {{ slot.time }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <form @submit.prevent="submit" class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    
                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <User v-if="form.type === 'individual'" class="w-5 h-5 text-violet-600" />
                            <Users v-else class="w-5 h-5 text-blue-600" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Session Type</p>
                            <p class="text-sm font-bold text-slate-900 capitalize">{{ form.type }} Session</p>
                        </div>
                        <button type="button" @click="step = 1" class="ml-auto text-xs text-violet-600 font-bold hover:underline">Change</button>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Topic / Discussion Focus</label>
                        <textarea v-model="form.topic" rows="3" class="w-full rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 text-sm" placeholder="What would you like to discuss?"></textarea>
                        <p v-if="form.errors.topic" class="text-red-500 text-xs mt-1">{{ form.errors.topic }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Duration (Hours)</label>
                        <select v-model="form.hours" class="w-full rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 text-sm">
                            <option :value="1">1 Hour</option>
                            <option :value="2">2 Hours</option>
                            <option :value="3">3 Hours</option>
                        </select>
                    </div>

                    <div v-if="form.type === 'group'" class="space-y-3 pt-4 border-t border-slate-100">
                        <div class="flex justify-between items-center">
                            <label class="block text-sm font-bold text-slate-700">Invite Colleagues</label>
                            <span class="text-xs text-slate-400">{{ form.guests.length }}/5 Guests</span>
                        </div>
                        
                        <div v-for="(guest, index) in form.guests" :key="index" class="flex gap-2">
                            <input type="email" v-model="form.guests[index]" placeholder="colleague@company.com" 
                                class="flex-1 rounded-xl border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            <button type="button" @click="removeGuest(index)" class="p-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                <Trash2 class="w-5 h-5" />
                            </button>
                        </div>

                        <button v-if="form.guests.length < 5" type="button" @click="addGuest" 
                            class="text-sm font-bold text-blue-600 flex items-center gap-2 hover:bg-blue-50 px-3 py-2 rounded-lg transition-colors">
                            <Plus class="w-4 h-4" /> Add Another Guest
                        </button>
                        
                        <div class="flex gap-2 mt-2 p-3 bg-blue-50 text-blue-700 text-xs rounded-xl">
                            <AlertCircle class="w-4 h-4 shrink-0" />
                            <p>Guests will receive an email invitation and Google Calendar event.</p>
                        </div>
                        
                        <div v-if="form.errors.guests" class="text-red-500 text-xs">{{ form.errors.guests }}</div>
                        <div v-for="(error, key) in form.errors" :key="key">
                            <p v-if="key.includes('guests.')" class="text-red-500 text-xs">{{ error }}</p>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" :disabled="form.processing || !form.date || !form.time" 
                            class="w-full py-4 rounded-xl text-white font-bold text-lg shadow-lg transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="form.type === 'individual' ? 'bg-violet-600 hover:bg-violet-700 shadow-violet-200' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-200'">
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Confirm {{ form.type === 'individual' ? 'Booking' : 'Team Session' }}</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>