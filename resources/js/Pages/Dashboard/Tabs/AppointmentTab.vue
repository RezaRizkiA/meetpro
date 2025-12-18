<script setup>
import { ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { Filter } from "lucide-vue-next";


// Import Partials
import AppointmentFilter from "./Partials/Appointment/Filter.vue";
import AppointmentCard from "./Partials/Appointment/Card.vue";
import AppointmentActionModal from "./Partials/Appointment/ActionModal.vue";
import AppointmentRescheduleModal from "./Partials/Appointment/RescheduleModal.vue";

const props = defineProps({
    appointments: Array,
    isExpert: Boolean,
    isAdmin: Boolean,
});

// State
const searchQuery = ref("");
const activeFilter = ref("all");
const showActionModal = ref(false);
const showRescheduleModal = ref(false);

const actionState = ref({ id: null, type: "", title: "", message: "" });
const editingAppointment = ref(null);
const editForm = useForm({ date: "", time: "" });

// Filter Logic
const filteredAppointments = computed(() => {
    let result = props.appointments || [];
    if (activeFilter.value !== "all") {
        if (activeFilter.value === "pending")
            result = result.filter((a) =>
                ["need_confirmation", "paid"].includes(a.status)
            );
        else if (activeFilter.value === "upcoming")
            result = result.filter((a) =>
                ["confirmed", "progress"].includes(a.status)
            );
        else result = result.filter((a) => a.status === activeFilter.value);
    }
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter((a) => {
            const name = props.isExpert ? a.user?.name : a.expert?.user?.name;
            return name ? name.toLowerCase().includes(query) : false;
        });
    }
    return result;
});

// Handlers
const openActionModal = (id, type) => {
    // Logic modal sama seperti sebelumnya...
    // (Pindahkan logic text title/message ke dalam AppointmentActionModal jika mau lebih bersih lagi)
    actionState.value = {
        id,
        type,
        title: "Confirm Action",
        message: "Are you sure?",
    }; // Sederhanakan trigger
    if (type === "progress") actionState.value.message = "Accept this session?";
    if (type === "declined") actionState.value.message = "Decline and refund?";
    if (type === "completed") actionState.value.message = "Mark as finished?";
    showActionModal.value = true;
};

const submitAction = () => {
    router.post(
        route("appointment.update_status", actionState.value.id),
        { status: actionState.value.type },
        {
            preserveScroll: true,
            onSuccess: () => (showActionModal.value = false),
        }
    );
};

const openRescheduleModal = (appointment) => {
    editingAppointment.value = appointment;
    const d = new Date(appointment.date_time);
    editForm.date = d.toISOString().split("T")[0];
    editForm.time = d.toTimeString().slice(0, 5);
    showRescheduleModal.value = true;
};

const submitReschedule = () => {
    editForm.put(
        route("appointment.edit_schedule", editingAppointment.value.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                showRescheduleModal.value = false;
                editForm.reset();
            },
        }
    );
};
</script>

<template>
    <div class="min-h-[500px]">
        <AppointmentFilter
            v-model:activeFilter="activeFilter"
            v-model:searchQuery="searchQuery"
        />

        <div
            v-if="filteredAppointments.length === 0"
            class="flex flex-col items-center justify-center py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200"
        >
            <div
                class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4"
            >
                <Filter class="w-8 h-8 text-slate-300" />
            </div>
            <h3 class="text-slate-900 font-bold text-lg">
                No appointments found
            </h3>
            <button
                v-if="activeFilter !== 'all' || searchQuery"
                @click="
                    {
                        activeFilter = 'all';
                        searchQuery = '';
                    }
                "
                class="mt-4 text-violet-600 font-bold text-sm hover:underline"
            >
                Clear all filters
            </button>
        </div>

        <TransitionGroup name="list" tag="div" class="space-y-4">
            <AppointmentCard
                v-for="appointment in filteredAppointments"
                :key="appointment.id"
                :appointment="appointment"
                :is-expert="isExpert"
                :is-admin="isAdmin"
                @action="openActionModal"
                @reschedule="openRescheduleModal"
            />
        </TransitionGroup>
        
        <AppointmentActionModal
            :show="showActionModal"
            :title="actionState.title"
            :message="actionState.message"
            :type="actionState.type"
            @close="closeActionModal"
            @confirm="submitAction"
        />

        <AppointmentRescheduleModal
            :show="showRescheduleModal"
            :form="editForm"
            :user-name="editingAppointment?.user?.name || 'User'"
            @close="closeRescheduleModal"
            @submit="submitReschedule"
        />
    </div>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.3s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
</style>
