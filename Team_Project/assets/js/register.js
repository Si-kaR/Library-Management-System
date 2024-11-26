const form = document.getElementById('registrationForm')
form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Get form values
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    // Clear previous errors
    clearErrors();
    
    // Validate all fields
    let isValid = true;
    
    // Validate first name
    if (firstName === '') {
        showError('firstName', 'First name is required');
        isValid = false;
    } else if (!/^[A-Za-z\s]{2,30}$/.test(firstName)) {
        showError('firstName', 'First name must be 2-30 characters long and contain only letters');
        isValid = false;
    }
    
    // Validate last name
    if (lastName === '') {
        showError('lastName', 'Last name is required');
        isValid = false;
    } else if (!/^[A-Za-z\s]{2,30}$/.test(lastName)) {
        showError('lastName', 'Last name must be 2-30 characters long and contain only letters');
        isValid = false;
    }
    
    // Validate email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        showError('email', 'Email is required');
        isValid = false;
    } else if (!emailPattern.test(email)) {
        showError('email', 'Please enter a valid email address');
        isValid = false;
    }
    
    // Validate password
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;
    if (password === '') {
        showError('password', 'Password is required');
        isValid = false;
    } else if (!passwordPattern.test(password)) {
        showError('password', 'Password must contain at least 8 characters, including uppercase, lowercase, number, and special character');
        isValid = false;
    }
    
    // Validate confirm password
    if (confirmPassword === '') {
        showError('confirmPassword', 'Please confirm your password');
        isValid = false;
    } else if (password !== confirmPassword) {
        showError('confirmPassword', 'Passwords do not match');
        isValid = false;
    }
    
    // If all validations pass
    if (isValid) {
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.classList.add('loading');
        submitButton.disabled = true;

        form.submit();
        
        // Simulate API call (replace with actual API call)
        // setTimeout(() => {
        //     // Create user object
        //     const userData = {
        //         firstName,
        //         lastName,
        //         email,
        //         password
        //     };
            
        //     console.log('Registration Data:', userData);
            
        //     // Show success message
        //     showSuccessMessage('Registration successful! Please check your email to verify your account.');
            
        //     // Reset form
        //     this.reset();
            
        //     // Reset button state
        //     submitButton.classList.remove('loading');
        //     submitButton.disabled = false;
        // }, 1500);
    }
});

// Show error message for a field
function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const formGroup = field.closest('.form-group');
    
    // Add error class to form group
    formGroup.classList.add('error');
    
    // Create and append error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    formGroup.appendChild(errorDiv);
    
    // Shake animation for error feedback
    field.style.animation = 'shake 0.5s ease-in-out';
    setTimeout(() => {
        field.style.animation = '';
    }, 500);
}

// Clear all error messages
function clearErrors() {
    document.querySelectorAll('.form-group').forEach(group => {
        group.classList.remove('error');
        const errorMessage = group.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    });
}

// Show success message
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'success-message';
    successDiv.textContent = message;
    
    const form = document.getElementById('registrationForm');
    form.insertAdjacentElement('beforebegin', successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 5000);
}

// Real-time validation
document.querySelectorAll('.form-group input').forEach(input => {
    input.addEventListener('input', function() {
        const formGroup = this.closest('.form-group');
        
        // Clear error message on input
        const errorMessage = formGroup.querySelector('.error-message');
        if (errorMessage) {
            formGroup.classList.remove('error');
            errorMessage.remove();
        }
        
        // Add success class when field is valid
        if (this.value.trim() !== '' && this.checkValidity()) {
            formGroup.classList.add('success');
        } else {
            formGroup.classList.remove('success');
        }
    });
    
    // Remove success class on focus
    input.addEventListener('focus', function() {
        const formGroup = this.closest('.form-group');
        formGroup.classList.remove('success');
    });
});

// Add shake animation style
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        text-align: center;
        animation: slideIn 0.3s ease;
    }
`;
document.head.appendChild(style);