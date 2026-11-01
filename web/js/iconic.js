/**
 * ICONIC INTERIORS — Luxury Interactions & Alpine Stores
 */

// Scroll reveal observer
document.addEventListener('DOMContentLoaded', () => {
    const reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(r => obs.observe(r));
    } else {
        reveals.forEach(r => r.classList.add('is-visible'));
    }

    // Header scroll background toggle
    const header = document.querySelector('.site-header');
    if (header) {
        const checkScroll = () => {
            if (window.scrollY > 40) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        };
        window.addEventListener('scroll', checkScroll, { passive: true });
        checkScroll();
    }
});

/**
 * Currency formatter for INR
 */
function formatINR(val, compact = false) {
    val = Math.round(Number(val) || 0);
    if (compact) {
        if (val >= 10000000) return '₹' + (val / 10000000).toFixed(2).replace(/\.00$/, '') + ' Cr';
        if (val >= 100000) return '₹' + (val / 100000).toFixed(2).replace(/\.00$/, '') + ' L';
    }
    const isNeg = val < 0;
    val = Math.abs(val).toString();
    if (val.length > 3) {
        const last3 = val.substring(val.length - 3);
        const rest = val.substring(0, val.length - 3);
        val = rest.replace(/\B(?=(\d{2})+(?!\d))/g, ',') + ',' + last3;
    }
    return (isNeg ? '-₹' : '₹') + val;
}

/**
 * Alpine.js Hero Style Customizer Component
 */
window.heroCustomizer = function(initialPresets = [], defaultRoom = 'Living Lounge') {
    return {
        presets: initialPresets,
        selectedRoom: defaultRoom,
        activePresetId: initialPresets[0]?.id || null,
        lightingMode: initialPresets[0]?.lighting || 'warm',
        comparePos: 50,
        showBeforeAfter: false,
        isSwapping: false,
        quickBookOpen: false,

        init() {
            if (this.presets.length > 0) {
                this.syncActive();
            }
        },

        get currentPreset() {
            const match = this.presets.find(p => p.id === this.activePresetId);
            if (match) return match;
            // fallback to first preset matching room or first preset
            return this.presets.find(p => p.room === this.selectedRoom) || this.presets[0] || {};
        },

        get availableRooms() {
            const set = new Set(this.presets.map(p => p.room));
            return Array.from(set);
        },

        get presetsForRoom() {
            return this.presets.filter(p => p.room === this.selectedRoom);
        },

        setRoom(room) {
            this.selectedRoom = room;
            const match = this.presets.find(p => p.room === room && p.slug === this.currentPreset.slug);
            if (match) {
                this.selectPreset(match.id);
            } else {
                const firstInRoom = this.presets.find(p => p.room === room);
                if (firstInRoom) this.selectPreset(firstInRoom.id);
            }
        },

        selectPreset(id) {
            if (this.activePresetId === id) return;
            this.isSwapping = true;
            setTimeout(() => {
                this.activePresetId = id;
                const p = this.currentPreset;
                if (p && p.lighting) {
                    this.lightingMode = p.lighting;
                }
                setTimeout(() => {
                    this.isSwapping = false;
                }, 150);
            }, 250);
        },

        setLighting(mode) {
            this.lightingMode = mode;
        },

        toggleBeforeAfter() {
            this.showBeforeAfter = !this.showBeforeAfter;
            if (this.showBeforeAfter) {
                this.comparePos = 50;
            }
        },

        syncActive() {
            const p = this.currentPreset;
            if (p) {
                this.activePresetId = p.id;
                this.lightingMode = p.lighting || 'warm';
            }
        },

        openBookModal() {
            const p = this.currentPreset;
            window.dispatchEvent(new CustomEvent('open-inquiry-modal', {
                detail: {
                    source: 'Hero Style Customizer',
                    heroStyle: p ? `${p.style} (${p.room})` : '',
                    service: p?.room === 'Modular Kitchen' ? 'Modular Kitchens' : 'Residential Interiors',
                    message: `I loved the "${p?.style}" design preset in the ${p?.room} showcase. Please get in touch for a site consultation.`
                }
            }));
        }
    };
};

/**
 * Alpine.js Estimator Component
 */
