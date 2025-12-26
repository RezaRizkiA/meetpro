<template>
    <div class="min-h-screen bg-page-gradient flex flex-col">
        <!-- Navbar Skeleton -->
        <div class="w-full px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div
                    class="w-9 h-9 rounded-lg bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                ></div>
                <div
                    class="w-28 h-6 rounded-lg bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                ></div>
            </div>
            <div class="hidden lg:flex items-center gap-4">
                <div
                    class="w-20 h-8 rounded-lg bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                ></div>
                <div
                    class="w-28 h-10 rounded-full bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                ></div>
            </div>
        </div>

        <!-- Content Skeleton -->
        <div
            class="flex-1 flex flex-col items-center justify-center px-6 py-16"
        >
            <!-- Title Skeleton -->
            <div class="text-center mb-12 space-y-4">
                <div
                    class="w-96 h-12 rounded-xl bg-slate-300 dark:bg-slate-700/50 skeleton-pulse mx-auto"
                ></div>
                <div
                    class="w-80 h-6 rounded-lg bg-slate-300 dark:bg-slate-700/50 skeleton-pulse mx-auto"
                ></div>
                <div
                    class="w-64 h-6 rounded-lg bg-slate-300 dark:bg-slate-700/50 skeleton-pulse mx-auto"
                ></div>
            </div>

            <!-- Cards Skeleton -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl w-full">
                <div
                    class="rounded-2xl bg-slate-200 dark:bg-slate-800/30 border border-slate-300 dark:border-slate-700/50 overflow-hidden"
                >
                    <div
                        class="h-56 bg-slate-300 dark:bg-slate-700/30 skeleton-pulse"
                    ></div>
                    <div class="p-6 space-y-4">
                        <div
                            class="w-48 h-8 rounded-lg bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                        ></div>
                        <div
                            class="w-full h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                        ></div>
                        <div
                            class="w-3/4 h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                        ></div>
                        <div
                            class="w-full h-12 rounded-xl bg-slate-300 dark:bg-slate-700/50 skeleton-pulse mt-4"
                        ></div>
                    </div>
                </div>
                <div
                    class="rounded-2xl bg-slate-200 dark:bg-slate-800/30 border border-slate-300 dark:border-slate-700/50 overflow-hidden"
                >
                    <div
                        class="h-56 bg-slate-300 dark:bg-slate-700/30 skeleton-pulse"
                    ></div>
                    <div class="p-6 space-y-4">
                        <div
                            class="w-48 h-8 rounded-lg bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                        ></div>
                        <div
                            class="w-full h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                        ></div>
                        <div
                            class="w-3/4 h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                        ></div>
                        <div
                            class="w-full h-12 rounded-xl bg-slate-300 dark:bg-slate-700/50 skeleton-pulse mt-4"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Skeleton -->
        <div
            class="w-full px-6 py-6 border-t border-slate-300 dark:border-slate-800/50"
        >
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div
                    class="w-48 h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                ></div>
                <div class="flex items-center gap-6">
                    <div
                        class="w-20 h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                    ></div>
                    <div
                        class="w-24 h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                    ></div>
                    <div
                        class="w-16 h-4 rounded bg-slate-300 dark:bg-slate-700/50 skeleton-pulse"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
