<!-- Favicon -->
<link rel="icon" type="image/png" href="{{asset('assets/assets/images/favicon.ico')}}">

<!-- Typography: Plus Jakarta Sans & JetBrains Mono -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Lucide Icons CDN -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Confetti Canvas CDN -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<!-- Local Icon Libraries (Offline Backup) -->
<link rel="stylesheet" href="{{asset('assets/assets/libs/bootstrap-icons/bootstrap-icons.css')}}">

<!-- ApexCharts & Flatpickr -->
<link rel="stylesheet" href="{{asset('assets/assets/libs/apexcharts/apexcharts.css')}}">
<link rel="stylesheet" href="{{asset('assets/assets/libs/flatpickr/flatpickr.min.css')}}">

<!-- TailwindCSS Engine (Zero-build execution with Espresso & Amber theme extension) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    mono: ['"JetBrains Mono"', 'monospace'],
                },
                colors: {
                    espresso: {
                        950: '#080604', // deepest canvas
                        900: '#0d0a08', // surface
                        850: '#130e0a', // elevated panel
                        800: '#1a130e', // card idle
                        750: '#231a13', // card hover
                        700: '#2d221a', // border subtle
                    },
                    bronze: {
                        300: '#f5c68a',
                        400: '#e5a55d',
                        500: '#c68437',
                        600: '#9d6325',
                        700: '#734617',
                        800: '#4a2c0c',
                    },
                    amberlux: {
                        400: '#fbbf24',
                        500: '#f59e0b',
                        600: '#d97706',
                        700: '#b45309',
                    },
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
                },
                boxShadow: {
                    'glow-amber': '0 0 35px -5px rgba(217, 119, 6, 0.28)',
                    'glow-bronze': '0 0 45px -10px rgba(157, 99, 37, 0.35)',
                    'glass-warm': 'inset 0 1px 1px 0 rgba(245, 198, 138, 0.08)',
                }
            }
        }
    }
</script>

<style>
    .tabular-nums {
        font-variant-numeric: tabular-nums;
    }

    /* Ambient Warm Coffee Lighting Mesh */
    .ambient-coffee-bg {
        background-color: #080604;
        background-image: 
            radial-gradient(at 0% 0%, rgba(180, 83, 9, 0.18) 0px, transparent 48%),
            radial-gradient(at 100% 0%, rgba(120, 53, 15, 0.16) 0px, transparent 45%),
            radial-gradient(at 50% 100%, rgba(69, 26, 3, 0.22) 0px, transparent 55%),
            radial-gradient(at 100% 100%, rgba(198, 132, 55, 0.10) 0px, transparent 40%);
    }

    /* Warm Frosted Glass Panels */
    .glass-espresso {
        background: rgba(19, 14, 10, 0.72);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(229, 165, 93, 0.12);
    }

    .card-espresso {
        background: rgba(26, 19, 14, 0.60);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(229, 165, 93, 0.09);
        transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .card-espresso:hover {
        background: rgba(35, 26, 19, 0.85);
        border-color: rgba(245, 198, 138, 0.30);
        transform: translateY(-2px);
        box-shadow: 0 16px 36px -12px rgba(180, 83, 9, 0.25);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background: rgba(229, 165, 93, 0.18);
        border-radius: 999px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(229, 165, 93, 0.38);
    }

    @keyframes pulseAura {
        0%, 100% { opacity: 0.35; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.08); }
    }
    .warm-orb {
        animation: pulseAura 9s ease-in-out infinite;
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    // Web Audio API helper for warm acoustic chime
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    function playWarmChime(type) {
        if (!audioCtx) return;
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        try {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);

            if (type === 'tap') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(360, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(520, audioCtx.currentTime + 0.07);
                gain.gain.setValueAtTime(0.09, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.07);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.07);
            } else if (type === 'success') {
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(440, audioCtx.currentTime);
                osc.frequency.setValueAtTime(554.37, audioCtx.currentTime + 0.08);
                osc.frequency.setValueAtTime(659.25, audioCtx.currentTime + 0.16);
                gain.gain.setValueAtTime(0.14, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.4);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.4);
            }
        } catch(e) {}
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>