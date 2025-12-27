<script setup>
defineProps({
    title: {
        type: [String, Array],
        required: true,
    },
    subtitle: {
        type: String,
        default: "",
    },
    badge: {
        type: String,
        default: "",
    },
    align: {
        type: String,
        default: "center",
        validator: (value) => ["left", "center", "right"].includes(value),
    },
});

const alignClass = (align) => {
    const classes = {
        left: "text-left items-start",
        center: "text-center items-center",
        right: "text-right items-end",
    };
    return classes[align] || classes.center;
};
</script>

<template>
    <section
        class="relative pt-32 pb-20 px-6 overflow-hidden bg-[#2563EB]/10 dark:bg-[#2563EB]/5"
        :class="alignClass(align)"
    >
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-blue-600/10 dark:bg-blue-600/5 blur-[120px] rounded-full -z-10 pointer-events-none"
        ></div>

        <div class="max-w-7xl mx-auto flex flex-col" :class="alignClass(align)">
            <!-- Badge -->
            <div v-if="badge" class="badge-glow mb-6">
                {{ badge }}
            </div>

            <!-- Title -->
            <h1
                class="text-5xl md:text-7xl font-bold tracking-tight mb-8 leading-[1.1] text-transparent bg-clip-text bg-linear-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 py-4"
            >
                <template v-if="Array.isArray(title)">
                    <template v-for="(line, index) in title" :key="index">
                        {{ line }}<br v-if="index < title.length - 1" />
                    </template>
                </template>
                <template v-else>{{ title }}</template>
            </h1>

            <!-- Subtitle -->
            <p
                v-if="subtitle"
                class="text-xl text-muted mb-10 max-w-2xl leading-relaxed"
            >
                {{ subtitle }}
            </p>

            <!-- Slot for CTA buttons or other content -->
            <slot />
        </div>
    </section>
</template>
