<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { ArrowRight, Users, Briefcase } from "lucide-vue-next";

const page = usePage();
const assets = computed(() => page.props.assets);
const user = computed(() => page.props.auth?.user);

// Image paths
const clientImage = computed(
    () => assets.value?.clientOptionUrl || "/image/clientoption.jpg"
);
const expertImage = computed(
    () => assets.value?.expertOptionUrl || "/image/expertoption.jpg"
);

const currentYear = new Date().getFullYear();
</script>

<template>
    <Head title="Choose Your Path" />

    <div
        class="min-h-screen bg-dark-gradient-radial text-white font-sans flex flex-col"
    >
        <!-- Navbar -->
        <nav class="w-full px-6 py-4 flex items-center justify-between">
            <Link :href="route('home')" class="flex items-center gap-2 group">
                <img
                    :src="assets.logoSmallUrl"
                    class="h-9 w-auto transition-transform group-hover:scale-105"
                    alt="Logo"
                />
                <span
                    class="font-display font-bold text-xl tracking-tight text-white"
                >
                    Key<span class="text-blue-400">Person</span>
                </span>
            </Link>

            <Link
                v-if="!user"
                :href="route('login')"
                class="px-5 py-2.5 rounded-lg bg-slate-800/50 border border-slate-700 text-sm font-medium text-white hover:bg-slate-700/50 hover:border-blue-500/50 transition-all"
            >
                Log In
            </Link>
            <Link
                v-else
                :href="route('dashboard.index')"
                class="px-5 py-2.5 rounded-lg bg-blue-600 text-sm font-medium text-white hover:bg-blue-500 transition-all"
            >
                Dashboard
            </Link>
        </nav>

        <!-- Main Content -->
        <main
            class="flex-1 flex flex-col items-center justify-center px-6 py-16"
        >
            <!-- Title -->
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-bold tracking-tight mb-6">
                    <span class="italic">Choose your</span> path
                </h1>
                <p
                    class="text-lg text-slate-400 max-w-xl mx-auto leading-relaxed"
                >
                    Select your role to get started with your personalized
                    experience.<br />
                    Are you here to hire or to get hired?
                </p>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl w-full">
                <!-- Client Card -->
                <div
                    class="group relative rounded-2xl overflow-hidden bg-slate-900/30 border border-slate-700/50 hover:border-blue-500/50 transition-all duration-300"
                >
                    <!-- Image -->
                    <div class="relative h-56 overflow-hidden">
                        <img
                            :src="clientImage"
                            alt="Hire Talent"
                            class="w-full h-full object-cover opacity-70 group-hover:opacity-90 group-hover:scale-105 transition-all duration-500"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent"
                        ></div>

                        <!-- Icon -->
                        <div class="absolute bottom-4 left-6">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center backdrop-blur-sm"
                            >
                                <Users class="w-6 h-6 text-blue-400" />
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-white mb-3">
                            I want to find an Expert
                        </h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">
                            Book verified mentors, schedule consultations, and
                            grow your team's skills effortlessly.
                        </p>

                        <Link
                            :href="route('client_onboarding.create')"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white font-medium hover:bg-slate-700 hover:border-blue-500/50 transition-all group/btn"
                        >
                            Join as Client
                            <ArrowRight
                                class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"
                            />
                        </Link>
                    </div>
                </div>

                <!-- Expert Card -->
                <div
                    class="group relative rounded-2xl overflow-hidden bg-slate-900/30 border border-slate-700/50 hover:border-cyan-500/50 transition-all duration-300"
                >
                    <!-- Image -->
                    <div class="relative h-56 overflow-hidden">
                        <img
                            :src="expertImage"
                            alt="Find Work"
                            class="w-full h-full object-cover opacity-70 group-hover:opacity-90 group-hover:scale-105 transition-all duration-500"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent"
                        ></div>

                        <!-- Icon -->
                        <div class="absolute bottom-4 left-6">
                            <div
                                class="w-12 h-12 rounded-xl bg-cyan-600/20 border border-cyan-500/30 flex items-center justify-center backdrop-blur-sm"
                            >
                                <Briefcase class="w-6 h-6 text-cyan-400" />
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-white mb-3">
                            I want to offer expertise
                        </h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">
                            Manage your schedule, monetize your time, and mentor
                            clients professionally.
                        </p>

                        <Link
                            :href="route('expert_onboarding.create')"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-slate-800/80 border border-slate-700 text-white font-medium hover:bg-slate-700 hover:border-cyan-500/50 transition-all group/btn"
                        >
                            Join as Expert
                            <ArrowRight
                                class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"
                            />
                        </Link>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full px-6 py-6 border-t border-slate-800/50">
            <div
                class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4"
            >
                <p class="text-sm text-slate-500">
                    © {{ currentYear }} KeyPerson Inc. All rights reserved.
                </p>
                <div class="flex items-center gap-6 text-sm text-slate-500">
                    <Link
                        :href="route('support')"
                        class="hover:text-white transition-colors"
                        >Help Center</Link
                    >
                    <Link
                        :href="route('privacy')"
                        class="hover:text-white transition-colors"
                        >Privacy Policy</Link
                    >
                    <Link
                        :href="route('terms')"
                        class="hover:text-white transition-colors"
                        >Terms</Link
                    >
                </div>
            </div>
        </footer>
    </div>
</template>
