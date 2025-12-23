/**
 * Appointment utility functions
 */

/**
 * Get status badge color classes
 * @param {string} status - Appointment status
 * @returns {string} - Tailwind CSS classes for badge styling
 */
export const getStatusColor = (status) => {
    const colors = {
        // Success states (Green)
        confirmed: "bg-green-500/20 text-green-400 border-green-500/30",
        completed: "bg-emerald-500/20 text-emerald-400 border-emerald-500/30",

        // In Progress states (Blue/Cyan)
        progress: "bg-cyan-500/20 text-cyan-400 border-cyan-500/30",
        scheduled: "bg-blue-500/20 text-blue-400 border-blue-500/30",

        // Pending/Waiting states (Yellow)
        pending: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
        need_confirmation: "bg-amber-500/20 text-amber-400 border-amber-500/30",

        // Cancelled/Error states (Red)
        cancelled: "bg-red-500/20 text-red-400 border-red-500/30",
        declined: "bg-red-500/20 text-red-400 border-red-500/30",

        // Modified state (Purple)
        edited: "bg-purple-500/20 text-purple-400 border-purple-500/30",
    };
    return colors[status] || colors.pending;
};

/**
 * Get payment status badge color classes
 * @param {string} status - Payment status
 * @returns {string} - Tailwind CSS classes for badge styling
 */
export const getPaymentStatusColor = (status) => {
    const colors = {
        berhasil: "bg-green-500/20 text-green-400 border-green-500/30",
        pending: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
        failed: "bg-red-500/20 text-red-400 border-red-500/30",
        refunded: "bg-orange-500/20 text-orange-400 border-orange-500/30",
    };
    return colors[status] || colors.pending;
};

/**
 * Get calendar status color (for v-calendar dots)
 * @param {string} status - Appointment status
 * @returns {string} - Color code for calendar dot
 */
export const getCalendarStatusColor = (status) => {
    const colors = {
        confirmed: "green",
        completed: "emerald",
        progress: "cyan",
        scheduled: "blue",
        pending: "yellow",
        need_confirmation: "orange",
        cancelled: "red",
        declined: "red",
        edited: "purple",
    };
    return colors[status] || "yellow";
};