window.costEstimator = function(config = {}) {
    return {
        propertyType: '3BHK',
        area: 1400,
        tier: 'Luxury Gold',
        location: '',
        fullName: '',
        phone: '',
        email: '',
        types: config.types || {
            '1BHK': { label: '1 BHK Apartment', area: 550, factor: 1.0 },
            '2BHK': { label: '2 BHK Apartment', area: 950, factor: 1.0 },
            '3BHK': { label: '3 BHK Apartment', area: 1400, factor: 1.04 },
            '4BHK/Villa': { label: '4 BHK / Villa', area: 2400, factor: 1.10 },
            'Commercial Office': { label: 'Commercial Office', area: 1800, factor: 0.92 }
        },
        rates: config.rates || {
            'Essential': 1450,
            'Luxury Gold': 2400,
            'Bespoke Signature': 3800
        },
        breakdownShares: config.breakdown || [
            { label: 'Modular Kitchen & Wardrobes', pct: 34 },
            { label: 'Living, Dining & Loose Furniture', pct: 22 },
            { label: 'False Ceiling & Lighting', pct: 14 },
            { label: 'Wall Finishes, Panelling & Paint', pct: 12 },
            { label: 'Flooring, Décor & Soft Furnishings', pct: 10 },
            { label: 'Design, Supervision & Project Mgmt.', pct: 8 }
        ],
        whatsappNumber: config.whatsappNumber || '919778646212',

        init() {
            this.updateDefaultArea();
        },

        updateDefaultArea() {
            if (this.types[this.propertyType]) {
                this.area = this.types[this.propertyType].area;
            }
        },

        get currentRate() {
            return this.rates[this.tier] || this.rates['Luxury Gold'] || 2400;
        },

        get currentFactor() {
            return this.types[this.propertyType]?.factor || 1.0;
        },

        get totalEstimate() {
            return Math.round(this.area * this.currentRate * this.currentFactor);
        },

        get rangeLow() {
            return Math.round(this.totalEstimate * 0.90);
        },

        get rangeHigh() {
            return Math.round(this.totalEstimate * 1.12);
        },

        get formattedRange() {
            return `${formatINR(this.rangeLow, true)} – ${formatINR(this.rangeHigh, true)}`;
        },

        get formattedDetailedRange() {
            return `${formatINR(this.rangeLow)} – ${formatINR(this.rangeHigh)}`;
        },

        get itemizedBreakdown() {
            const tot = this.totalEstimate;
            return this.breakdownShares.map(item => ({
                label: item.label,
                pct: item.pct,
                amount: Math.round(tot * (item.pct / 100)),
                formatted: formatINR(Math.round(tot * (item.pct / 100)))
            }));
        },

        get whatsappForwardUrl() {
            const text = [
                `Hello ICONIC INTERIORS,`,
                ``,
                `I just calculated an estimate for my space on your website:`,
                `• Property Type: ${this.types[this.propertyType]?.label || this.propertyType}`,
                `• Carpet Area: ${this.area} sq.ft.`,
                `• Finish Tier: ${this.tier} (₹${this.currentRate}/sq.ft.)`,
                `• Estimated Budget: ${this.formattedDetailedRange}`,
                this.location ? `• Location: ${this.location}` : '',
                this.fullName ? `• Name: ${this.fullName}` : '',
                this.phone ? `• Phone: ${this.phone}` : '',
                ``,
                `Please arrange a site consultation to discuss execution.`
            ].filter(Boolean).join("\n");

            return `https://wa.me/${this.whatsappNumber}?text=${encodeURIComponent(text)}`;
        }
    };
};

/**
 * Alpine.js Global Inquiry Modal Store / Controller
 */
window.inquiryModal = function() {
    return {
        open: false,
        source: 'Contact Form',
        service: '',
        heroStyle: '',
        propertyType: '',
        carpetArea: '',
        finishTier: '',
        estimatedBudget: '',
        preferredLocation: '',
        message: '',

        init() {
            window.addEventListener('open-inquiry-modal', (e) => {
                const data = e.detail || {};
                this.source = data.source || 'Contact Form';
                this.service = data.service || '';
                this.heroStyle = data.heroStyle || '';
                this.propertyType = data.propertyType || '';
                this.carpetArea = data.carpetArea || '';
                this.finishTier = data.finishTier || '';
                this.estimatedBudget = data.estimatedBudget || '';
                this.preferredLocation = data.preferredLocation || '';
                this.message = data.message || '';
                this.open = true;
                document.body.style.overflow = 'hidden';
            });
        },

        close() {
            this.open = false;
            document.body.style.overflow = '';
        }
    };
};

/**
 * Before/After interactive slider vanilla helper
 */
window.updateCompare = function(e, container) {
    const rect = container.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    let pos = ((clientX - rect.left) / rect.width) * 100;
    pos = Math.max(0, Math.min(100, pos));
    container.style.setProperty('--pos', pos + '%');
};
