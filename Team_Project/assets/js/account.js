// Sample user data (replace with actual backend data)
const userData = {
    id: '123',
    name: '',
    email: 'francis@gmail.com',
    phone: '+233 20 123 4567',
    address: '123 Main St, Accra',
    memberSince: '2023-01-15',
    profilePicture: '/api/placeholder/150/150',
    preferences: {
        emailNotifications: true,
        smsNotifications: false,
        interests: ['workshop', 'seminar']
    },
    readingInterests: ['Fiction', 'Technology', 'History', 'Science'],
    reservations: [
        {
            id: 'RSV001',
            bookTitle: 'Cloud Computing Basics',
            reservationDate: '2024-02-25',
            status: 'pending',
            notificationSent: false
        }
    ],
    registeredEvents: [
        {
            id: 1,
            title: 'Reading Workshop',
            date: '2024-11-15',
            time: '14:00',
            location: 'Main Library Hall',
            status: 'upcoming',
            description: 'Improve your reading speed and comprehension'
        },
        {
            id: 2,
            title: 'Technology Seminar',
            date: '2024-10-20',
            time: '10:00',
            location: 'Computer Lab',
            status: 'past',
            description: 'Introduction to modern technology trends'
        }
    ],
    notifications: [
        {
            id: 1,
            type: 'event',
            message: 'Reminder: Reading Workshop tomorrow at 2 PM',
            date: '2024-11-14',
            read: false,
            priority: 'high'
        },
        {
            id: 2,
            type: 'book',
            message: 'Your book "Introduction to Machine Learning" is due in 3 days',
            date: '2024-11-12',
            read: true,
            priority: 'medium'
        }
    ],
    recommendations: {
        books: [
            {
                id: 'BK001',
                title: 'Data Science Fundamentals',
                author: 'Jane Smith',
                reason: 'Based on your interest in Technology',
                matchScore: 89,
                image: '/api/placeholder/300/200'
            },
            {
                id: 'BK002',
                title: '1984',
                author: 'George Orwell',
                reason: 'Popular in Fiction category',
                matchScore: 75,
                image: '/api/placeholder/300/200'
            }
        ],
        events: [
            {
                id: 'EVT002',
                title: 'Technology Workshop',
                date: '2024-03-25',
                reason: 'Matches your interests in Technology',
                image: '/api/placeholder/300/200'
            }
        ]
    }
};

// DOM Elements
const accountNavButtons = document.querySelectorAll('.account-nav-btn');
const accountSections = document.querySelectorAll('.account-content');
const searchInput = document.querySelector('input[type="search"]');
const notificationFilters = document.querySelectorAll('.notification-filters .filter-btn');

// Form Validation Functions
function validateProfileForm(formData) {
    const errors = {};
    
    if (!formData.name) {
        errors.name = 'Name is required';
    } else if (!formData.name.match(/^[a-zA-Z\s]{2,50}$/)) {
        errors.name = 'Name must be 2-50 characters long and contain only letters';
    }
    
    if (!formData.email) {
        errors.email = 'Email is required';
    } else if (!formData.email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        errors.email = 'Please enter a valid email address';
    }
    
    if (formData.phone && !formData.phone.match(/^\+?[\d\s-]{10,15}$/)) {
        errors.phone = 'Please enter a valid phone number';
    }
    
    return errors;
}

function showError(message, inputElement) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    const formGroup = inputElement.closest('.form-group');
    const existingError = formGroup.querySelector('.error-message');
    
    if (existingError) {
        existingError.remove();
    }
    
    formGroup.appendChild(errorDiv);
    inputElement.classList.add('invalid');
}

function clearError(inputElement) {
    const formGroup = inputElement.closest('.form-group');
    const errorDiv = formGroup.querySelector('.error-message');
    
    if (errorDiv) {
        errorDiv.remove();
    }
    
    inputElement.classList.remove('invalid');
}

