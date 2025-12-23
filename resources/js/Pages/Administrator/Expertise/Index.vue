<script setup>
import { ref, computed, watch } from "vue";
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import Swal from "sweetalert2";
import {
    Layers,
    Grid,
    Hash,
    Plus,
    Edit,
    Trash2,
    Search,
    X,
    Save,
    FolderTree,
    ChevronRight,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    categories: Array,
    subCategories: Array,
    skills: Object, // Paginated
});

// --- STATE MANAGEMENT ---
const activeTab = ref("categories"); // 'categories' | 'sub_categories' | 'skills'
const showModal = ref(false);
const isEditing = ref(false);
const editId = ref(null);

// Form Utama (Dynamic)
const form = useForm({
    name: "",
    icon: "", // Opsional untuk kategori
    category_id: "", // Untuk Sub-Category
    sub_category_id: "", // Untuk Skill
});

// --- COMPUTED TITLES ---
const tabTitle = computed(() => {
    if (activeTab.value === "categories") return "Categories";
    if (activeTab.value === "sub_categories") return "Sub-Categories";
    return "Skills";
});

// --- ACTIONS: OPEN MODAL ---
const openModal = (type = "create", item = null) => {
    isEditing.value = type === "edit";
    showModal.value = true;
    form.clearErrors();

    if (type === "edit" && item) {
        editId.value = item.id;
        form.name = item.name;
        form.icon = item.icon || "";
        form.category_id = item.category_id || "";
        form.sub_category_id = item.sub_category_id || "";
    } else {
        form.reset();
        editId.value = null;

        // Auto-select parent pertama jika ada data (UX improvement)
        if (
            activeTab.value === "sub_categories" &&
            props.categories.length > 0
        ) {
            form.category_id = props.categories[0].id;
        }
        if (activeTab.value === "skills" && props.subCategories.length > 0) {
            form.sub_category_id = props.subCategories[0].id;
        }
    }
};

// --- ACTIONS: SUBMIT ---
const submit = () => {
    let routeName = `dashboard.expertises.${activeTab.value.replace("_", "-")}`; // categories, sub-categories, skills

    if (isEditing.value) {
        form.put(route(`${routeName}.update`, editId.value), {
            onSuccess: () => {
                showModal.value = false;
                Swal.fire({
                    title: "Updated!",
                    text: "Data has been updated successfully.",
                    icon: "success",
                    background: "#1e293b",
                    color: "#e2e8f0",
                    confirmButtonColor: "#3b82f6",
                });
            },
        });
    } else {
        form.post(route(`${routeName}.store`), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
                Swal.fire({
                    title: "Created!",
                    text: "New data has been added.",
                    icon: "success",
                    background: "#1e293b",
                    color: "#e2e8f0",
                    confirmButtonColor: "#3b82f6",
                });
            },
        });
    }
};

// --- ACTIONS: DELETE ---
const deleteItem = (id) => {
    let routeName = `dashboard.expertises.${activeTab.value.replace("_", "-")}`;

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, delete it!",
        background: "#1e293b",
        color: "#e2e8f0",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route(`${routeName}.destroy`, id), {
                onSuccess: () =>
                    Swal.fire({
                        title: "Deleted!",
                        text: "Item has been deleted.",
                        icon: "success",
                        background: "#1e293b",
                        color: "#e2e8f0",
                        confirmButtonColor: "#3b82f6",
                    }),
            });
        }
    });
};
</script>

