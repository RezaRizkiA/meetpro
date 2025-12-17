import { ref, computed } from "vue";
import {
    Home,
    Briefcase,
    Calendar as CalendarIcon,
    Users,
    Settings,
    CreditCard,
} from "lucide-vue-next";

export function useDashboardMenu(props) {
    const activeTab = ref("home");

    const tabs = [
        {
            id: "home",
            label: "Overview",
            icon: Home,
            show: true,
            activeClass:
                "bg-violet-50 text-violet-600 border-r-4 border-violet-600",
        },
        {
            id: "appointments",
            label: "Appointments",
            icon: Briefcase,
            show: true, // Bisa diubah logicnya nanti
            activeClass: "bg-blue-50 text-blue-600 border-r-4 border-blue-600",
        },
        {
            id: "calendar",
            label: "Calendar",
            icon: CalendarIcon,
            show: true,
            activeClass:
                "bg-emerald-50 text-emerald-600 border-r-4 border-emerald-600",
        },
        {
            id: "transactions",
            label: "Billing",
            icon: CreditCard,
            show: true,
            activeClass: "bg-pink-50 text-pink-600 border-r-4 border-pink-600",
        },
        {
            id: "members",
            label: "Community",
            icon: Users,
            show: true,
            activeClass:
                "bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600",
        },
        {
            id: "expertises",
            label: "Expertise Settings",
            icon: Settings,
            show: props.isAdmin, // Logic role dari props
            activeClass:
                "bg-orange-50 text-orange-600 border-r-4 border-orange-600",
        },
    ];

    const currentTabTitle = computed(() => {
        const tab = tabs.find((t) => t.id === activeTab.value);
        return tab ? tab.label : "Dashboard";
    });

    return {
        activeTab,
        tabs,
        currentTabTitle,
    };
}
