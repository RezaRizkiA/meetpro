<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";

// Ambil shared data (Logo)
const page = usePage();
const logoUrl = computed(() => page.props.assets.logoUrl);
const googleIconUrl = computed(() => page.props.assets.googleIconUrl);

// Form Handling dengan Inertia
const form = useForm({
    email: "",
    password: "",
    remember: true,
});

const submit = () => {
    form.post(route("login_post"), {
        onFinish: () => form.reset("password"),
    });
};

// Logic Sederhana untuk Carousel/Slider di sebelah kanan
const slides = [
    {
        image: "/image/choose-expert.png", // Pastikan path ini benar di folder public
        title: "Choose Professional Expert",
        desc: "Tentukan expert yang sedang dibutuhkan, kenali profile dan jadwal available-nya.",
    },
    {
        image: "/image/complate-payment.png",
        title: "Payment & Make Appointment",
        desc: "Selesaikan pembayaran lalu buat janji dengan profesional yang dibutuhkan.",
    },
    {
        image: "/image/confirm-schedule.png",
        title: "Confirmation Schedule",
        desc: "Setelah appointment terbuat, konfirmasi dengan expert untuk jadwal fix.",
    },
];

const currentSlide = ref(0);
let intervalId;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};

onMounted(() => {
    intervalId = setInterval(nextSlide, 5000); // Ganti slide tiap 5 detik
});