// Initialize user data
function initializeUserData() {
    // Profile info
    document.getElementById('userName').textContent = userData.name;
    document.getElementById('memberSince').textContent = new Date(userData.memberSince).toLocaleDateString();
    document.getElementById('profileImage').src = userData.profilePicture;
    
    // Stats
    // document.getElementById('booksCount').textContent = userData.borrowingHistory.length;
    document.getElementById('eventsCount').textContent = userData.registeredEvents.length;
    document.getElementById('reservationsCount').textContent = userData.reservations.length;
    
    // Profile form
    document.getElementById('fullName').value = userData.name;
    document.getElementById('email').value = userData.email;
    document.getElementById('phone').value = userData.phone;
    document.getElementById('address').value = userData.address;
    
    // Load all sections
    loadEvents();
    loadReservations();
    loadNotifications();
    loadRecommendations();
    handleInterests();
    updateNotificationCount();
}

// Update the setupNavigation function
function setupNavigation() {
    accountNavButtons.forEach(button => {
        button.addEventListener('click', () => {
            const sectionId = button.dataset.section;
            switchSection(sectionId);
        });
    });
}

// Add new function to handle section switching
function switchSection(sectionId) {
    // Remove active class from all buttons and add to clicked button
    accountNavButtons.forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.section === sectionId) {
            btn.classList.add('active');
        }
    });
    
    // Hide all sections first
    accountSections.forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show the selected section
    const targetSection = document.getElementById(`${sectionId}Section`);
    if (targetSection) {
        targetSection.classList.remove('hidden');
    }
    
    // Update URL without reload
    history.pushState(null, '', `?section=${sectionId}`);
}

// Update the checkUrlParameters function
function checkUrlParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'profile'; // Default to profile if no section specified
    switchSection(section);
}




// Check URL parameters
function checkUrlParameters() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');
    
    if (section) {
        const sectionButton = document.querySelector(`[data-section="${section}"]`);
        if (sectionButton) {
            sectionButton.click();
        }
    }
}



// Event Management Functions
function loadEvents() {
    const eventsList = document.getElementById('eventsList');
    const upcomingEvents = userData.registeredEvents.filter(event => 
        new Date(`${event.date} ${event.time}`) > new Date()
    );
    const pastEvents = userData.registeredEvents.filter(event => 
        new Date(`${event.date} ${event.time}`) <= new Date()
    );
    
    // Event tab functionality
    const tabButtons = document.querySelectorAll('.events-tabs .tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            const events = button.dataset.tab === 'upcoming' ? upcomingEvents : pastEvents;
            displayEvents(events);
        });
    });
    
    // Initial display
    displayEvents(upcomingEvents);
}

function displayEvents(events) {
    const eventsList = document.getElementById('eventsList');
    if (!events.length) {
        eventsList.innerHTML = `
            <div class="empty-state">
                <p>No events found</p>
            </div>
        `;
        return;
    }

    eventsList.innerHTML = events.map(event => `
        <div class="event-item">
            <div class="event-details">
                <h4>${event.title}</h4>
                <p>${formatDateTime(event.date, event.time)} - ${event.location}</p>
                <p class="event-description">${event.description}</p>
                <span class="event-status ${event.status}">${event.status}</span>
            </div>
            <div class="event-actions">
                <button class="btn" onclick="viewEventDetails(${event.id})">View Details</button>
                ${event.status === 'upcoming' ? 
                    `<button class="btn btn-outline" onclick="cancelEventRegistration(${event.id})">Cancel Registration</button>` 
                    : ''
                }
            </div>
        </div>
    `).join('');
}

