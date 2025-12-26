<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted, onUnmounted } from "vue";

// Ambil shared data (Logo)
const page = usePage();
const logoSmallUrl = computed(() => page.props.assets.logoSmallUrl);
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
        image: "/image/choose-expert.png",
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
const showPassword = ref(false);
let intervalId;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};

onMounted(() => {
    intervalId = setInterval(nextSlide, 5000);
});

onUnmounted(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <Head title="Sign In" />

    <div class="min-h-screen flex font-sans bg-background text-foreground">
        <!-- Left Side: Form -->
        <div
            class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:px-20 xl:px-24 relative z-10"
        >
            <!-- Glow Background -->
            <div
                class="absolute top-0 left-0 w-[500px] h-[500px] bg-blue-500/10 rounded-full blur-[150px] -z-10"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-cyan-500/10 rounded-full blur-[120px] -z-10"
            ></div>

            <div class="mx-auto w-full max-w-sm lg:w-104">
                <!-- Logo -->
                <div class="mb-12">
                    <Link
                        :href="route('home')"
                        class="inline-flex items-center gap-2 group transition-transform hover:scale-105 duration-300"
                    >
                        <img
                            :src="logoSmallUrl"
                            alt="KeyPerson Logo"
                            class="h-10 w-auto"
                        />
                        <span
                            class="font-display font-bold text-xl tracking-tight text-white"
                        >
                            Key<span class="text-blue-400">Person</span>
                        </span>
                    </Link>
                </div>

                <!-- Header -->
                <div class="mb-10">
                    <h2
                        class="font-display text-4xl font-bold text-foreground tracking-tight"
                    >
                        Welcome back
                    </h2>
                    <p class="mt-3 text-base text-muted">
                        Please enter your details to sign in.
                    </p>
                </div>

                <!-- Google Sign In -->
                <div class="mb-8">
                    <a
                        :href="route('google.login')"
                        class="group flex w-full items-center justify-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-bold text-foreground shadow-lg transition-all duration-300 bg-surface backdrop-blur-sm border border-border hover:bg-surface-hover hover:border-blue-500/30 hover:shadow-[0_0_20px_rgba(59,130,246,0.2)]"
                    >
                        <img
                            :src="googleIconUrl"
                            class="h-5 w-5 transition-transform group-hover:scale-110 duration-300"
                            alt="Google"
                        />
                        <span>Sign in with Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="relative mb-8">
                    <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                    >
                        <div class="w-full border-t border-border"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span
                            class="bg-background px-4 text-xs font-medium text-muted uppercase tracking-widest"
                            >or with email</span
                        >
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="block text-sm font-semibold text-muted mb-2"
                                >Email</label
                            >
                            <div class="relative">
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    class="block w-full rounded-2xl border-0 bg-slate-800/50 backdrop-blur-sm px-4 py-4 text-white shadow-sm ring-1 ring-inset ring-slate-700/50 placeholder:text-slate-500 focus:bg-slate-800/70 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-500/50 focus:ring-red-500 bg-red-500/10':
                                            form.errors.email,
                                    }"
                                    placeholder="Enter your email"
                                />
                            </div>
                            <p
                                v-if="form.errors.email"
                                class="mt-2 text-sm text-red-400 flex items-center gap-1"
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

                        <!-- Password -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label
                                    for="password"
                                    class="block text-sm font-semibold text-slate-300"
                                    >Password</label
                                >
                                <a
                                    href="#"
                                    class="text-sm font-medium text-blue-400 hover:text-blue-300 transition-colors"
                                    >Forgot password?</a
                                >
                            </div>
                            <div class="relative">
                                <input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    autocomplete="current-password"
                                    required
                                    class="block w-full rounded-2xl border-0 bg-slate-800/50 backdrop-blur-sm px-4 py-4 pr-12 text-white shadow-sm ring-1 ring-inset ring-slate-700/50 placeholder:text-slate-500 focus:bg-slate-800/70 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 transition-all duration-200"
                                    :class="{
                                        'ring-red-500/50 focus:ring-red-500 bg-red-500/10':
                                            form.errors.password,
                                    }"
                                    placeholder="Enter your password"
                                />
                                <!-- Toggle Password Visibility -->
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-400 transition-colors focus:outline-none"
                                >
                                    <!-- Eye Icon (Show) -->
                                    <svg
                                        v-if="!showPassword"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                    <!-- Eye Off Icon (Hide) -->
                                    <svg
                                        v-else
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <p
                                v-if="form.errors.password"
                                class="mt-2 text-sm text-red-400 flex items-center gap-1"
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

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                v-model="form.remember"
                                type="checkbox"
                                class="h-5 w-5 rounded-lg border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500 focus:ring-offset-slate-900 transition-colors cursor-pointer"
                            />
                            <label
                                for="remember-me"
                                class="ml-3 block text-sm text-slate-400 select-none cursor-pointer"
                            >
                                Remember me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="group relative flex w-full justify-center rounded-2xl bg-blue-600 px-4 py-4 text-sm font-bold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-500 hover:shadow-[0_0_30px_rgba(59,130,246,0.5)] hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none"
                            >
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                >
                                    <svg
                                        v-if="form.processing"
                                        class="animate-spin h-5 w-5 text-white group-hover:text-blue-200"
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

                <!-- Sign Up Link -->
                <p class="mt-10 text-center text-sm text-muted">
                    Don't have an account?
                    <a
                        :href="route('google.login')"
                        class="font-bold text-blue-400 hover:text-blue-300 transition-colors"
                        >Sign Up free</a
                    >
                </p>

                <!-- Terms -->
                <div class="mt-6 text-center text-xs text-muted-foreground">
                    By signing in, you agree to our
                    <Link
                        :href="route('terms')"
                        class="underline hover:text-slate-300 transition-colors"
                        >Terms</Link
                    >
                    and
                    <Link
                        :href="route('privacy')"
                        class="underline hover:text-slate-300 transition-colors"
                        >Privacy Policy</Link
                    >.
                </div>
            </div>
        </div>

        <!-- Right Side: Slider -->
        <div class="hidden lg:flex relative flex-1 overflow-hidden">
            <!-- Gradient Background -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900"
            ></div>

            <!-- Decorative Glow Blobs -->
            <div
                class="absolute top-0 right-0 -mr-20 -mt-20 w-[500px] h-[500px] bg-blue-500/20 rounded-full mix-blend-multiply filter blur-[100px] animate-blob"
            ></div>
            <div
                class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[500px] h-[500px] bg-cyan-500/20 rounded-full mix-blend-multiply filter blur-[100px] animate-blob animation-delay-2000"
            ></div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-blue-600/20 rounded-full mix-blend-multiply filter blur-[100px] animate-blob animation-delay-4000"
            ></div>

            <div
                class="relative w-full h-full flex flex-col items-center justify-center p-16"
            >
                <transition name="fade" mode="out-in">
                    <div :key="currentSlide" class="text-center max-w-lg">
                        <div class="mb-12 relative group">
                            <!-- Glow Effect -->
                            <div
                                class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-3xl blur opacity-30 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"
                            ></div>
                            <!-- Glassmorphism Card -->
                            <div
                                class="relative bg-slate-800/50 backdrop-blur-xl p-4 rounded-3xl shadow-2xl border border-slate-700/50 transform transition-transform duration-500 hover:scale-[1.02]"
                            >
                                <img
                                    :src="slides[currentSlide].image"
                                    :alt="slides[currentSlide].title"
                                    class="w-full h-auto rounded-2xl"
                                />
                            </div>
                        </div>
                        <h3
                            class="font-display text-3xl font-bold text-white mb-4 tracking-tight"
                        >
                            {{ slides[currentSlide].title }}
                        </h3>
                        <p class="text-lg text-slate-400 leading-relaxed">
                            {{ slides[currentSlide].desc }}
                        </p>
                    </div>
                </transition>

                <!-- Slide Indicators -->
                <div class="absolute bottom-16 flex space-x-3">
                    <button
                        v-for="(slide, index) in slides"
                        :key="index"
                        @click="currentSlide = index"
                        class="h-2 rounded-full transition-all duration-500 ease-out"
                        :class="
                            currentSlide === index
                                ? 'bg-blue-500 w-10 shadow-[0_0_10px_rgba(59,130,246,0.5)]'
                                : 'bg-slate-600 w-2 hover:bg-slate-500'
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
