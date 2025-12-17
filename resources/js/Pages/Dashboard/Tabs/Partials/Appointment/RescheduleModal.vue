<script setup>
import { CalendarClock, XCircle } from "lucide-vue-next";

const props = defineProps({
    show: Boolean,
    form: Object, // Inertia Form Object
    userName: String // Nama user yang di-reschedule
});

const emit = defineEmits(['close', 'submit']);
</script>

<template>
    <Transition name="modal">
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="$emit('close')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            
            <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <CalendarClock class="w-5 h-5 text-violet-600" />
                        <h3 class="font-bold text-slate-900">Reschedule Session</h3>
                    </div>
                    <button @click="$emit('close')" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <XCircle class="w-6 h-6" />
                    </button>
                </div>

                <form @submit.prevent="$emit('submit')" class="p-6">
                    <div class="p-4 bg-yellow-50 rounded-xl mb-6 border border-yellow-100">
                        <p class="text-xs text-yellow-800 leading-relaxed">
                            Changing the schedule will notify <strong>{{ userName }}</strong>.
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">New Date</label>
                            <input v-model="form.date" type="date" required class="w-full rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">New Time</label>
                            <input v-model="form.time" type="time" required class="w-full rounded-xl border-slate-200 focus:border-violet-500 focus:ring-violet-500" />
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="$emit('close')" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg shadow-violet-200 transition-all disabled:opacity-70">
                            {{ form.processing ? 'Updating...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>