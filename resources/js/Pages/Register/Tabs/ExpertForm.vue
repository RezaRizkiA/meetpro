<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import ExpertiseSelector from '../../../Components/ExpertiseSelector.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

// ICONS
import {
    Plus, Trash2, UploadCloud, FileText, Image as ImageIcon, Link as LinkIcon,
    User, Banknote, Briefcase, Building2, Calendar, Award, Globe, Loader2, Check, Save
} from 'lucide-vue-next';

const props = defineProps({
    user: Object,
    existingData: Object,
    expertises: Array
});

// --- HELPER: Safe JSON Parse ---
const parseData = (data, defaultValue = []) => {
    if (!data) return defaultValue;
    try {
        return Array.isArray(data) ? data : JSON.parse(data);
    } catch (e) {
        return defaultValue;
    }
};

// --- FORM SETUP ---
const form = useForm({
    title: props.existingData?.title || '', // Sebelumnya 'expertise'
    price: props.existingData?.price || '',
    biography: props.existingData?.biography || '',
    type: parseData(props.existingData?.type, []),

    experiences: parseData(props.existingData?.experiences, [{ position: '', company: '', years: '', description: '' }]),
    licenses: parseData(props.existingData?.licenses, [{ certification: '', attachment: null }]).map(item => ({ ...item, attachment: null, existing_attachment: item.attachment })),
    gallerys: parseData(props.existingData?.gallerys, [{ photos: null }]).map(item => ({ ...item, photos: null, existing_photos: item.photos })),
    socials: parseData(props.existingData?.socials, [{ key: 'instagram', value: '' }]),

    expertise_id: parseData(props.existingData?.expertise_id, []),
});

// --- REPEATER ACTIONS ---
// Experience
const addExperience = () => form.experiences.push({ position: '', company: '', years: '', description: '' });
const removeExperience = (index) => form.experiences.splice(index, 1);

// License
const addLicense = () => form.licenses.push({ certification: '', attachment: null });
const removeLicense = (index) => form.licenses.splice(index, 1);
const handleLicenseUpload = (e, index) => {
    const file = e.target.files[0];
    if (file) form.licenses[index].attachment = file;
};

// Gallery
const addGallery = () => form.gallerys.push({ photos: null });
const removeGallery = (index) => form.gallerys.splice(index, 1);
const galleryPreviews = ref({});
const handleGalleryUpload = (e, index) => {
    const file = e.target.files[0];
    if (file) {
        form.gallerys[index].photos = file;
        galleryPreviews.value[index] = URL.createObjectURL(file);
    }
};

// Socials
const addSocial = () => form.socials.push({ key: 'instagram', value: '' });
const removeSocial = (index) => form.socials.splice(index, 1);

