<script setup>
const props = defineProps({
    registerClientUrl: String,
    registerExpertUrl: String
});

const values = [
    {
        title: 'Ease of Access',
        desc: 'Just sign in with your Google account and start scheduling instantly. No complex setups required.',
        icon: 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
        class: 'text-blue-600 bg-blue-50 group-hover:bg-blue-600 group-hover:text-white'
    },
    {
        title: 'Transparency',
        desc: 'No hidden fees. Prices, schedules, and professional profiles are clearly displayed upfront.',
        icon: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
        class: 'text-violet-600 bg-violet-50 group-hover:bg-violet-600 group-hover:text-white'
    },
    {
        title: 'Privacy & Security',
        desc: 'Your data is encrypted and protected. We never share your personal information without explicit consent.',
        icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
        class: 'text-fuchsia-600 bg-fuchsia-50 group-hover:bg-fuchsia-600 group-hover:text-white'
    }
];
</script>

<template>
    <div class="font-sans text-slate-600 bg-white">

        <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-7xl opacity-50 pointer-events-none">
                <div
                    class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-violet-300 rounded-full mix-blend-multiply filter blur-[90px] animate-blob">
                </div>
                <div
                    class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-fuchsia-300 rounded-full mix-blend-multiply filter blur-[90px] animate-blob animation-delay-2000">
                </div>
                <div
                    class="absolute -bottom-32 left-1/2 w-[500px] h-[500px] bg-pink-300 rounded-full mix-blend-multiply filter blur-[90px] animate-blob animation-delay-4000">
                </div>
            </div>

            <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider mb-8 shadow-sm">
                    <span
                        class="w-2 h-2 rounded-full bg-gradient-to-r from-violet-500 to-fuchsia-500 animate-pulse"></span>
                    <span class="leading-none pt-[1px]">About Us</span>
                </div>

                <h1
                    class="font-display text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight mb-8 leading-[1.1]">
                    Empowering Growth through <br class="hidden md:block" />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500">
                        Meaningful Connections.
                    </span>
                </h1>

                <p class="text-xl md:text-2xl text-slate-500 max-w-2xl mx-auto mb-12 leading-relaxed font-light">
                    Keyperson.id is a smart platform connecting you with counselors, mentors, and coaches to support
                    your personal & professional journey.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-5">
                    <a :href="registerClientUrl"
                        class="inline-flex justify-center items-center px-8 py-4 text-base font-bold font-display text-white transition-all rounded-full bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500 shadow-lg shadow-violet-500/30 hover:shadow-violet-500/50 hover:-translate-y-1">
                        Start as Organizer
                    </a>
                    <a :href="registerExpertUrl"
                        class="inline-flex justify-center items-center px-8 py-4 text-base font-bold font-display text-slate-700 transition-all bg-white border-2 border-slate-100 rounded-full hover:border-violet-200 hover:text-violet-600 hover:bg-violet-50 hover:-translate-y-1">
                        Join as Professional
                    </a>
                </div>

                <p class="mt-12 text-sm font-semibold text-slate-400 uppercase tracking-widest">
                    Proudly Built by PT. Kastara Parama Nusantara
                </p>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-slate-50 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 pointer-events-none"
                style="background-image: radial-gradient(#6366f1 1px, transparent 1px); background-size: 24px 24px;">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="font-display text-4xl font-bold text-slate-900 mb-6 leading-tight">
                            Simplifying how you find <br>
                            <span class="text-violet-600">the right person.</span>
                        </h2>
                        <div class="space-y-6 text-lg text-slate-600 leading-relaxed">
                            <p>
                                We believe that finding a mentor or counselor shouldn't be complicated. Our goal is to
                                remove the friction of scheduling, payments, and administrative tasks.
                            </p>
                            <p>
                                With <span class="font-bold text-slate-900">KeyPerson</span>, appointments are scheduled
                                online and automatically synced to both your Google Calendar and the professional’s.
                                Simple, fast, and hassle-free.
                            </p>
                        </div>
                    </div>

                    <div class="relative group">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-violet-600 to-fuchsia-600 rounded-[2.5rem] transform rotate-2 opacity-20 group-hover:rotate-1 transition-transform duration-500">
                        </div>
                        <div
                            class="absolute inset-0 bg-gradient-to-l from-violet-600 to-fuchsia-600 rounded-[2.5rem] transform -rotate-2 opacity-20 group-hover:-rotate-1 transition-transform duration-500">
                        </div>

                        <div class="relative bg-white p-10 md:p-12 rounded-[2rem] shadow-xl border border-white">
                            <div class="w-16 h-16 bg-violet-100 rounded-2xl flex items-center justify-center mb-8">
                                <svg class="w-8 h-8 text-violet-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>

                            <h3
                                class="font-display text-2xl lg:text-3xl font-bold text-slate-900 leading-normal italic">
                                "To connect individuals and organizations with the right professionals, quickly and
                                efficiently."
                            </h3>

                            <div class="mt-8 flex items-center gap-3">
                                <div class="h-1 w-12 bg-gradient-to-r from-violet-500 to-fuchsia-500 rounded-full">
                                </div>
                                <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Our
                                    Mission</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <h2 class="font-display text-4xl font-bold text-slate-900 mb-6">Our Core Values</h2>
                    <p class="text-lg text-slate-500">
                        We build with principles that ensure trust, clarity, and ease for every user on our platform.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="(val, idx) in values" :key="idx"
                        class="group relative bg-white rounded-[2rem] p-10 border border-slate-100 shadow-sm hover:shadow-[0_20px_50px_rgba(8,_112,_184,_0.07)] hover:-translate-y-2 transition-all duration-500 overflow-hidden">

                        <div class="relative z-10">
                            <div
                                :class="`w-16 h-16 rounded-2xl flex items-center justify-center mb-8 transition-all duration-500 ${val.class}`">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        :d="val.icon" />
                                </svg>
                            </div>

                            <h3
                                class="font-display text-2xl font-bold text-slate-900 mb-4 group-hover:text-violet-700 transition-colors">
                                {{ val.title }}</h3>
                            <p class="text-slate-600 leading-relaxed font-medium group-hover:text-slate-700">
                                {{ val.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto bg-slate-900 rounded-[3rem] overflow-hidden relative shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900 to-violet-900 opacity-90">
                </div>
                <div
                    class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-fuchsia-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
                </div>
                <div
                    class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-violet-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
                </div>

                <div class="relative z-10 px-8 py-20 md:p-24 grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <div
                            class="inline-block px-4 py-1.5 rounded-full border border-slate-700 bg-slate-800/50 text-violet-300 text-xs font-bold uppercase tracking-wider mb-6">
                            The Team Behind
                        </div>
                        <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-8">
                            Who We Are
                        </h2>
                        <div class="space-y-6 text-lg text-slate-300 font-light leading-relaxed">
                            <p>
                                Keyperson.id is proudly developed and managed by <br>
                                <span class="text-white font-bold text-2xl tracking-tight">PT Kastara Parama
                                    Nusantara</span>.
                            </p>
                            <p class="opacity-80">
                                We are a technology team committed to building impactful digital solutions, particularly
                                in the areas of human development, education, and professional services.
                            </p>
                        </div>
                    </div>

                    <div class="hidden lg:flex justify-end gap-6">
                        <div
                            class="p-8 bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl text-center min-w-[160px]">
                            <div class="text-4xl font-bold text-white mb-2 font-display">100%</div>
                            <div class="text-slate-400 text-xs uppercase tracking-widest font-semibold">Commitment</div>
                        </div>
                        <div
                            class="p-8 bg-white/5 backdrop-blur-md border border-white/10 rounded-3xl text-center min-w-[160px]">
                            <div class="text-4xl font-bold text-white mb-2 font-display">24/7</div>
                            <div class="text-slate-400 text-xs uppercase tracking-widest font-semibold">Support Ready
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
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