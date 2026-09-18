<!-- Favicon -->
<link rel="icon" type="image/png" href="{{asset('assets/assets/images/favicon.ico')}}">

<!-- Local Icon Libraries (Offline Compatible) -->
<link rel="stylesheet" href="{{asset('assets/assets/libs/bootstrap-icons/bootstrap-icons.css')}}">

<!-- ApexCharts & Flatpickr -->
<link rel="stylesheet" href="{{asset('assets/assets/libs/apexcharts/apexcharts.css')}}">
<link rel="stylesheet" href="{{asset('assets/assets/libs/flatpickr/flatpickr.min.css')}}">

<!-- TailwindCSS Engine (Zero-build execution) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    emerald: {
                        50: '#ecfdf5',
                        100: '#d1fae5',
                        200: '#a7f3d0',
                        300: '#6ee7b7',
                        400: '#34d399',
                        500: '#10b981',
                        600: '#059669',
                        700: '#047857',
                        800: '#065f46',
                        900: '#064e3b',
                        950: '#022c22',
                    }
                }
            }
        }
    }
</script>
<style>
    /* Custom Scrollbars & Utilities */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.03);
    }
    ::-webkit-scrollbar-thumb {
        background: rgba(16, 185, 129, 0.25);
        border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(16, 185, 129, 0.5);
    }
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>