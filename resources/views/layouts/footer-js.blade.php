<script>
// Footer Modal Functions
function getScrollbarWidth() {
    // Create a temporary div to measure scrollbar width
    const outer = document.createElement('div');
    outer.style.visibility = 'hidden';
    outer.style.overflow = 'scroll';
    outer.style.msOverflowStyle = 'scrollbar';
    document.body.appendChild(outer);
    
    const inner = document.createElement('div');
    outer.appendChild(inner);
    
    const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
    outer.parentNode.removeChild(outer);
    
    return scrollbarWidth;
}

function openFooterModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Calculate scrollbar width before hiding it
        const scrollbarWidth = getScrollbarWidth();
        
        // Add padding to prevent layout shift
        document.body.style.paddingRight = scrollbarWidth + 'px';
        document.body.style.overflow = 'hidden';
        
        modal.style.display = 'flex';
        // Force reflow to ensure display is set before adding class
        void modal.offsetWidth;
        modal.classList.add('show');
    }
}

function closeFooterModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        
        // Wait for animation to complete before restoring scrollbar
        setTimeout(() => {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            modal.style.display = 'none';
        }, 300);
    }
}

// Initialize footer modals when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for footer links
    const privacyLink = document.getElementById('footer-privacy-link');
    if (privacyLink) {
        privacyLink.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('privacy-modal');
        });
    }

    const termsLink = document.getElementById('footer-terms-link');
    if (termsLink) {
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('terms-modal');
        });
    }

    const faqLink = document.getElementById('footer-faq-link');
    if (faqLink) {
        faqLink.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('faq-modal');
        });
    }

    const contactLink = document.getElementById('footer-contact-link');
    if (contactLink) {
        contactLink.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('contact-modal');
        });
    }

    // Close buttons for footer modals
    document.querySelectorAll('.footer-modal-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.footer-modal');
            if (modal) {
                closeFooterModal(modal.id);
            }
        });
    });

    // Close footer modals when clicking outside
    document.querySelectorAll('.footer-modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeFooterModal(this.id);
            }
        });
    });
});
</script>
