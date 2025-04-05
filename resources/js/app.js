/**
 * First, we will load all of this project's JavaScript dependencies which
 * includes other libraries.
 */

import './bootstrap';
import Swal from 'sweetalert2';
import Calendar from "@toast-ui/calendar";
import "@toast-ui/calendar/dist/toastui-calendar.min.css";
import {Alpine, Livewire} from '../../vendor/livewire/livewire/dist/livewire.esm';
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'

Alpine.plugin(ToastComponent)

// Make Alpine globally available for the modals and other components
window.Alpine = Alpine

// Define custom directive for modals
document.addEventListener('alpine:init', () => {
    Alpine.store('layout', {
        isSidebarOpen: true,
        isMobileMenuOpen: false,
        toggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
        },
        toggleMobileMenu() {
            this.isMobileMenuOpen = !this.isMobileMenuOpen;
        }
    });
});

Alpine.start()
Livewire.start()

// Make Swal available globally
window.Swal = Swal;

// Set up Livewire hooks for modals
document.addEventListener('livewire:initialized', () => {
    Livewire.hook('component.initialized', (component) => {
        // Handle modal component initialization
    });
});
