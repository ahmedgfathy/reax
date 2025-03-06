import './bootstrap';
import './i18n';

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
});