// Submit
const submit = () => {
    form.post(route('register_expert_post'), {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="p-6 md:p-10">

        <div class="grid lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">
                    <div class="mb-8 pb-4 border-b border-slate-100">
                        <h3 class="font-display text-lg font-bold text-slate-900">Professional Details</h3>
                        <p class="text-sm text-slate-500">Basic information about your profession.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Professional
                                    Title</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <User
                                            class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                    </div>
                                    <input v-model="form.title" type="text"
                                        class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm"
                                        placeholder="e.g. Clinical Psychologist">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rate
                                    / Hour (IDR)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <Banknote
                                            class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                    </div>
                                    <input v-model="form.price" type="number"
                                        class="block w-full pl-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 transition-all duration-200 sm:text-sm"
                                        placeholder="e.g. 500000">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">I am
                                a:</label>
                            <div class="flex flex-wrap gap-3">
                                <label v-for="type in ['Counselor', 'Psychologist', 'Coach', 'Trainer', 'Consultant']"
                                    :key="type"
                                    class="cursor-pointer select-none inline-flex items-center px-4 py-2 rounded-xl border transition-all duration-200 text-sm font-medium"
                                    :class="form.type.includes(type)
                                        ? 'bg-violet-600 text-white border-violet-600 shadow-md shadow-violet-200'
                                        : 'bg-white text-slate-600 border-slate-200 hover:border-violet-300 hover:bg-slate-50'">
                                    <input type="checkbox" :value="type" v-model="form.type" class="hidden">
                                    <span>{{ type }}</span>
                                    <Check v-if="form.type.includes(type)" class="ml-2 w-4 h-4" />
                                </label>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Biography</label>
                            <div
                                class="bg-slate-50 rounded-xl overflow-hidden border border-slate-200 focus-within:border-violet-500 focus-within:ring-4 focus-within:ring-violet-500/10 transition-all">
                                <QuillEditor theme="snow" v-model:content="form.biography" contentType="html"
                                    toolbar="minimal" style="height: 150px;" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">
                    <div class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900">Work Experience</h3>
                            <p class="text-sm text-slate-500">Your professional background.</p>
                        </div>
                        <button type="button" @click="addExperience"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors">
                            <Plus class="w-4 h-4" /> Add
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div v-for="(exp, index) in form.experiences" :key="index"
                            class="relative p-6 bg-slate-50 border border-slate-100 rounded-2xl group hover:border-violet-200 transition-colors">
                            <button type="button" @click="removeExperience(index)"
                                class="absolute top-4 right-4 text-slate-400 hover:text-red-500 bg-white p-1.5 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-all">
                                <Trash2 class="w-4 h-4" />
                            </button>

                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <Briefcase class="w-4 h-4 text-slate-400" />
                                    </div>
                                    <input v-model="exp.position" type="text"
                                        class="block w-full pl-9 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-violet-500 focus:border-violet-500"
                                        placeholder="Position Title">
                                </div>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <Building2 class="w-4 h-4 text-slate-400" />
                                    </div>
                                    <input v-model="exp.company" type="text"
                                        class="block w-full pl-9 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-violet-500 focus:border-violet-500"
                                        placeholder="Company">
                                </div>
                            </div>
                            <div class="relative group mb-4">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Calendar class="w-4 h-4 text-slate-400" />
                                </div>
                                <input v-model="exp.years" type="text"
                                    class="block w-full pl-9 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-violet-500 focus:border-violet-500"
                                    placeholder="Years (e.g. 2018 - 2022)">
                            </div>
                            <textarea v-model="exp.description" rows="2"
                                class="block w-full p-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-violet-500 focus:border-violet-500"
                                placeholder="Job Description (Optional)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">
                    <div class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900">Certifications</h3>
                            <p class="text-sm text-slate-500">Upload your licenses or certificates.</p>
                        </div>
                        <button type="button" @click="addLicense"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors">
                            <Plus class="w-4 h-4" /> Add
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div v-for="(lic, index) in form.licenses" :key="index" class="flex items-start gap-3">
                            <div class="flex-1 space-y-2">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <Award class="w-4 h-4 text-slate-400" />
                                    </div>
                                    <input v-model="lic.certification" type="text"
                                        class="block w-full pl-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-violet-500 focus:border-violet-500"
                                        placeholder="Certification Name">
                                </div>
                                <div class="flex items-center gap-2">
                                    <label
                                        class="cursor-pointer inline-flex items-center gap-2 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition border border-slate-200">
                                        <UploadCloud class="w-3.5 h-3.5" />
                                        {{ lic.attachment ? 'File Selected' : 'Upload PDF/Image' }}
                                        <input type="file" class="hidden"
                                            @change="(e) => handleLicenseUpload(e, index)">
                                    </label>
                                    <a v-if="lic.existing_attachment" :href="`/storage/${lic.existing_attachment}`"
                                        target="_blank"
                                        class="text-xs text-violet-600 hover:underline flex items-center gap-1 bg-violet-50 px-2 py-1 rounded border border-violet-100">
                                        <LinkIcon class="w-3 h-3" /> View Current
                                    </a>
                                </div>
                            </div>
                            <button type="button" @click="removeLicense(index)"
                                class="mt-2 text-slate-300 hover:text-red-500 p-1">
                                <Trash2 class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">
                    <div class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900">Gallery</h3>
                            <p class="text-sm text-slate-500">Showcase your portfolio or activity.</p>
                        </div>
                        <button type="button" @click="addGallery"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors">
                            <Plus class="w-4 h-4" /> Add
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="(gal, index) in form.gallerys" :key="index"
                            class="relative group aspect-square bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex items-center justify-center overflow-hidden hover:border-violet-400 hover:bg-violet-50/30 transition-all">

                            <button type="button" @click="removeGallery(index)"
                                class="absolute top-2 right-2 bg-white rounded-full p-1.5 shadow-md text-red-500 z-20 opacity-0 group-hover:opacity-100 transition-opacity">
                                <Trash2 class="w-3.5 h-3.5" />
                            </button>

                            <input type="file" @change="(e) => handleGalleryUpload(e, index)"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">

                            <img v-if="galleryPreviews[index]" :src="galleryPreviews[index]"
                                class="w-full h-full object-cover">
                            <img v-else-if="gal.existing_photos" :src="`/storage/${gal.existing_photos}`"
                                class="w-full h-full object-cover">
                            <div v-else
                                class="text-center text-slate-400 pointer-events-none group-hover:text-violet-500 transition-colors">
                                <ImageIcon class="w-6 h-6 mx-auto mb-1" />
                                <span class="text-[10px] font-bold uppercase tracking-wider">Upload</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">
                    <div class="mb-8 pb-4 border-b border-slate-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900">Social Media</h3>
                            <p class="text-sm text-slate-500">Connect with your clients.</p>
                        </div>
                        <button type="button" @click="addSocial"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors">
                            <Plus class="w-4 h-4" /> Add
                        </button>
                    </div>

                    <div v-for="(soc, index) in form.socials" :key="index" class="flex gap-3 mb-4">
                        <div class="w-1/3 min-w-[120px]">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <Globe class="w-4 h-4 text-slate-400" />
                                </div>
                                <select v-model="soc.key"
                                    class="block w-full pl-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-violet-500 focus:border-violet-500 appearance-none">
                                    <option value="instagram">Instagram</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="website">Website</option>
                                    <option value="tiktok">TikTok</option>
                                </select>
                            </div>
                        </div>
                        <div class="relative flex-1">
                            <input v-model="soc.value" type="text"
                                class="block w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-violet-500 focus:border-violet-500 transition-all"
                                placeholder="URL or Username">
                            <button type="button" @click="removeSocial(index)"
                                class="absolute right-2 top-2.5 text-slate-300 hover:text-red-500">
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1 sticky top-6 z-20">
                <ExpertiseSelector :expertises="expertises" v-model="form.expertise_id"
                    label="Choose Your Specialist" />
            </div>

        </div>

        <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
            <a :href="route('profile')"
                class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition-all">
                Cancel
            </a>

            <button type="submit" :disabled="form.processing"
                class="px-8 py-3 rounded-xl font-bold text-sm transition-all duration-300 flex items-center gap-2 shadow-lg shadow-violet-200 transform active:scale-95 disabled:shadow-none disabled:opacity-50 disabled:cursor-not-allowed"
                :class="{
                    'bg-green-600 text-white hover:bg-green-700': form.recentlySuccessful,
                    'bg-slate-900 text-white hover:bg-slate-800': !form.recentlySuccessful
                }">
                <template v-if="form.processing">
                    <Loader2 class="animate-spin h-4 w-4" />
                    Saving...
                </template>

                <template v-else-if="form.recentlySuccessful">
                    <Check class="h-5 w-5" />
                    Saved!
                </template>

                <template v-else>
                    <Save class="h-4 w-4" />
                    Save & Update
                </template>
            </button>
        </div>

    </form>
</template>

<style>
/* Override style Quill agar match dengan input Tailwind */
.ql-toolbar {
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    border-color: #e2e8f0 !important;
    background-color: #f8fafc;
}

.ql-container {
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
    border-color: #e2e8f0 !important;
    background-color: #f8fafc;
    font-family: inherit;
    font-size: 0.875rem;
}

/* Efek fokus untuk editor (opsional, agak tricky di override CSS) */
.ql-container.ql-snow {
    border-top: none;
}
</style>