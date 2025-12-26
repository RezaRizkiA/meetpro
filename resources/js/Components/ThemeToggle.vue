<script setup>
import { ref, onMounted } from "vue";
import { Sun, Moon } from "lucide-vue-next";

const isDark = ref(true);

onMounted(() => {
    // Check localStorage first, then system preference
    const stored = localStorage.getItem("theme");
    if (stored) {
        isDark.value = stored === "dark";
    } else {
        isDark.value = window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;
    }
    applyTheme();
});

const toggleTheme = () => {
    isDark.value = !isDark.value;
    localStorage.setItem("theme", isDark.value ? "dark" : "light");
    applyTheme();
};

const applyTheme = () => {
    if (isDark.value) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
};
</script>

<template>
    <button
        @click="toggleTheme"
        class="relative p-2 rounded-xl transition-all duration-300 hover:bg-slate-700/50 border border-transparent hover:border-blue-500/30 group"
        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
    >
        <!-- Sun Icon (shown when dark mode is active - click to go light) -->
        <Sun
            v-if="isDark"
            class="w-5 h-5 text-amber-400 transition-transform group-hover:rotate-45 group-hover:scale-110"
        />
        <!-- Moon Icon (shown when light mode is active - click to go dark) -->
        <Moon
            v-else
            class="w-5 h-5 text-blue-400 transition-transform group-hover:-rotate-12 group-hover:scale-110"
        />
    </button>
</template>
