export function showToast(message, type = 'success', duration = 4000) {
    const toast = document.getElementById('toast');
    if (!toast) return;

    const isDark = document.documentElement.classList.contains('dark');
    const isError = type === 'error';

    toast.textContent = message;
    toast.className = `fixed top-4 right-4 px-4 py-3 rounded-xl border opacity-100 transition-opacity duration-300 z-50 text-xs shadow-lg ${isError
            ? isDark ? 'bg-rose-950 text-rose-200 border-rose-800' : 'bg-rose-900 text-white border-rose-700'
            : isDark ? 'bg-emerald-950 text-emerald-200 border-emerald-800' : 'bg-emerald-900 text-white border-emerald-700'
        }`;

    // Clear any existing timeout
    if (toast.timeoutId) clearTimeout(toast.timeoutId);

    toast.timeoutId = setTimeout(() => {
        toast.style.opacity = '0';
    }, duration);
}

// Listen for Livewire dispatches automatically across the app
document.addEventListener('DOMContentLoaded', () => {
    if (window.Livewire) {
        window.Livewire.on('notify', (event) => {
            // Livewire v3 event handling
            const data = Array.isArray(event) ? event[0] : event;
            showToast(data.message || data, data.type || 'success');
        });
    }
});