<template>
    <Head title="Expertise Management" />

    <div class="space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-slate-100">
                    Expertise Management
                </h1>
                <p class="text-slate-400 mt-1">
                    Manage skill trees, categories, and specific skills.
                </p>
            </div>

            <button
                @click="openModal('create')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-500/20"
            >
                <Plus class="w-4 h-4" />
                Add New {{ tabTitle.slice(0, -1) }}
            </button>
        </div>

        <!-- Tabs Navigation -->
        <div
            class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-1.5"
        >
            <nav class="flex gap-1" aria-label="Tabs">
                <button
                    @click="activeTab = 'categories'"
                    :class="[
                        activeTab === 'categories'
                            ? 'bg-slate-700 text-slate-100 shadow-lg'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50',
                        'flex-1 py-3 px-4 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all',
                    ]"
                >
                    <Layers class="w-4 h-4" />
                    Categories
                    <span
                        class="px-2 py-0.5 rounded-full text-xs bg-slate-600 text-slate-300"
                        >{{ categories.length }}</span
                    >
                </button>

                <button
                    @click="activeTab = 'sub_categories'"
                    :class="[
                        activeTab === 'sub_categories'
                            ? 'bg-slate-700 text-slate-100 shadow-lg'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50',
                        'flex-1 py-3 px-4 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all',
                    ]"
                >
                    <Grid class="w-4 h-4" />
                    Sub-Categories
                    <span
                        class="px-2 py-0.5 rounded-full text-xs bg-slate-600 text-slate-300"
                        >{{ subCategories.length }}</span
                    >
                </button>

                <button
                    @click="activeTab = 'skills'"
                    :class="[
                        activeTab === 'skills'
                            ? 'bg-slate-700 text-slate-100 shadow-lg'
                            : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50',
                        'flex-1 py-3 px-4 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all',
                    ]"
                >
                    <Hash class="w-4 h-4" />
                    Skills
                    <span
                        class="px-2 py-0.5 rounded-full text-xs bg-slate-600 text-slate-300"
                        >{{ skills.total || skills.data?.length || 0 }}</span
                    >
                </button>
            </nav>
        </div>

        <!-- Content Table -->
        <div
            class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 overflow-hidden"
        >
            <!-- Categories Table -->
            <div v-if="activeTab === 'categories'" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="bg-slate-900/50 text-slate-400 font-semibold uppercase text-xs tracking-wider border-b border-slate-700/50"
                    >
                        <tr>
                            <th class="px-6 py-4">Category Name</th>
                            <th class="px-6 py-4 text-center">
                                Sub-Categories
                            </th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        <tr
                            v-for="cat in categories"
                            :key="cat.id"
                            class="hover:bg-slate-700/30 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white"
                                    >
                                        <Layers class="w-5 h-5" />
                                    </div>
                                    <span class="font-bold text-slate-100">{{
                                        cat.name
                                    }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1.5 bg-slate-700 text-slate-300 rounded-lg text-xs font-bold"
                                >
                                    {{ cat.sub_categories_count }} Items
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button
                                        @click="openModal('edit', cat)"
                                        class="p-2 text-blue-400 hover:bg-blue-500/20 rounded-lg transition-colors"
                                    >
                                        <Edit class="w-4 h-4" />
                                    </button>
                                    <button
                                        @click="deleteItem(cat.id)"
                                        class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="categories.length === 0">
                            <td
                                colspan="3"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                <Layers
                                    class="w-12 h-12 mx-auto mb-3 opacity-50"
                                />
                                <p>No categories found.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sub-Categories Table -->
            <div v-if="activeTab === 'sub_categories'" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="bg-slate-900/50 text-slate-400 font-semibold uppercase text-xs tracking-wider border-b border-slate-700/50"
                    >
                        <tr>
                            <th class="px-6 py-4">Parent Category</th>
                            <th class="px-6 py-4">Sub-Category Name</th>
                            <th class="px-6 py-4 text-center">Skills</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        <tr
                            v-for="sub in subCategories"
                            :key="sub.id"
                            class="hover:bg-slate-700/30 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1.5 bg-violet-500/20 text-violet-400 rounded-lg text-xs font-semibold border border-violet-500/30"
                                >
                                    {{ sub.category?.name || "-" }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white"
                                    >
                                        <Grid class="w-5 h-5" />
                                    </div>
                                    <span class="font-bold text-slate-100">{{
                                        sub.name
                                    }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1.5 bg-blue-500/20 text-blue-400 rounded-lg text-xs font-bold border border-blue-500/30"
                                >
                                    {{ sub.skills_count }} Skills
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button
                                        @click="openModal('edit', sub)"
                                        class="p-2 text-blue-400 hover:bg-blue-500/20 rounded-lg transition-colors"
                                    >
                                        <Edit class="w-4 h-4" />
                                    </button>
                                    <button
                                        @click="deleteItem(sub.id)"
                                        class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="subCategories.length === 0">
                            <td
                                colspan="4"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                <Grid
                                    class="w-12 h-12 mx-auto mb-3 opacity-50"
                                />
                                <p>No sub-categories found.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Skills Table -->
            <div v-if="activeTab === 'skills'" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="bg-slate-900/50 text-slate-400 font-semibold uppercase text-xs tracking-wider border-b border-slate-700/50"
                    >
                        <tr>
                            <th class="px-6 py-4">Skill Name</th>
                            <th class="px-6 py-4">Category Path</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        <tr
                            v-for="skill in skills.data"
                            :key="skill.id"
                            class="hover:bg-slate-700/30 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white"
                                    >
                                        <Hash class="w-5 h-5" />
                                    </div>
                                    <span class="font-bold text-slate-100">{{
                                        skill.name
                                    }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <span
                                        class="px-2 py-1 bg-slate-700 text-slate-300 rounded text-xs"
                                    >
                                        {{ skill.sub_category?.category?.name }}
                                    </span>
                                    <ChevronRight
                                        class="w-3 h-3 text-slate-600"
                                    />
                                    <span
                                        class="px-2 py-1 bg-slate-700 text-slate-300 rounded text-xs"
                                    >
                                        {{ skill.sub_category?.name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <button
                                        @click="openModal('edit', skill)"
                                        class="p-2 text-blue-400 hover:bg-blue-500/20 rounded-lg transition-colors"
                                    >
                                        <Edit class="w-4 h-4" />
                                    </button>
                                    <button
                                        @click="deleteItem(skill.id)"
                                        class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="skills.data.length === 0">
                            <td
                                colspan="3"
                                class="px-6 py-12 text-center text-slate-500"
                            >
                                <Hash
                                    class="w-12 h-12 mx-auto mb-3 opacity-50"
                                />
                                <p>No skills found.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div
                    v-if="skills.links && skills.links.length > 3"
                    class="px-6 py-4 border-t border-slate-700/50"
                >
                    <div class="flex gap-1 justify-end">
                        <template v-for="(link, k) in skills.links" :key="k">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                class="px-3 py-1.5 text-xs rounded-lg font-medium transition-colors"
                                :class="
                                    link.active
                                        ? 'bg-blue-500 text-white'
                                        : 'bg-slate-700 text-slate-300 hover:bg-slate-600'
                                "
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Teleport to="body">
            <div
                v-if="showModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md"
                @click.self="showModal = false"
            >
                <div
                    class="bg-slate-900/95 backdrop-blur-xl border border-slate-700/50 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all"
                >
                    <!-- Modal Header with Gradient -->
                    <div
                        class="relative px-6 py-5 border-b border-slate-700/50 overflow-hidden"
                    >
                        <!-- Background Decoration -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600/20 via-violet-600/20 to-purple-600/20"
                        ></div>
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-full -mr-16 -mt-16"
                        ></div>

                        <div class="relative flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <!-- Dynamic Icon based on tab -->
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center"
                                    :class="[
                                        activeTab === 'categories'
                                            ? 'bg-gradient-to-br from-violet-500 to-purple-600'
                                            : activeTab === 'sub_categories'
                                            ? 'bg-gradient-to-br from-blue-500 to-cyan-600'
                                            : 'bg-gradient-to-br from-emerald-500 to-teal-600',
                                    ]"
                                >
                                    <Layers
                                        v-if="activeTab === 'categories'"
                                        class="w-5 h-5 text-white"
                                    />
                                    <Grid
                                        v-else-if="
                                            activeTab === 'sub_categories'
                                        "
                                        class="w-5 h-5 text-white"
                                    />
                                    <Hash v-else class="w-5 h-5 text-white" />
                                </div>
                                <div>
                                    <h3
                                        class="font-bold text-slate-100 text-lg"
                                    >
                                        {{ isEditing ? "Edit" : "Create New" }}
                                        {{ tabTitle.slice(0, -1) }}
                                    </h3>
                                    <p class="text-xs text-slate-400">
                                        {{
                                            isEditing
                                                ? "Update the details below"
                                                : "Fill in the details below"
                                        }}
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="showModal = false"
                                class="text-slate-400 hover:text-slate-200 p-2 hover:bg-slate-700/50 rounded-xl transition-all"
                            >
                                <X class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <form @submit.prevent="submit" class="p-6 space-y-5">
                        <!-- Name Field -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1 h-1 rounded-full bg-blue-400"
                                    ></span>
                                    Name
                                </span>
                            </label>
                            <input
                                type="text"
                                v-model="form.name"
                                required
                                placeholder="Enter name..."
                                class="w-full rounded-xl bg-slate-800/50 border-slate-700 text-slate-100 placeholder-slate-500 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm py-3 px-4 transition-all"
                            />
                            <p
                                v-if="form.errors.name"
                                class="text-red-400 text-xs mt-2 flex items-center gap-1"
                            >
                                <span
                                    class="w-1 h-1 rounded-full bg-red-400"
                                ></span>
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Parent Category (for Sub-Categories) -->
                        <div v-if="activeTab === 'sub_categories'">
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1 h-1 rounded-full bg-violet-400"
                                    ></span>
                                    Parent Category
                                </span>
                            </label>
                            <select
                                v-model="form.category_id"
                                required
                                class="w-full rounded-xl bg-slate-800/50 border-slate-700 text-slate-100 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm py-3 px-4 transition-all"
                            >
                                <option value="" disabled class="bg-slate-800">
                                    Select Category
                                </option>
                                <option
                                    v-for="cat in categories"
                                    :key="cat.id"
                                    :value="cat.id"
                                    class="bg-slate-800"
                                >
                                    {{ cat.name }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.category_id"
                                class="text-red-400 text-xs mt-2 flex items-center gap-1"
                            >
                                <span
                                    class="w-1 h-1 rounded-full bg-red-400"
                                ></span>
                                {{ form.errors.category_id }}
                            </p>
                        </div>

                        <!-- Parent Sub-Category (for Skills) -->
                        <div v-if="activeTab === 'skills'">
                            <label
                                class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2"
                            >
                                <span class="flex items-center gap-2">
                                    <span
                                        class="w-1 h-1 rounded-full bg-emerald-400"
                                    ></span>
                                    Parent Sub-Category
                                </span>
                            </label>
                            <select
                                v-model="form.sub_category_id"
                                required
                                class="w-full rounded-xl bg-slate-800/50 border-slate-700 text-slate-100 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 text-sm py-3 px-4 transition-all"
                            >
                                <option value="" disabled class="bg-slate-800">
                                    Select Sub-Category
                                </option>
                                <option
                                    v-for="sub in subCategories"
                                    :key="sub.id"
                                    :value="sub.id"
                                    class="bg-slate-800"
                                >
                                    {{ sub.category?.name }} → {{ sub.name }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.sub_category_id"
                                class="text-red-400 text-xs mt-2 flex items-center gap-1"
                            >
                                <span
                                    class="w-1 h-1 rounded-full bg-red-400"
                                ></span>
                                {{ form.errors.sub_category_id }}
                            </p>
                        </div>

                        <!-- Modal Footer -->
                        <div
                            class="pt-4 flex justify-end gap-3 border-t border-slate-700/50 mt-6"
                        >
                            <button
                                type="button"
                                @click="showModal = false"
                                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-sm font-semibold text-slate-300 transition-all"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl text-sm font-bold transition-all disabled:opacity-50 flex items-center gap-2 shadow-lg shadow-blue-500/25"
                            >
                                <Save class="w-4 h-4" />
                                {{ isEditing ? "Save Changes" : "Create" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>
    </div>
</template>
