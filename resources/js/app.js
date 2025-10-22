import './bootstrap';
import Alpine from 'alpinejs';

// Auto-refresh functionality
Alpine.data('autoRefresh', (config = {}) => ({
    interval: null,
    isRefreshing: false,
    lastRefresh: null,
    
    init() {
        this.startRefresh();
    },
    
    startRefresh() {
        if (this.interval) {
            clearInterval(this.interval);
        }
        
        this.interval = setInterval(() => {
            this.refresh();
        }, config.interval || 30000);
    },
    
    stopRefresh() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    },
    
    async refresh() {
        if (this.isRefreshing) return;
        
        this.isRefreshing = true;
        this.lastRefresh = new Date();
        
        try {
            if (config.onRefresh && typeof config.onRefresh === 'function') {
                await config.onRefresh();
            }
        } catch (error) {
            console.error('Auto-refresh error:', error);
        } finally {
            this.isRefreshing = false;
        }
    },
    
    destroy() {
        this.stopRefresh();
    }
}));

// Toast notifications
Alpine.data('toast', () => ({
    toasts: [],
    
    show(message, type = 'info', duration = 5000) {
        const id = Date.now();
        const toast = {
            id,
            message,
            type,
            duration
        };
        
        this.toasts.push(toast);
        
        setTimeout(() => {
            this.remove(id);
        }, duration);
    },
    
    remove(id) {
        this.toasts = this.toasts.filter(toast => toast.id !== id);
    },
    
    success(message) {
        this.show(message, 'success');
    },
    
    error(message) {
        this.show(message, 'error');
    },
    
    warning(message) {
        this.show(message, 'warning');
    },
    
    info(message) {
        this.show(message, 'info');
    }
}));

// Modal functionality
Alpine.data('modal', () => ({
    open: false,
    
    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },
    
    hide() {
        this.open = false;
        document.body.style.overflow = '';
    },
    
    toggle() {
        this.open ? this.hide() : this.show();
    }
}));

// Loading states
Alpine.data('loading', () => ({
    loading: false,
    
    async withLoading(callback) {
        this.loading = true;
        try {
            await callback();
        } finally {
            this.loading = false;
        }
    }
}));

// Form handling
Alpine.data('form', (config = {}) => ({
    data: config.data || {},
    errors: {},
    loading: false,
    
    async submit() {
        this.loading = true;
        this.errors = {};
        
        try {
            const response = await fetch(config.url, {
                method: config.method || 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.data)
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                this.errors = errorData.errors || {};
                throw new Error(errorData.message || 'An error occurred');
            }
            
            const result = await response.json();
            
            if (config.onSuccess) {
                config.onSuccess(result);
            }
            
            return result;
        } catch (error) {
            if (config.onError) {
                config.onError(error);
            }
            throw error;
        } finally {
            this.loading = false;
        }
    }
}));

// Start Alpine
window.Alpine = Alpine;
Alpine.start();