function loadBorrowingHistory() {
    const currentLoans = [];
    const history = [];

    const borrowingList = document.getElementById('borrowingList');
    if (!borrowingList) return;

    // Fetch borrowed books from the server
    fetch('../auth_functions/book_functions.php') // Update the path to where your PHP function is
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                borrowingList.innerHTML = `
                    <div class="empty-state">
                        <p>${data.error}</p>
                    </div>
                `;
                return;
            }

            if (data.message) {
                borrowingList.innerHTML = `
                    <div class="empty-state">
                        <p>${data.message}</p>
                    </div>
                `;
                return;
            }

            // Split data into current loans and history
            data.forEach(item => {
                if (item.status === 'borrowed') {
                    currentLoans.push(item);
                } else {
                    history.push(item);
                }
            });

            function displayBorrowings(items) {
                if (!items.length) {
                    borrowingList.innerHTML = `
                        <div class="empty-state">
                            <p>No borrowing records found</p>
                        </div>
                    `;
                    return;
                }

                borrowingList.innerHTML = items.map(item => `
                    <div class="borrowing-item">
                        <div class="item-details">
                            <h4>${item.title}</h4>
                            <p>Borrowed: ${formatDate(item.borrow_date)}</p>
                            <p class="due-date">Due: ${formatDate(item.due_date)}</p>
                            ${item.return_date ? `<p>Returned: ${formatDate(item.return_date)}</p>` : ''}
                        </div>
                        <div class="item-status status-${item.status}">
                            ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                        </div>
                        ${item.status === 'borrowed' ? `
                            <div class="item-actions">
                                <button class="btn" onclick="renewBook('${item.book_id}')">Renew</button>
                                <button class="btn btn-outline" onclick="returnBook('${item.book_id}')">Return</button>
                            </div>
                        ` : ''}
                    </div>
                `).join('');
            }

            // Borrowing tab functionality
            const borrowingTabs = document.querySelectorAll('.borrowing-tabs .tab-btn');
            borrowingTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    borrowingTabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    displayBorrowings(tab.dataset.tab === 'current' ? currentLoans : history);
                });
            });

            // Initial display
            displayBorrowings(currentLoans);
        })
        .catch(error => {
            borrowingList.innerHTML = `
                <div class="empty-state">
                    <p>Error loading borrowed books. Please try again later.</p>
                </div>
            `;
        });
}


// Load Reservations
function loadReservations() {
    const reservationsList = document.getElementById('reservationsList');
    if (!reservationsList) return;

    if (!userData.reservations.length) {
        reservationsList.innerHTML = `
            <div class="empty-state">
                <p>No active reservations</p>
            </div>
        `;
        return;
    }

    reservationsList.innerHTML = userData.reservations.map(reservation => `
        <div class="reservation-item">
            <div class="item-details">
                <h4>${reservation.bookTitle}</h4>
                <p>Reserved on: ${formatDate(reservation.reservationDate)}</p>
            </div>
            <span class="item-status status-${reservation.status}">
                ${reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1)}
            </span>
            <button class="btn btn-outline" onclick="cancelReservation('${reservation.id}')">Cancel</button>
        </div>
    `).join('');
}



// Notification Management Functions
function loadNotifications() {
    const notificationsList = document.getElementById('notificationsList');
    if (!notificationsList) return;

    // Filter notifications based on selected filter
    const activeFilter = document.querySelector('.notification-filters .filter-btn.active');
    const filterType = activeFilter ? activeFilter.dataset.filter : 'all';
    
    let filteredNotifications = [...userData.notifications];
    
    if (filterType === 'unread') {
        filteredNotifications = filteredNotifications.filter(n => !n.read);
    } else if (filterType !== 'all') {
        filteredNotifications = filteredNotifications.filter(n => n.type === filterType);
    }

    if (!filteredNotifications.length) {
        notificationsList.innerHTML = `
            <div class="empty-state">
                <p>No notifications found</p>
            </div>
        `;
        return;
    }

    notificationsList.innerHTML = filteredNotifications.map(notification => `
        <div class="notification-item ${notification.read ? '' : 'unread'} priority-${notification.priority}">
            <div class="notification-content">
                <p>${notification.message}</p>
                <small>${formatDate(notification.date)}</small>
            </div>
            ${!notification.read ? `
                <button class="btn-icon" onclick="markAsRead(${notification.id})" aria-label="Mark as read">
                    <span class="icon">✓</span>
                </button>
            ` : ''}
        </div>
    `).join('');

    updateNotificationCount();
}

