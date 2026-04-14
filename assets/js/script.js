document.addEventListener('DOMContentLoaded', function() {
    smoothScroll();
    initGallery();
    initModals();
    initSidebar();
    initCheckboxes();
    initFileUploads();
});

function smoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function initGallery() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            const overlay = this.querySelector('.gallery-overlay span');
            
            if (img && overlay) {
                alert(overlay.textContent + ': ' + img.alt);
            }
        });
    });
}

function initModals() {
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const closeButtons = document.querySelectorAll('.btn-close');
    const modals = document.querySelectorAll('.modal');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    });

    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        });
    });
}

function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    
    sidebarLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
}

function initCheckboxes() {
    const checklistItems = document.querySelectorAll('.checklist-item');
    
    checklistItems.forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    item.classList.add('checked');
                } else {
                    item.classList.remove('checked');
                }
            });
        }
    });
}

function initFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        const maxSize = 300 * 1024;
        const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        
        input.addEventListener('change', function(e) {
            const file = this.files[0];
            
            if (file) {
                if (file.size > maxSize) {
                    alert('File size must be less than 300 KB');
                    this.value = '';
                    return;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Only JPG, PNG, and PDF files are allowed');
                    this.value = '';
                    return;
                }
                
                const fileInfo = this.parentElement.querySelector('.file-info');
                if (fileInfo) {
                    fileInfo.textContent = `Selected: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                }
            }
        });
    });
}

function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.main-content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function confirmAction(message) {
    return confirm(message);
}

function formatDate(date) {
    const d = new Date(date);
    return d.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${hour12}:${minutes} ${ampm}`;
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

function showLoader() {
    const loader = document.createElement('div');
    loader.className = 'loader';
    loader.innerHTML = '<div class="spinner"></div>';
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    `;
    
    const spinner = document.createElement('div');
    spinner.style.cssText = `
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    `;
    
    loader.appendChild(spinner);
    document.body.appendChild(loader);
    
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
    
    return loader;
}

function hideLoader(loader) {
    if (loader) {
        loader.remove();
    }
}
