<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";
import PageLoadingSkeleton from "./PageLoadingSkeleton.vue";

const isNavigating = ref(false);
const showContent = ref(true);

let startListener = null;
let finishListener = null;

onMounted(() => {
    // Listen for Inertia navigation events
    startListener = router.on("start", () => {
        isNavigating.value = true;
        showContent.value = false;
    });

    finishListener = router.on("finish", () => {
        // Small delay for smooth transition
        setTimeout(() => {
            isNavigating.value = false;
            showContent.value = true;
        }, 100);
    });
});

onUnmounted(() => {
    if (startListener) startListener();
    if (finishListener) finishListener();
});
</script>

<template>
    <div class="min-h-screen bg-background">
        <!-- Loading Skeleton -->
        <Transition
            enter-active-class="transition-opacity duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <PageLoadingSkeleton v-if="isNavigating" />
        </Transition>

        <!-- Page Content -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div v-show="showContent">
                <slot />
            </div>
        </Transition>
    </div>
</template>