function updateNotificationCount() {
    const unreadCount = userData.notifications.filter(n => !n.read).length;
    const badge = document.getElementById('notificationBadge');
    
    if (badge) {
        badge.textContent = unreadCount;
        badge.style.display = unreadCount > 0 ? 'inline-flex' : 'none';
    }
}

// Recommendation Management Functions
function loadRecommendations() {
    const bookRecommendations = document.getElementById('bookRecommendations');
    const eventRecommendations = document.getElementById('eventRecommendations');

    if (bookRecommendations) {
        if (!userData.recommendations.books.length) {
            bookRecommendations.innerHTML = `
                <div class="empty-state">
                    <p>No book recommendations available</p>
                </div>
            `;
        } else {
            bookRecommendations.innerHTML = userData.recommendations.books.map(book => `
                <div class="recommendation-card">
                    <img src="${book.image}" alt="${book.title}" class="recommendation-image">
                    <div class="recommendation-content">
                        <h4>${book.title}</h4>
                        <p>By ${book.author}</p>
                        <p class="reason-tag">${book.reason}</p>
                        <div class="match-score">
                            <div class="score-bar" style="width: ${book.matchScore}%"></div>
                            <span>${book.matchScore}% Match</span>
                        </div>
                        <button class="btn-primary" onclick="reserveBook('${book.id}')">Reserve Book</button>
                    </div>
                </div>
            `).join('');
        }
    }

    if (eventRecommendations) {
        if (!userData.recommendations.events.length) {
            eventRecommendations.innerHTML = `
                <div class="empty-state">
                    <p>No event recommendations available</p>
                </div>
            `;
        } else {
            eventRecommendations.innerHTML = userData.recommendations.events.map(event => `
                <div class="recommendation-card">
                    <img src="${event.image}" alt="${event.title}" class="recommendation-image">
                    <div class="recommendation-content">
                        <h4>${event.title}</h4>
                        <p>${formatDate(event.date)}</p>
                        <p class="reason-tag">${event.reason}</p>
                        <button class="btn-primary" onclick="registerForEvent('${event.id}')">Register</button>
                    </div>
                </div>
            `).join('');
        }
    }
}

// Interest Management
function handleInterests() {
    const interestTags = document.getElementById('interestTags');
    const addInterestBtn = document.getElementById('addInterestBtn');
    
    function renderInterestTags() {
        if (!interestTags) return;
        
        interestTags.innerHTML = userData.readingInterests.map(interest => `
            <div class="interest-tag">
                ${interest}
                <button class="remove-tag" onclick="removeInterest('${interest}')" aria-label="Remove ${interest}">×</button>
            </div>
        `).join('');
    }

    if (addInterestBtn) {
        addInterestBtn.addEventListener('click', showInterestModal);
    }

    renderInterestTags();
}


// Update the Interest Modal functions
function showInterestModal() {
    const modal = document.getElementById('interestModal');
    if (!modal) return;

    const availableInterests = [
        'Fiction', 'Non-Fiction', 'Technology', 'Science', 
        'History', 'Arts', 'Business', 'Self-Help'
    ].filter(interest => !userData.readingInterests.includes(interest));

    // Update modal content
    const modalContent = modal.querySelector('.modal-body');
    modalContent.innerHTML = `
        <div class="interest-options">
            ${availableInterests.map(interest => `
                <button type="button" class="interest-option" onclick="handleInterestSelection('${interest}')">
                    ${interest}
                </button>
            `).join('')}
        </div>
    `;

    // Show modal
    modal.style.display = 'flex';

    // Add close button functionality
    const closeButton = modal.querySelector('.close-modal');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            closeInterestModal();
        });
    }

    // Add click outside to close
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeInterestModal();
        }
    });
}

