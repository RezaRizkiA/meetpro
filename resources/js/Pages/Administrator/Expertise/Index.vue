<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import Swal from 'sweetalert2';
import { 
    Layers, Grid, Hash, Plus, Edit, Trash2, 
    Search, X, Save, FolderTree 
} from 'lucide-vue-next';

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    categories: Array,
    subCategories: Array,
    skills: Object // Paginated
});

// --- STATE MANAGEMENT ---
const activeTab = ref('categories'); // 'categories' | 'sub_categories' | 'skills'
const showModal = ref(false);
const isEditing = ref(false);
const editId = ref(null);

// Form Utama (Dynamic)
const form = useForm({
    name: '',
    icon: '', // Opsional untuk kategori
    category_id: '', // Untuk Sub-Category
    sub_category_id: '', // Untuk Skill
});

// --- COMPUTED TITLES ---
const tabTitle = computed(() => {
    if (activeTab.value === 'categories') return 'Categories';
    if (activeTab.value === 'sub_categories') return 'Sub-Categories';
    return 'Skills';
});

// --- ACTIONS: OPEN MODAL ---
const openModal = (type = 'create', item = null) => {
    isEditing.value = type === 'edit';
    showModal.value = true;
    form.clearErrors();

    if (type === 'edit' && item) {
        editId.value = item.id;
        form.name = item.name;
        form.icon = item.icon || '';
        form.category_id = item.category_id || '';
        form.sub_category_id = item.sub_category_id || '';
    } else {
        form.reset();
        editId.value = null;
        
        // Auto-select parent pertama jika ada data (UX improvement)
        if (activeTab.value === 'sub_categories' && props.categories.length > 0) {
            form.category_id = props.categories[0].id;
        }
        if (activeTab.value === 'skills' && props.subCategories.length > 0) {
            form.sub_category_id = props.subCategories[0].id;
        }
    }
};

// --- ACTIONS: SUBMIT ---
const submit = () => {
    let routeName = `dashboard.expertises.${activeTab.value.replace('_', '-')}`; // categories, sub-categories, skills
    
    if (isEditing.value) {
        form.put(route(`${routeName}.update`, editId.value), {
            onSuccess: () => {
                showModal.value = false;
                Swal.fire('Updated!', 'Data has been updated successfully.', 'success');
            }
        });
    } else {
        form.post(route(`${routeName}.store`), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
                Swal.fire('Created!', 'New data has been added.', 'success');
            }
        });
    }
};

// --- ACTIONS: DELETE ---
const deleteItem = (id) => {
    let routeName = `dashboard.expertises.${activeTab.value.replace('_', '-')}`;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route(`${routeName}.destroy`, id), {
                onSuccess: () => Swal.fire('Deleted!', 'Item has been deleted.', 'success')
            });
        }
    });
};
</script>

