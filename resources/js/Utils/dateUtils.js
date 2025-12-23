/**
 * Date and time utility functions
 */

/**
 * Format date time to display format
 * @param {string|Date} dateTime - Date time to format
 * @returns {Object} - Object with formatted date and time
 */
export const formatDateTime = (dateTime) => {
    if (!dateTime) return { date: "-", time: "-" };

    const date = new Date(dateTime);

    return {
        date: date.toLocaleDateString("en-US", {
            month: "short",
            day: "numeric",
            year: "numeric",
        }),
        time: date.toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: true,
        }),
    };
};

/**
 * Format date for API (YYYY-MM-DD format in local timezone)
 * @param {Date} date - Date to format
 * @returns {string} - Formatted date string
 */
export const formatDateForAPI = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
};
