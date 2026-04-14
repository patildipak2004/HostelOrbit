document.addEventListener('DOMContentLoaded', function() {
    initLoginForm();
    initRegistrationForm();
    initComplaintForm();
    initLeaveForm();
    initStaffForm();
});

function initLoginForm() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    }
}

function validateLoginForm() {
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    let isValid = true;
    
    if (!username.value.trim()) {
        showError(username, 'Username is required');
        isValid = false;
    } else {
        clearError(username);
    }
    
    if (!password.value) {
        showError(password, 'Password is required');
        isValid = false;
    } else {
        clearError(password);
    }
    
    return isValid;
}

function initRegistrationForm() {
    const regForm = document.getElementById('registrationForm');
    
    if (regForm) {
        const nextBtns = document.querySelectorAll('.btn-next');
        const prevBtns = document.querySelectorAll('.btn-prev');
        
        nextBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (validateCurrentStep(this)) {
                    nextStep(this);
                }
            });
        });
        
        prevBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                prevStep(this);
            });
        });
        
        regForm.addEventListener('submit', function(e) {
            if (!validateAllSteps()) {
                e.preventDefault();
            }
        });
    }
}

function validateCurrentStep(btn) {
    const currentStep = btn.closest('.form-step');
    const inputs = currentStep.querySelectorAll('input, select, textarea');
    let isValid = true;
    
    inputs.forEach(input => {
        if (input.hasAttribute('required') && !input.value.trim()) {
            showError(input, 'This field is required');
            isValid = false;
        } else {
            clearError(input);
            
            if (input.type === 'email' && input.value.trim()) {
                if (!validateEmail(input.value)) {
                    showError(input, 'Please enter a valid email');
                    isValid = false;
                }
            }
            
            if (input.type === 'tel' && input.value.trim()) {
                if (!validatePhone(input.value)) {
                    showError(input, 'Please enter a valid phone number');
                    isValid = false;
                }
            }
        }
    });
    
    return isValid;
}

function validateAllSteps() {
    const steps = document.querySelectorAll('.form-step');
    let isValid = true;
    
    steps.forEach(step => {
        const inputs = step.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                showError(input, 'This field is required');
                isValid = false;
            }
        });
    });
    
    return isValid;
}