function closeInterestModal() {
    const modal = document.getElementById('interestModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function handleInterestSelection(interest) {
    if (!userData.readingInterests.includes(interest)) {
        userData.readingInterests.push(interest);
        handleInterests(); // Refresh interest tags display
        closeInterestModal();
        showMessage(`Added "${interest}" to your interests`, 'success');
    }
}

// Add these styles to make the interest options clickable and visually appealing
const styles = document.createElement('style');
styles.textContent = `
    .interest-options {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 1rem;
    }

    .interest-option {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
    }

    .interest-option:hover {
        background: #f0f0f0;
        border-color: #3498db;
    }

    .modal {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
    }

    .close-modal {
        border: none;
        background: none;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.5rem;
    }
`;
document.head.appendChild(styles);


// Action Functions
function viewEventDetails(eventId) {
    const event = userData.registeredEvents.find(e => e.id === eventId);
    if (!event) return;
    
    showModal({
        title: event.title,
        content: `
            <div class="event-details-modal">
                <p><strong>Date:</strong> ${formatDateTime(event.date, event.time)}</p>
                <p><strong>Location:</strong> ${event.location}</p>
                <p><strong>Status:</strong> ${event.status}</p>
                <p>${event.description}</p>
            </div>
        `
    });
}



function returnBook(bookId) {
    const book = userData.borrowingHistory.find(b => b.id === bookId);
    if (!book) return;
    
    // Here you would typically make an API call
    showMessage('Book return processed successfully', 'success');
}

function cancelReservation(reservationId) {
    const reservation = userData.reservations.find(r => r.id === reservationId);
    if (!reservation) return;
    
    if (confirm('Are you sure you want to cancel this reservation?')) {
        // Here you would typically make an API call
        userData.reservations = userData.reservations.filter(r => r.id !== reservationId);
        loadReservations();
        showMessage('Reservation cancelled successfully', 'success');
    }
}

function markAsRead(notificationId) {
    const notification = userData.notifications.find(n => n.id === notificationId);
    if (notification) {
        notification.read = true;
        // Here you would typically make an API call
        loadNotifications();
        updateNotificationCount();
    }
}

function addInterest(interest) {
    if (!userData.readingInterests.includes(interest)) {
        userData.readingInterests.push(interest);
        handleInterests();
        closeModal('interestModal');
        showMessage(`Added "${interest}" to your interests`, 'success');
    }
}

function removeInterest(interest) {
    userData.readingInterests = userData.readingInterests.filter(i => i !== interest);
    handleInterests();
    showMessage(`Removed "${interest}" from your interests`, 'success');
}

// Profile Picture Management
function handleProfilePicture() {
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    
    fileInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (file) {
            try {
                setLoading('profileImage', true);
                // Here you would typically upload to a server
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                    showMessage('Profile picture updated successfully', 'success');
                };
                reader.readAsDataURL(file);
            } catch (error) {
                showMessage('Failed to upload profile picture', 'error');
            } finally {
                setLoading('profileImage', false);
            }
        }
    });
    
    fileInput.click();
}

// Utility Functions
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatDateTime(date, time) {
    return `${formatDate(date)} at ${time}`;
}

function setLoading(elementId, isLoading) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.toggle('loading', isLoading);
    }
}

function showMessage(message, type = 'success') {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    messageDiv.textContent = message;
    
    document.querySelector('.main-content').insertAdjacentElement('afterbegin', messageDiv);
    
    setTimeout(() => messageDiv.remove(), 3000);
}

function showModal(options) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>${options.title}</h3>
                <button class="close-modal" onclick="closeModal(this)">&times;</button>
            </div>
            <div class="modal-body">
                ${options.content}
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    setTimeout(() => modal.style.display = 'flex', 10);
}

