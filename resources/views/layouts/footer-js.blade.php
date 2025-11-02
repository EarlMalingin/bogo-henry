<script>
// Footer Modal Functions
function openFooterModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        // Force reflow to ensure display is set before adding class
        void modal.offsetWidth;
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeFooterModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
        setTimeout(() => {
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
