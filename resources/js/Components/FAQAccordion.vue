<script setup>
import { ref, nextTick } from "vue";
import { ChevronDownIcon } from "lucide-vue-next";

defineProps({
    items: {
        type: Array,
        required: true,
        // Expected format: [{ question: String, answer: String }, ...]
    },
    defaultOpen: {
        type: Number,
        default: null,
    },
});

const openIndex = ref(null);
const contentRefs = ref([]);
const heights = ref({});

const toggle = async (index) => {
    if (openIndex.value === index) {
        // Closing
        openIndex.value = null;
    } else {
        // Opening - measure height first
        openIndex.value = index;
        await nextTick();
        if (contentRefs.value[index]) {
            heights.value[index] = contentRefs.value[index].scrollHeight;
        }
    }
};

const getHeight = (index) => {
    if (openIndex.value === index && heights.value[index]) {
        return `${heights.value[index]}px`;
    }
    return "0px";
};
</script>

<template>
    <div class="space-y-4">
        <div
            v-for="(item, index) in items"
            :key="index"
            class="bg-white/80 dark:bg-slate-800/50 backdrop-blur-sm border border-slate-200 dark:border-slate-700/50 rounded-2xl overflow-hidden transition-all duration-300"
            :class="
                openIndex === index
                    ? 'shadow-[0_0_30px_rgba(59,130,246,0.15)]'
                    : ''
            "
        >
            <!-- Question -->
            <button
                @click="toggle(index)"
                class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 dark:hover:bg-slate-800/80 transition-colors group"
            >
                <span
                    class="font-bold text-lg text-slate-900 dark:text-white pr-4 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
                >
                    {{ item.question }}
                </span>
                <ChevronDownIcon
                    class="w-6 h-6 text-blue-600 dark:text-blue-400 transition-transform duration-300 shrink-0"
                    :class="openIndex === index ? 'rotate-180' : ''"
                />
            </button>

            <!-- Answer with smooth height animation -->
            <div
                :ref="(el) => (contentRefs[index] = el)"
                class="overflow-hidden transition-all duration-300 ease-out"
                :style="{ maxHeight: getHeight(index) }"
            >
                <div class="px-6 pb-6 pt-0">
                    <div
                        class="text-slate-700 dark:text-slate-300 leading-relaxed border-t border-slate-200 dark:border-slate-700/50 pt-4"
                    >
                        {{ item.answer }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
