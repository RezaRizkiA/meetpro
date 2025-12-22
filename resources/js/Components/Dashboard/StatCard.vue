<script setup>
import { computed } from "vue";

const props = defineProps({
    label: String,
    value: [String, Number],
    icon: Object,
    iconColor: {
        type: String,
        default: "blue",
    },
    trend: {
        type: String,
        default: null, // e.g., '+12%', '-5%'
    },
});

const iconBgColor = computed(() => {
    const colors = {
        blue: "bg-blue-500/10",
        violet: "bg-violet-500/10",
        green: "bg-green-500/10",
        orange: "bg-orange-500/10",
        red: "bg-red-500/10",
    };
    return colors[props.iconColor] || colors.blue;
});

const iconTextColor = computed(() => {
    const colors = {
        blue: "text-blue-400",
        violet: "text-violet-400",
        green: "text-green-400",
        orange: "text-orange-400",
        red: "text-red-400",
    };
    return colors[props.iconColor] || colors.blue;
});

const trendColor = computed(() => {
    if (!props.trend) return "";
    return props.trend.startsWith("+") ? "text-green-400" : "text-red-400";
});
</script>

<template>
    <div
        class="bg-slate-800/50 backdrop-blur-sm p-6 rounded-xl border border-slate-700/50 hover:border-slate-600/50 transition-all duration-200"
    >
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-lg flex items-center justify-center"
                    :class="iconBgColor"
                >
                    <component
                        :is="icon"
                        class="w-6 h-6"
                        :class="iconTextColor"
                    />
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-400 mb-1">
                        {{ label }}
                    </p>
                    <h3 class="text-2xl font-bold text-slate-100">
                        {{ value }}
                    </h3>
                </div>
            </div>
            <div v-if="trend" class="text-xs font-semibold" :class="trendColor">
                {{ trend }}
            </div>
        </div>
    </div>
</template>