<template>
    <Head title="Expertise Management" />

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Expertise Management</h1>
                <p class="text-slate-500 mt-1">Manage skill trees, categories, and specific skills.</p>
            </div>
            
            <button @click="openModal('create')" 
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-violet-200">
                <Plus class="w-4 h-4" />
                Add New {{ tabTitle.slice(0, -1) }} </button>
        </div>

        <div class="border-b border-slate-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button 
                    @click="activeTab = 'categories'"
                    :class="[
                        activeTab === 'categories' 
                        ? 'border-violet-500 text-violet-600' 
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300',
                        'whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2'
                    ]">
                    <Layers class="w-4 h-4" />
                    Categories
                </button>

                <button 
                    @click="activeTab = 'sub_categories'"
                    :class="[
                        activeTab === 'sub_categories' 
                        ? 'border-violet-500 text-violet-600' 
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300',
                        'whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2'
                    ]">
                    <Grid class="w-4 h-4" />
                    Sub-Categories
                </button>

                <button 
                    @click="activeTab = 'skills'"
                    :class="[
                        activeTab === 'skills' 
                        ? 'border-violet-500 text-violet-600' 
                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300',
                        'whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2'
                    ]">
                    <Hash class="w-4 h-4" />
                    Skills
                </button>
            </nav>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            
            <div v-if="activeTab === 'categories'" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Category Name</th>
                            <th class="px-6 py-4 text-center">Sub-Categories</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="cat in categories" :key="cat.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ cat.name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs font-bold">{{ cat.sub_categories_count }} Items</span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button @click="openModal('edit', cat)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><Edit class="w-4 h-4" /></button>
                                <button @click="deleteItem(cat.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><Trash2 class="w-4 h-4" /></button>
                            </td>
                        </tr>
                        <tr v-if="categories.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">No categories found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="activeTab === 'sub_categories'" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Parent Category</th>
                            <th class="px-6 py-4">Sub-Category Name</th>
                            <th class="px-6 py-4 text-center">Skills</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="sub in subCategories" :key="sub.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-500">{{ sub.category?.name || '-' }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ sub.name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-bold">{{ sub.skills_count }} Skills</span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button @click="openModal('edit', sub)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><Edit class="w-4 h-4" /></button>
                                <button @click="deleteItem(sub.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><Trash2 class="w-4 h-4" /></button>
                            </td>
                        </tr>
                        <tr v-if="subCategories.length === 0">
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">No sub-categories found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="activeTab === 'skills'" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Skill Name</th>
                            <th class="px-6 py-4">Category Path</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="skill in skills.data" :key="skill.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ skill.name }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                <span class="px-2 py-1 bg-slate-100 rounded text-xs">
                                    {{ skill.sub_category?.category?.name }}
                                </span>
                                <span class="mx-1">/</span>
                                <span class="px-2 py-1 bg-slate-100 rounded text-xs text-slate-700">
                                    {{ skill.sub_category?.name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button @click="openModal('edit', skill)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><Edit class="w-4 h-4" /></button>
                                <button @click="deleteItem(skill.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><Trash2 class="w-4 h-4" /></button>
                            </td>
                        </tr>
                        <tr v-if="skills.data.length === 0">
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">No skills found.</td>
                        </tr>
                    </tbody>
                </table>
                
                <div v-if="skills.links.length > 3" class="px-6 py-4 border-t border-slate-100">
                    <div class="flex gap-1 justify-end">
                        <template v-for="(link, k) in skills.links" :key="k">
                            <Link v-if="link.url" :href="link.url" v-html="link.label"
                                class="px-3 py-1 text-xs rounded border"
                                :class="link.active ? 'bg-violet-600 text-white border-violet-600' : 'bg-white text-slate-600 border-slate-200'" />
                        </template>
                    </div>
                </div>
            </div>

        </div>

        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-900">
                        {{ isEditing ? 'Edit' : 'Create New' }} {{ tabTitle.slice(0, -1) }}
                    </h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Name</label>
                        <input type="text" v-model="form.name" required placeholder="Enter name..."
                            class="w-full rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 text-sm">
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>

                    <div v-if="activeTab === 'sub_categories'">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Parent Category</label>
                        <select v-model="form.category_id" required class="w-full rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 text-sm">
                            <option value="" disabled>Select Category</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">{{ form.errors.category_id }}</p>
                    </div>

                    <div v-if="activeTab === 'skills'">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Parent Sub-Category</label>
                        <select v-model="form.sub_category_id" required class="w-full rounded-xl border-slate-200 focus:ring-violet-500 focus:border-violet-500 text-sm">
                            <option value="" disabled>Select Sub-Category</option>
                            <option v-for="sub in subCategories" :key="sub.id" :value="sub.id">
                                {{ sub.category?.name }} > {{ sub.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.sub_category_id" class="text-red-500 text-xs mt-1">{{ form.errors.sub_category_id }}</p>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 disabled:opacity-50 flex items-center gap-2">
                            <Save class="w-4 h-4" />
                            {{ isEditing ? 'Save Changes' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>