onUnmounted(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <Head title="Sign In" />

    <div class="min-h-screen flex font-sans bg-slate-50 text-slate-600">
        <!-- Left Side: Form -->
        <div
            class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 bg-white relative z-10 shadow-[4px_0_24px_rgba(0,0,0,0.02)]"
        >
            <div class="mx-auto w-full max-w-sm lg:w-104">
                <div class="mb-12">
                    <Link
                        :href="route('home')"
                        class="inline-block transition-transform hover:scale-105 duration-300"
                    >
                        <img
                            :src="logoUrl"
                            alt="KeyPerson Logo"
                            class="h-11 w-auto"
                        />
                    </Link>
                </div>

                <div class="mb-10">
                    <h2
                        class="font-display text-4xl font-bold text-slate-900 tracking-tight"
                    >
                        Welcome back
                    </h2>
                    <p class="mt-3 text-base text-slate-500">
                        Please enter your details to sign in.
                    </p>
                </div>

                <div class="mb-8">
                    <a
                        :href="route('google.login')"
                        class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-white px-4 py-3.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 hover:ring-slate-300 hover:shadow-md transition-all duration-300"
                    >
                        <img
                            :src="googleIconUrl"
                            class="h-5 w-5 transition-transform group-hover:scale-110 duration-300"
                            alt="Google"
                        />
                        <span>Sign in with Google</span>
                    </a>
                </div>

                <div class="relative mb-8">
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div class="w-full border-t border-slate-100"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span
                            class="bg-white px-4 text-xs font-medium text-slate-400 uppercase tracking-widest"
                            >or with email</span
                        >
                    </div>
                </div>

                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <div>
                            <label
                                for="email"
                                class="block text-sm font-semibold text-slate-700 mb-2"
                                >Email</label
                            >
                            <div class="relative">
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    class="block w-full rounded-2xl border-0 bg-slate-50 px-4 py-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-300 focus:ring-red-500 bg-red-50':
                                            form.errors.email,
                                    }"
                                    placeholder="Enter your email"
                                />
                            </div>
                            <p
                                v-if="form.errors.email"
                                class="mt-2 text-sm text-red-600 flex items-center gap-1"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label
                                    for="password"
                                    class="block text-sm font-semibold text-slate-700"
                                    >Password</label
                                >
                                <a
                                    href="#"
                                    class="text-sm font-medium text-violet-600 hover:text-violet-500 transition-colors"
                                    >Forgot password?</a
                                >
                            </div>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    class="block w-full rounded-2xl border-0 bg-slate-50 px-4 py-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-violet-600 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-300 focus:ring-red-500 bg-red-50':
                                            form.errors.password,
                                    }"
                                    placeholder="Enter your password"
                                />
                            </div>
                            <p
                                v-if="form.errors.password"
                                class="mt-2 text-sm text-red-600 flex items-center gap-1"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-5 w-5 rounded-lg border-slate-300 text-violet-600 focus:ring-violet-600 transition-colors cursor-pointer"
                            />
                            <label
                                for="remember-me"
                                class="ml-3 block text-sm text-slate-600 select-none cursor-pointer"
                            >
                                Remember me
                            </label>
                        </div>

                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="group relative flex w-full justify-center rounded-2xl bg-slate-900 px-4 py-4 text-sm font-bold text-white shadow-lg shadow-slate-900/20 hover:bg-slate-800 hover:shadow-slate-900/30 hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none"
                            >
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                >
                                    <svg
                                        v-if="form.processing"
                                        class="animate-spin h-5 w-5 text-white group-hover:text-slate-200"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                </span>
                                {{
                                    form.processing
                                        ? "Signing in..."
                                        : "Sign In"
                                }}
                            </button>
                        </div>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm text-slate-500">
                    Don’t have an account?
                    <a
                        :href="route('google.login')"
                        class="font-bold text-violet-600 hover:text-violet-500 transition-colors"
                        >Sign Up free</a
                    >
                </p>

                <div class="mt-6 text-center text-xs text-slate-400">
                    By signing in, you agree to our
                    <Link
                        :href="route('terms')"
                        class="underline hover:text-slate-600"
                        >Terms</Link
                    >
                    and
                    <Link
                        :href="route('privacy')"
                        class="underline hover:text-slate-600"
                        >Privacy Policy</Link
                    >.
                </div>
            </div>
        </div>

        <!-- Right Side: Slider -->
        <div class="hidden lg:flex relative flex-1 bg-slate-50 overflow-hidden">
            <div
                class="absolute inset-0 bg-gradient-to-br from-violet-50 to-fuchsia-50"
            ></div>

            <!-- Decorative Blobs -->
            <div
                class="absolute top-0 right-0 -mr-20 -mt-20 w-160 h-160 bg-violet-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"
            ></div>
            <div
                class="absolute bottom-0 left-0 -ml-20 -mb-20 w-160 h-160 bg-fuchsia-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"
            ></div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-160 h-160 bg-pink-200/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"
            ></div>

            <div
                class="relative w-full h-full flex flex-col items-center justify-center p-16"
            >
                <transition name="fade" mode="out-in">
                    <div :key="currentSlide" class="text-center max-w-lg">
                        <div class="mb-12 relative group">
                            <div
                                class="absolute -inset-1 bg-linear-to-r from-violet-600 to-fuchsia-600 rounded-4xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"
                            ></div>
                            <div
                                class="relative bg-white/80 backdrop-blur-sm p-4 rounded-4xl shadow-2xl shadow-violet-500/10 border border-white/50 transform transition-transform duration-500 hover:scale-[1.02]"
                            >
                                <img
                                    :src="slides[currentSlide].image"
                                    :alt="slides[currentSlide].title"
                                    class="w-full h-auto rounded-3xl shadow-inner"
                                />
                            </div>
                        </div>
                        <h3
                            class="font-display text-3xl font-bold text-slate-900 mb-4 tracking-tight"
                        >
                            {{ slides[currentSlide].title }}
                        </h3>
                        <p
                            class="text-lg text-slate-500 leading-relaxed font-light"
                        >
                            {{ slides[currentSlide].desc }}
                        </p>
                    </div>
                </transition>

                <div class="absolute bottom-16 flex space-x-3">
                    <button
                        v-for="(slide, index) in slides"
                        :key="index"
                        @click="currentSlide = index"
                        class="h-2 rounded-full transition-all duration-500 ease-out"
                        :class="
                            currentSlide === index
                                ? 'bg-violet-600 w-10'
                                : 'bg-slate-300 w-2 hover:bg-slate-400'
                        "
                        :aria-label="'Go to slide ' + (index + 1)"
                    ></button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Transisi Slider */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}

.animate-blob {
    animation: blob 10s infinite;
}
.animation-delay-2000 {
    animation-delay: 5s;
}
.animation-delay-4000 {
    animation-delay: 8s;
}

@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
}
</style>
