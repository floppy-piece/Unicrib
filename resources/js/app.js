import './bootstrap';
import { initPropertyMap } from './modules/property-map';
import { initImageUploadPreview } from './modules/property-images';
import { initPropertyShowPage } from './modules/property-show';
import { initUnicribInteractive } from './modules/unicrib-interactive';
import './bootstrap';
import { showToast } from './modules/toast';

document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    if (toast && toast.dataset.session) {
        try {
            const data = JSON.parse(toast.dataset.session);
            const message = typeof data === 'string' ? data : (data.message || '');
            const type = data.type || 'success';
            console.log('toast loaded')
            if (message) {
                showToast(message, type);
            }
        } catch (e) {
            console.error('Error parsing session toast:', e);
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Inject Geoapify API key globally for Leaflet modules
    if (document.querySelector('meta[name="geoapify-key"]')) {
        window.GEOAPIFY_API_KEY = document.querySelector('meta[name="geoapify-key"]').content;
    }

    // Initialize interactive 3D effects & dynamic feedback
    initUnicribInteractive();

    // Initialize property details page script if present
    if (document.getElementById('propertyMap')) {
        initPropertyShowPage();
    }

    // Check if property form / map container exists on page
    const mapContainer = document.getElementById('locationMap');
    if (mapContainer) {
        const initialLat = mapContainer.dataset.lat ? parseFloat(mapContainer.dataset.lat) : null;
        const initialLng = mapContainer.dataset.lng ? parseFloat(mapContainer.dataset.lng) : null;

        initPropertyMap({ initialLat, initialLng });
    }

    // Initialize image preview functionality if upload input exists
    initImageUploadPreview();

    // General property form validation
    const form = document.getElementById('propertyForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const mapLat = document.getElementById('mapLat')?.value;
            const mapLng = document.getElementById('mapLng')?.value;

            if (!mapLat || !mapLng) {
                e.preventDefault();
                alert('Please click on the map to set your property location!');
                return false;
            }
            return true;
        });
    }
});