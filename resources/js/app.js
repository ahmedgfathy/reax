import './bootstrap';
import Alpine from 'alpinejs';

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Helper function to handle RTL for dynamic components
document.addEventListener('DOMContentLoaded', function() {
    // Check if the document is in RTL mode
    const isRTL = document.dir === 'rtl' || 
                 document.documentElement.getAttribute('dir') === 'rtl' ||
                 document.documentElement.lang === 'ar';
    
    if (isRTL) {
        // Apply RTL classes for dynamic elements that may be loaded after page init
        document.documentElement.classList.add('rtl');
        document.body.classList.add('rtl');
        
        // Add RTL specific behaviors
        document.querySelectorAll('.ltr-only').forEach(el => {
            el.style.display = 'none';
        });
        document.querySelectorAll('.rtl-only').forEach(el => {
            el.style.display = 'block';
        });
    } else {
        // Add LTR specific behaviors
        document.querySelectorAll('.rtl-only').forEach(el => {
            el.style.display = 'none';
        });
        document.querySelectorAll('.ltr-only').forEach(el => {
            el.style.display = 'block';
        });
    }
    
    // Set data attribute for use in any JavaScript component
    document.documentElement.setAttribute('data-direction', isRTL ? 'rtl' : 'ltr');
    
    // Font loading optimization
    const lang = document.documentElement.lang;
    const dir = document.documentElement.dir;
    
    if (lang === 'ar' || dir === 'rtl') {
        document.body.style.fontFamily = "'Cairo', sans-serif";
        document.documentElement.style.fontFamily = "'Cairo', sans-serif";
    } else {
        document.body.style.fontFamily = "'Inter', 'Roboto', sans-serif";
        document.documentElement.style.fontFamily = "'Inter', 'Roboto', sans-serif";
    }
});

// PWA functionality
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful');
            })
            .catch(function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
    });
}

// PWA install prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    const installButton = document.getElementById('pwa-install-button');
    if (installButton) {
        installButton.classList.remove('hidden');
        
        installButton.addEventListener('click', (e) => {
            installButton.classList.add('hidden');
            deferredPrompt.prompt();
            
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                } else {
                    console.log('User dismissed the install prompt');
                }
                deferredPrompt = null;
            });
        });
    }
});

// Smooth scrolling for anchor links
document.addEventListener('click', function(e) {
    if (e.target.tagName === 'A' && e.target.getAttribute('href').startsWith('#')) {
        e.preventDefault();
        const target = document.querySelector(e.target.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    }
});

// Form validation helpers
window.validateForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    return isValid;
};

// Toast notification system
window.showToast = function(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transition-all transform translate-x-full`;
    
    switch (type) {
        case 'success':
            toast.classList.add('bg-green-500');
            break;
        case 'error':
            toast.classList.add('bg-red-500');
            break;
        case 'warning':
            toast.classList.add('bg-yellow-500');
            break;
        default:
            toast.classList.add('bg-blue-500');
    }
    
    toast.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, duration);
};

// Initialize tooltips and other interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to buttons
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            }
        });
    });
});