function closeModal(element) {
    const modal = element.closest('.modal');
    if (modal) {
        modal.style.display = 'none';
        setTimeout(() => modal.remove(), 300);
    }
}

// Event Listeners
document.getElementById('profileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('fullName').value.trim(),
        email: document.getElementById('email').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        address: document.getElementById('address').value.trim()
    };
    
    const errors = validateProfileForm(formData);
    
    if (Object.keys(errors).length > 0) {
        Object.entries(errors).forEach(([field, message]) => {
            showError(message, document.getElementById(field));
        });
        return;
    }
    
    try {
        setLoading('profileForm', true);
        // Here you would typically send the formData to a server
        await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
        
        // Update local data
        Object.assign(userData, formData);
        
        showMessage('Profile updated successfully!');
    } catch (error) {
        showMessage('Failed to update profile', 'error');
    } finally {
        setLoading('profileForm', false);
    }
});

// Initialize Application
document.addEventListener('DOMContentLoaded', function() {
    initializeUserData();
    setupNavigation();
    checkUrlParameters();
    
    // Add keyboard navigation for modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const visibleModal = document.querySelector('.modal[style*="display: flex"]');
            if (visibleModal) closeModal(visibleModal.querySelector('.close-modal'));
        }
    });
    
    // Initialize notification filters
    notificationFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            notificationFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
            loadNotifications();
        });
    });
    
    // Initialize profile picture change
    document.getElementById('changeAvatarBtn')?.addEventListener('click', handleProfilePicture);
});


//
// Add this to your event listeners section
document.querySelector('.btn-secondary')?.addEventListener('click', function(e) {
    e.preventDefault();
    // Reset form to original values
    document.getElementById('fullName').value = userData.name;
    document.getElementById('email').value = userData.email;
    document.getElementById('phone').value = userData.phone;
    document.getElementById('address').value = userData.address;
    
    // Clear any error messages
    document.querySelectorAll('.error-message').forEach(error => error.remove());
    document.querySelectorAll('.form-input').forEach(input => input.classList.remove('invalid'));
});


// Add this to your existing account.js initialization
document.addEventListener('DOMContentLoaded', function() {
    // Check for URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section');
    
    if (section) {
        // Find and click the corresponding nav button
        const targetButton = document.querySelector(`[data-section="${section}"]`);
        if (targetButton) {
            switchSection(section);
            // Update button states
            document.querySelectorAll('.account-nav-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            targetButton.classList.add('active');
        }
    }
});

// Update switchSection function
function switchSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.account-content').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    const targetSection = document.getElementById(`${sectionId}Section`);
    if (targetSection) {
        targetSection.classList.remove('hidden');
        // Update URL without reload
        history.pushState(null, '', `?section=${sectionId}`);
    }
    
    // Load section-specific content
    switch(sectionId) {
        case 'discussions':
            loadUserDiscussions();
            break;
        case 'notifications':
            loadNotifications();
            break;
        // ... handle other sections
    }
}

// Add function to load user discussions
function loadUserDiscussions() {
    const discussionsList = document.getElementById('discussionsList');
    if (!discussionsList) return;

    // Get active tab
    const activeTab = document.querySelector('.discussions-tabs .tab-btn.active');
    const tabType = activeTab ? activeTab.dataset.tab : 'my-topics';

    // Load appropriate discussions
    switch(tabType) {
        case 'my-topics':
            // Load user's created topics
            loadMyTopics();
            break;
        case 'replies':
            // Load discussions user has replied to
            loadMyReplies();
            break;
        case 'saved':
            // Load saved discussions
            loadSavedDiscussions();
            break;
    }
}

// Add these functions to handle different discussion types
function loadMyTopics() {
    // Load topics created by user
}

function loadMyReplies() {
    // Load discussions user has replied to
}

function loadSavedDiscussions() {
    // Load discussions saved by user
}