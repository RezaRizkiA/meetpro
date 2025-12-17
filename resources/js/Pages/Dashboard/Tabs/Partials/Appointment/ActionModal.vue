<script setup>
import { CheckCircle2, XCircle, Medal } from "lucide-vue-next";

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
    type: String, // 'progress', 'declined', 'completed'
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <Transition name="modal">
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="$emit('close')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
            
            <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" 
                        :class="{
                            'bg-green-100 text-green-600': type === 'progress',
                            'bg-red-100 text-red-600': type === 'declined',
                            'bg-blue-100 text-blue-600': type === 'completed'
                        }">
                        <CheckCircle2 v-if="type === 'progress'" class="w-8 h-8" />
                        <XCircle v-else-if="type === 'declined'" class="w-8 h-8" />
                        <Medal v-else class="w-8 h-8" />
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ title }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">{{ message }}</p>
                    
                    <div class="flex gap-3">
                        <button @click="$emit('close')" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">
                            Cancel
                        </button>
                        <button @click="$emit('confirm')" 
                            class="flex-1 px-4 py-3 text-white font-bold rounded-xl shadow-lg transition-transform active:scale-95"
                            :class="{
                                'bg-green-600 hover:bg-green-700 shadow-green-200': type === 'progress',
                                'bg-red-600 hover:bg-red-700 shadow-red-200': type === 'declined',
                                'bg-blue-600 hover:bg-blue-700 shadow-blue-200': type === 'completed'
                            }">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>