function nextStep(btn) {
    const currentStep = btn.closest('.form-step');
    const nextStep = currentStep.nextElementSibling;
    const progressBar = document.querySelector('.progress-bar');
    
    if (nextStep && nextStep.classList.contains('form-step')) {
        currentStep.classList.remove('active');
        nextStep.classList.add('active');
        
        const currentStepNum = currentStep.dataset.step;
        const progressSteps = progressBar.querySelectorAll('.progress-step');
        
        if (progressSteps[currentStepNum - 1]) {
            progressSteps[currentStepNum - 1].classList.add('completed');
            progressSteps[currentStepNum].classList.add('active');
        }
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function prevStep(btn) {
    const currentStep = btn.closest('.form-step');
    const prevStepEl = currentStep.previousElementSibling;
    const progressBar = document.querySelector('.progress-bar');
    
    if (prevStepEl && prevStepEl.classList.contains('form-step')) {
        currentStep.classList.remove('active');
        prevStepEl.classList.add('active');
        
        const currentStepNum = currentStep.dataset.step;
        const progressSteps = progressBar.querySelectorAll('.progress-step');
        
        if (progressSteps[currentStepNum - 2]) {
            progressSteps[currentStepNum - 2].classList.remove('completed');
            progressSteps[currentStepNum - 2].classList.add('active');
            progressSteps[currentStepNum - 1].classList.remove('active');
        }
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function initComplaintForm() {
    const complaintForm = document.getElementById('complaintForm');
    
    if (complaintForm) {
        complaintForm.addEventListener('submit', function(e) {
            if (!validateComplaintForm()) {
                e.preventDefault();
            }
        });
    }
}

function validateComplaintForm() {
    const complaintType = document.getElementById('complaint_type');
    const subject = document.getElementById('subject');
    const description = document.getElementById('description');
    let isValid = true;
    
    if (!complaintType.value) {
        showError(complaintType, 'Please select complaint type');
        isValid = false;
    } else {
        clearError(complaintType);
    }
    
    if (!subject.value.trim()) {
        showError(subject, 'Subject is required');
        isValid = false;
    } else {
        clearError(subject);
    }
    
    if (!description.value.trim()) {
        showError(description, 'Description is required');
        isValid = false;
    } else if (description.value.trim().length < 20) {
        showError(description, 'Description must be at least 20 characters');
        isValid = false;
    } else {
        clearError(description);
    }
    
    return isValid;
}

function initLeaveForm() {
    const leaveForm = document.getElementById('leaveForm');
    
    if (leaveForm) {
        leaveForm.addEventListener('submit', function(e) {
            if (!validateLeaveForm()) {
                e.preventDefault();
            }
        });
        
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        if (startDate && endDate) {
            startDate.addEventListener('change', validateLeaveDates);
            endDate.addEventListener('change', validateLeaveDates);
        }
    }
}

function validateLeaveForm() {
    const leaveType = document.getElementById('leave_type');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const reason = document.getElementById('reason');
    let isValid = true;
    
    if (!leaveType.value) {
        showError(leaveType, 'Please select leave type');
        isValid = false;
    } else {
        clearError(leaveType);
    }
    
    if (!startDate.value) {
        showError(startDate, 'Start date is required');
        isValid = false;
    } else {
        clearError(startDate);
    }
    
    if (!endDate.value) {
        showError(endDate, 'End date is required');
        isValid = false;
    } else {
        clearError(endDate);
    }
    
    if (!validateLeaveDates()) {
        isValid = false;
    }
    
    if (!reason.value.trim()) {
        showError(reason, 'Reason is required');
        isValid = false;
    } else if (reason.value.trim().length < 10) {
        showError(reason, 'Reason must be at least 10 characters');
        isValid = false;
    } else {
        clearError(reason);
    }
    
    return isValid;
}

function validateLeaveDates() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    if (startDate.value && endDate.value) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (start < today) {
            showError(startDate, 'Start date cannot be in the past');
            return false;
        }
        
        if (end < start) {
            showError(endDate, 'End date must be after start date');
            return false;
        }
        
        clearError(startDate);
        clearError(endDate);
    }
    
    return true;
}

function initStaffForm() {
    const cleaningForm = document.getElementById('cleaningForm');
    
    if (cleaningForm) {
        cleaningForm.addEventListener('submit', function(e) {
            if (!validateCleaningForm()) {
                e.preventDefault();
            }
        });
    }
}

function validateCleaningForm() {
    const checkboxes = document.querySelectorAll('.checklist-item input[type="checkbox"]');
    let isChecked = false;
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            isChecked = true;
        }
    });
    
    if (!isChecked) {
        alert('Please check at least one cleaning item');
        return false;
    }
    
    return true;
}

function showError(input, message) {
    const formGroup = input.closest('.form-group');
    let errorElement = formGroup.querySelector('small.error');
    
    if (!errorElement) {
        errorElement = document.createElement('small');
        errorElement.className = 'error';
        errorElement.style.color = '#ef4444';
        formGroup.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
    input.style.borderColor = '#ef4444';
}

function clearError(input) {
    const formGroup = input.closest('.form-group');
    const errorElement = formGroup.querySelector('small.error');
    
    if (errorElement) {
        errorElement.remove();
    }
    
    input.style.borderColor = '#e5e7eb';
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[0-9]{10}$/;
    return re.test(phone.replace(/\D/g, ''));
}

function validatePercentage(value) {
    const num = parseFloat(value);
    return num >= 0 && num <= 100;
}

function validateRequired(value) {
    return value && value.trim() !== '';
}

function validateMinLength(value, minLength) {
    return value && value.trim().length >= minLength;
}

function validateMaxLength(value, maxLength) {
    return value && value.trim().length <= maxLength;
}

function validateDate(dateString) {
    const date = new Date(dateString);
    return date instanceof Date && !isNaN(date);
}

function validateFutureDate(dateString) {
    const date = new Date(dateString);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return date >= today;
}

function validateAge(dateString, minAge = 10, maxAge = 100) {
    const birthDate = new Date(dateString);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    return age >= minAge && age <= maxAge;
}

function validateFile(input, maxSizeKB = 1024, allowedTypes = ['image/jpeg', 'image/png', 'application/pdf']) {
    const file = input.files[0];
    
    if (!file) {
        return { valid: false, message: 'Please select a file' };
    }
    
    if (file.size > maxSizeKB * 1024) {
        return { valid: false, message: `File size must be less than ${maxSizeKB / 1024} MB` };
    }
    
    if (!allowedTypes.includes(file.type)) {
        return { valid: false, message: 'Only JPG, PNG, and PDF files are allowed' };
    }
    
    return { valid: true };
}
