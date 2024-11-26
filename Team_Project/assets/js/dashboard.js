// Global variables
let currentSection = null;

// Show section function
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.admin-section, .user-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Show selected section
    const selectedSection = document.getElementById('section-' + sectionName);
    if (selectedSection) {
        selectedSection.style.display = 'block';
        currentSection = sectionName;
    }
    
    // Update navigation active states
    document.querySelectorAll('nav a').forEach(link => {
        link.classList.remove('dashboard-nav-active');
    });
    event.currentTarget.classList.add('dashboard-nav-active');

    // Update URL without page reload
    history.pushState(null, '', `?section=${sectionName}`);
}

// Handle form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Profile form submission
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await updateProfile(new FormData(this));
        });
    }

    // Password form submission
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await updatePassword(new FormData(this));
        });
    }

    // Show initial section based on URL parameter or default
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'dashboard';
    showSection(section);
});

// Profile update function
async function updateProfile(formData) {
    try {
        const response = await fetch('../actions/account/update_profile.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Profile updated successfully', 'success');
        } else {
            throw new Error(result.error || 'Failed to update profile');
        }
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

// Password update function
async function updatePassword(formData) {
    try {
        const response = await fetch('../actions/account/update_password.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Password updated successfully', 'success');
            document.getElementById('passwordForm').reset();
        } else {
            throw new Error(result.error || 'Failed to update password');
        }
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

// Return book function
async function returnBook(bookId) {
    if (!confirm('Are you sure you want to return this book?')) return;
    
    try {
        const response = await fetch('../actions/books/return_book.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `book_id=${bookId}`
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Book returned successfully', 'success');
            location.reload(); // Refresh page to update book list
        } else {
            throw new Error(result.error || 'Failed to return book');
        }
    } catch (error) {
        showMessage(error.message, 'error');
    }
}

// Utility function to show messages
function showMessage(message, type = 'success') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 p-4 rounded-lg ${
        type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
    }`;
    messageDiv.textContent = message;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Form reset function
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
        document.getElementById('profileForm').reset();
    }
}