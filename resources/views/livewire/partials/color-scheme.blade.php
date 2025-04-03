{{-- Google-like Color Scheme CSS Variables --}}
<style>
    :root {
        /* Primary Colors - Google Blue */
        --color-primary-50: 232, 240, 254;   /* #e8f0fe */
        --color-primary-100: 210, 227, 252;  /* #d2e3fc */
        --color-primary-200: 174, 203, 250;  /* #aecbfa */
        --color-primary-300: 129, 169, 249;  /* #81a9f9 */
        --color-primary-400: 87, 136, 245;   /* #5788f5 */
        --color-primary-500: 66, 133, 244;   /* #4285f4 - Google Blue */
        --color-primary-600: 26, 115, 232;   /* #1a73e8 */
        --color-primary-700: 25, 103, 210;   /* #1967d2 */
        --color-primary-800: 24, 90, 188;    /* #185abc */
        --color-primary-900: 23, 78, 166;    /* #174ea6 */
        
        /* Secondary Colors - Google Green */
        --color-secondary-50: 230, 244, 234;  /* #e6f4ea */
        --color-secondary-100: 204, 232, 213; /* #cce8d5 */
        --color-secondary-200: 153, 209, 172; /* #99d1ac */
        --color-secondary-300: 102, 187, 132; /* #66bb84 */
        --color-secondary-400: 51, 164, 91;   /* #33a45b */
        --color-secondary-500: 15, 157, 88;   /* #0f9d58 - Google Green */
        --color-secondary-600: 13, 142, 80;   /* #0d8e50 */
        --color-secondary-700: 12, 128, 72;   /* #0c8048 */
        --color-secondary-800: 10, 113, 64;   /* #0a7140 */
        --color-secondary-900: 9, 99, 57;     /* #096339 */
        
        /* Warning Colors - Google Yellow */
        --color-warning-50: 254, 247, 224;    /* #fef7e0 */
        --color-warning-100: 253, 239, 194;   /* #fdefc2 */
        --color-warning-200: 252, 225, 133;   /* #fce185 */
        --color-warning-300: 251, 211, 73;    /* #fbd349 */
        --color-warning-400: 251, 188, 5;     /* #fbbc05 - Google Yellow */
        --color-warning-500: 244, 180, 0;     /* #f4b400 */
        --color-warning-600: 230, 169, 0;     /* #e6a900 */
        --color-warning-700: 217, 159, 0;     /* #d99f00 */
        --color-warning-800: 204, 149, 0;     /* #cc9500 */
        --color-warning-900: 191, 139, 0;     /* #bf8b00 */
        
        /* Danger Colors - Google Red */
        --color-danger-50: 252, 232, 230;     /* #fce8e6 */
        --color-danger-100: 249, 209, 204;    /* #f9d1cc */
        --color-danger-200: 246, 174, 164;    /* #f6aea4 */
        --color-danger-300: 242, 139, 125;    /* #f28b7d */
        --color-danger-400: 238, 103, 85;     /* #ee6755 */
        --color-danger-500: 234, 67, 53;      /* #ea4335 - Google Red */
        --color-danger-600: 217, 48, 37;      /* #d93025 */
        --color-danger-700: 197, 34, 31;      /* #c5221f */
        --color-danger-800: 179, 20, 18;      /* #b31412 */
        --color-danger-900: 165, 14, 14;      /* #a50e0e */

        /* Google Material Shadows */
        --shadow-sm: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 1px 3px 1px rgba(60, 64, 67, 0.15);
        --shadow-md: 0 2px 6px 2px rgba(60, 64, 67, 0.15), 0 1px 2px 0 rgba(60, 64, 67, 0.3);
        --shadow-lg: 0 4px 8px 3px rgba(60, 64, 67, 0.15), 0 1px 3px 0 rgba(60, 64, 67, 0.3);
        --shadow-xl: 0 6px 10px 4px rgba(60, 64, 67, 0.15), 0 2px 3px 0 rgba(60, 64, 67, 0.3);
    }

    /* Google Card Styles */
    .google-card {
        border-radius: 0.5rem;
        box-shadow: var(--shadow-md);
        transition: box-shadow 0.3s ease;
    }
    
    .google-card:hover {
        box-shadow: var(--shadow-lg);
    }

    /* Google Button Styles */
    .btn-primary {
        @apply px-4 py-2 bg-blue-500 text-white font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition duration-150;
    }
    
    .btn-secondary {
        @apply px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition duration-150;
    }
    
    .btn-success {
        @apply px-4 py-2 bg-green-500 text-white font-medium rounded-lg shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition duration-150;
    }
    
    .btn-danger {
        @apply px-4 py-2 bg-red-500 text-white font-medium rounded-lg shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition duration-150;
    }

    /* Google Form Input Styles */
    .google-input {
        @apply block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm transition duration-150;
    }
    
    .google-select {
        @apply block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm transition duration-150;
    }
    
    .google-textarea {
        @apply block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 sm:text-sm transition duration-150;
    }

    /* Google Badge Styles */
    .google-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    
    .google-badge-blue {
        @apply bg-blue-100 text-blue-800;
    }
    
    .google-badge-green {
        @apply bg-green-100 text-green-800;
    }
    
    .google-badge-yellow {
        @apply bg-yellow-100 text-yellow-800;
    }
    
    .google-badge-red {
        @apply bg-red-100 text-red-800;
    }
    
    .google-badge-gray {
        @apply bg-gray-100 text-gray-800;
    }

    /* Google Progress Bar */
    .google-progress-container {
        @apply w-full bg-gray-200 rounded-full h-2.5;
    }
    
    .google-progress-bar {
        @apply bg-blue-500 h-2.5 rounded-full transition-all duration-300;
    }
</style> 