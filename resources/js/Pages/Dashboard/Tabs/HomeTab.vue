<script setup>
import { computed, ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    Users,
    Briefcase,
    CheckCircle,
    Calendar,
    ArrowUpRight,
    Layout,
    ExternalLink,
    Clock,
    Settings,
    Copy,
    Edit3,
    Eye,
    BarChart3,
} from "lucide-vue-next";

const props = defineProps({
    user: Object,
    appointmentsCount: Number,
    isExpert: Boolean,
});

const page = usePage();
const assets = computed(() => page.props.assets);
const isClient = computed(() =>
    props.user.roles.some((r) => r.toLowerCase() === "client")
);

// Copy Link Function
const copyLink = (slug) => {
    const url = `${window.location.origin}/client-${slug}`;
    navigator.clipboard.writeText(url);
    alert("Public Page URL copied to clipboard!");
};

// --- DATA STATISTIK (Disesuaikan per Role) ---
const stats = computed(() => {
    // 1. STATISTIK UNTUK CLIENT (Organisasi)
    if (isClient.value) {
        return [
            {
                label: "Page Visitors",
                value: "1.2k", // Mockup Data
                icon: Eye,
                color: "text-blue-600",
                bg: "bg-blue-50",
                trend: "+24% this week",
            },
            {
                label: "Experts Listed",
                value: "12", // Mockup / Bisa ambil dari count expertise
                icon: Users,
                color: "text-violet-600",
                bg: "bg-violet-50",
                trend: "Active",
            },
            {
                label: "Engagement Rate",
                value: "85%",
                icon: BarChart3,
                color: "text-emerald-600",
                bg: "bg-emerald-50",
                trend: "High",
            },
        ];
    }

    // 2. STATISTIK DEFAULT (User Biasa / Expert) - Appointment Based
    return [
        {
            label: "Total Appointments",
            value: props.appointmentsCount,
            icon: Briefcase,
            color: "text-violet-600",
            bg: "bg-violet-50",
            trend: "Upcoming",
        },
        {
            label: "Completed Sessions",
            value: 7, // Mockup
            icon: CheckCircle,
            color: "text-emerald-600",
            bg: "bg-emerald-50",
            trend: "All time",
        },
        {
            label: "Active Hours",
            value: "24h",
            icon: Clock,
            color: "text-orange-600",
            bg: "bg-orange-50",
            trend: "This month",
        },
    ];
});
</script>

<template>
    <div class="space-y-8">
        <div
            class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white shadow-xl shadow-slate-200/50 group"
        >
            <div
                class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-violet-600 rounded-full mix-blend-screen filter blur-[80px] opacity-30 animate-pulse"
            ></div>
            <div
                class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-fuchsia-600 rounded-full mix-blend-screen filter blur-[80px] opacity-30"
            ></div>
            <div
                class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"
            ></div>

            <div
                class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center md:items-start gap-8"
            >
                <div class="shrink-0 relative">
                    <div
                        class="w-28 h-28 md:w-32 md:h-32 rounded-full p-1 bg-gradient-to-br from-white/20 to-white/5 backdrop-blur-sm border border-white/10 shadow-2xl"
                    >
                        <img
                            :src="user.picture_url || assets.userPlaceholderUrl"
                            alt="Profile"
                            class="w-full h-full object-cover rounded-full transition-transform duration-700 group-hover:scale-105"
                        />
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-2">
                    <h1
                        class="text-3xl md:text-5xl font-display font-bold leading-tight tracking-tight"
                    >
                        Hi, {{ user.name.split(" ")[0] }}!
                    </h1>

                    <p class="text-slate-300 text-lg font-light max-w-xl">
                        <span v-if="isClient">
                            Manage your organization's public presence and
                            expert listings from here.
                        </span>
                        <span v-else>
                            Welcome to your dashboard. You have
                            <strong class="text-white">{{
                                appointmentsCount
                            }}</strong>
                            upcoming appointments.
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <div
                v-for="(stat, index) in stats"
                :key="index"
                class="group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden"
            >
                <div
                    class="absolute inset-0 bg-gradient-to-br from-white to-slate-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                ></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div
                            :class="`w-14 h-14 rounded-2xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110 ${stat.bg} ${stat.color}`"
                        >
                            <component :is="stat.icon" class="w-7 h-7" />
                        </div>
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-lg bg-slate-100 text-slate-500"
                        >
                            {{ stat.trend }}
                        </span>
                    </div>
                    <div>
                        <h3
                            class="text-3xl font-display font-bold text-slate-900 mb-1 tracking-tight"
                        >
                            {{ stat.value }}
                        </h3>
                        <p
                            class="text-slate-500 font-medium text-sm uppercase tracking-wide"
                        >
                            {{ stat.label }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="isClient && user.client"
            class="bg-white rounded-[2.5rem] border border-slate-200 shadow-lg overflow-hidden relative"
        >
            <div class="h-32 w-full bg-slate-100 relative">
                <img
                    v-if="user.client.banner_url"
                    :src="user.client.banner_url"
                    class="w-full h-full object-cover opacity-80"
                />
                <div
                    class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"
                ></div>
            </div>

            <div
                class="px-8 pb-8 relative -mt-12 flex flex-col md:flex-row gap-6 items-end md:items-center"
            >
                <div
                    class="w-24 h-24 bg-white rounded-2xl shadow-md p-2 flex items-center justify-center border border-slate-100 shrink-0"
                >
                    <img
                        :src="
                            user.client.logo_url ||
                            assets.clientLogoPlaceholderUrl
                        "
                        class="max-w-full max-h-full object-contain"
                    />
                </div>

                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-2xl font-bold text-slate-900">
                        {{
                            user.client.banner_title || "Your Organization Name"
                        }}
                    </h3>
                    <div
                        class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-2 text-sm text-slate-500"
                    >
                        <span
                            class="flex items-center gap-1 bg-slate-100 px-2 py-1 rounded-lg border border-slate-200"
                        >
                            <Layout class="w-3.5 h-3.5" />
                            {{ user.client.section_hero || "Organization" }}
                        </span>
                        <a
                            :href="route('home_client', user.client.slug_page)"
                            target="_blank"
                            class="flex items-center gap-1 hover:text-violet-600 transition-colors"
                        >
                            keyperson.id/client-{{ user.client.slug_page }}
                            <ExternalLink class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>

                <div class="flex gap-3 w-full md:w-auto">
                    <Link
                        :href="route('register_client')"
                        class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition-all shadow-lg hover:shadow-slate-900/20"
                    >
                        <Edit3 class="w-4 h-4" /> Edit Page
                    </Link>
                    <button
                        @click="copyLink(user.client.slug_page)"
                        class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 transition-all"
                    >
                        <Copy class="w-4 h-4" /> Share
                    </button>
                </div>
            </div>
        </div>

        <div
            v-else-if="isExpert && user.expert"
            class="bg-gradient-to-r from-violet-50 to-indigo-50 rounded-[2.5rem] border border-violet-100 p-8 flex flex-col md:flex-row items-center justify-between gap-6"
        >
            <div class="flex items-center gap-6">
                <div
                    class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm text-violet-600"
                >
                    <Briefcase class="w-8 h-8" />
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">
                        Expert Profile Status
                    </h3>
                    <p class="text-slate-500 text-sm mt-1">
                        Your profile is active and visible to public.
                    </p>
                </div>
            </div>
            <Link
                :href="route('register_expert')"
                class="px-6 py-3 bg-white text-violet-700 font-bold rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2"
            >
                <Settings class="w-4 h-4" /> Manage Profile
            </Link>
        </div>
    </div>
</template>
