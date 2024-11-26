// // // Sample events data
// const events = [
//     {
//         id: 1,
//         title: 'Reading Workshop',
//         type: 'workshop',
//         date: '2024-11-15',
//         time: '14:00',
//         location: 'Main Library Hall',
//         description: 'Improve your reading comprehension and speed',
//         image: '/api/placeholder/400/320'
//     },
//     {
//         id: 2,
//         title: 'Technology Workshop',
//         type: 'workshop',
//         date: '2024-11-20',
//         time: '10:00',
//         location: 'Computer Lab',
//         description: 'Introduction to basic programming concepts',
//         image: '/api/placeholder/400/320'
//     },
//     {
//         id: 3,
//         title: 'Study Group Meetup',
//         type: 'meetup',
//         date: '2024-11-22',
//         time: '15:00',
//         location: 'Study Room B',
//         description: 'Group study session for upcoming exams',
//         image: '/api/placeholder/400/320'
//     },
//     {
//         id: 4,
//         title: 'Book Reading Session',
//         type: 'reading',
//         date: '2024-11-25',
//         time: '16:00',
//         location: 'Reading Room',
//         description: 'Featured book: "The Great Gatsby"',
//         image: '/api/placeholder/400/320'
//     },
//     {
//         id: 5,
//         title: 'Digital Skills Seminar',
//         type: 'seminar',
//         date: '2024-11-27',
//         time: '11:00',
//         location: 'Conference Room',
//         description: 'Essential digital literacy skills',
//         image: '/api/placeholder/400/320'
//     },
//     {
//         id: 6,
//         title: 'Research Methods Workshop',
//         type: 'workshop',
//         date: '2024-11-29',
//         time: '13:00',
//         location: 'Study Room A',
//         description: 'Advanced research techniques',
//         image: '/api/placeholder/400/320'
//     }
// ];




// Current date state
let currentDate = {
    year: 2024,
    month: 10 // November (0-based)
};


// Initialize
function initialize() {
    fetchEvents();
    setupEventListeners();
}

var event = [];

const getEvents = async () => {
    try {
        const response = await fetch("../actions/auth/fetch_events.php");
        const data = await response.json()
        console.log(data.data)
        events = data.data
        return data.data
    } catch (error) {
        console.log(error)

    }

}

// const events = getEvents()
console.log(events)

// DOM Elements
const calendarGrid = document.getElementById('calendarGrid');
const eventsGrid = document.getElementById('eventsGrid');
const modal = document.getElementById('registrationModal');
const closeModal = document.querySelector('.close-modal');
const registrationForm = document.getElementById('registrationForm');
const calendarViewBtn = document.getElementById('calendarViewBtn');
const listViewBtn = document.getElementById('listViewBtn');
const eventFilter = document.getElementById('eventFilter');
const searchInput = document.querySelector('input[type="search"]');
const searchResults = document.getElementById('searchResults');

// Utility Functions
function getDaysInMonth(year, month) {
    return new Date(year, month + 1, 0).getDate();
}

function getMonthName(month) {
    return new Date(2024, month).toLocaleString('default', { month: 'long' });
}

function formatDate(date) {
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Generate calendar
function generateCalendar(year = currentDate.year, month = currentDate.month) {
    const daysInMonth = getDaysInMonth(year, month);
    const firstDay = new Date(year, month, 1).getDay();
    const monthName = getMonthName(month);

    // Update calendar title
    document.querySelector('.calendar-title').textContent = `${monthName} ${year}`;

    calendarGrid.innerHTML = '';

    // Add empty cells for days before the first of the month
    for (let i = 0; i < firstDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'calendar-day empty';
        calendarGrid.appendChild(emptyDay);
    }

    // Add days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateCell = document.createElement('div');
        dateCell.className = 'calendar-day';

        const dateDiv = document.createElement('div');
        dateDiv.className = 'calendar-date';
        dateDiv.textContent = day;
        dateCell.appendChild(dateDiv);

        // Format the date string to match events data format
        const currentDateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
        const dayEvents = events.filter(event => event.date === currentDateStr);

        dayEvents.forEach(event => {
            const eventDiv = document.createElement('div');
            eventDiv.className = 'event';
            eventDiv.textContent = `${event.time} ${event.title}`;
            eventDiv.addEventListener('click', () => showRegistrationModal(event));
            dateCell.appendChild(eventDiv);
        });

        calendarGrid.appendChild(dateCell);
    }
}

// Generate event cards
function generateEventCards(filteredEvents = events) {
    eventsGrid.innerHTML = '';

    if (filteredEvents.length === 0) {
        const noEvents = document.createElement('div');
        noEvents.className = 'no-events';
        noEvents.textContent = 'No events found matching your criteria.';
        eventsGrid.appendChild(noEvents);
        return;
    }

    filteredEvents.forEach(event => {
        const card = document.createElement('div');
        card.className = 'event-card';

        const image = document.createElement('img');
        image.src = event.image;
        image.alt = event.title;
        image.className = 'event-image';

        const content = document.createElement('div');
        content.className = 'event-content';

        content.innerHTML = `
            <h3 class="event-title">${event.title}</h3>
            <div class="event-details">
                <p><strong>Date:</strong> ${formatDate(new Date(event.date))}</p>
                <p><strong>Time:</strong> ${event.time}</p>
                <p><strong>Location:</strong> ${event.location}</p>
                <p>${event.description}</p>
            </div>
            <span class="event-tag">${event.type.charAt(0).toUpperCase() + event.type.slice(1)}</span>
        `;

        const registerButton = document.createElement('button');
        registerButton.className = 'btn-primary';
        registerButton.textContent = 'Register';
        registerButton.addEventListener('click', () => showRegistrationModal(event));
        content.appendChild(registerButton);

        card.appendChild(image);
        card.appendChild(content);
        eventsGrid.appendChild(card);
    });
}

// Calendar Navigation
function navigateMonth(direction) {
    currentDate.month += direction;

    if (currentDate.month > 11) {
        currentDate.month = 0;
        currentDate.year++;
    } else if (currentDate.month < 0) {
        currentDate.month = 11;
        currentDate.year--;
    }

    generateCalendar(currentDate.year, currentDate.month);
}

// Modal Functions
function showRegistrationModal(event) {
    modal.style.display = 'flex';
    document.getElementById('modalTitle').textContent = `Register for ${event.title}`;
    registrationForm.dataset.eventId = event.id;
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function hideModal() {
    modal.style.display = 'none';
    registrationForm.reset();
    document.body.style.overflow = ''; // Restore scrolling
}

// Enhanced Search and Filter
function filterEvents() {
    const filterValue = eventFilter.value;
    const searchText = searchInput.value.toLowerCase().trim();

    let filteredEvents = events;

    // Apply type filter
    if (filterValue !== 'all') {
        filteredEvents = filteredEvents.filter(event => event.type === filterValue);
    }

    // Apply search filter with multiple terms support
    if (searchText) {
        const searchTerms = searchText.split(' ');
        filteredEvents = filteredEvents.filter(event => {
            const searchableText = [
                event.title,
                event.description,
                event.location,
                event.type,
                formatDate(new Date(event.date)),
                event.time
            ].join(' ').toLowerCase();

            return searchTerms.every(term => searchableText.includes(term));
        });
    }

    generateEventCards(filteredEvents);

    // Show search results count
    if (searchResults) {
        searchResults.textContent = `Found ${filteredEvents.length} event${filteredEvents.length !== 1 ? 's' : ''}`;

        // Clear the search results count after 3 seconds
        setTimeout(() => {
            searchResults.textContent = '';
        }, 3000);
    } else {
        // Clear search results count if search input is empty
        if (searchResults) {
            searchResults.textContent = '';
        }
    }

    generateEventCards(filteredEvents);
}

// Add this to ensure search results are cleared when clicking outside
document.addEventListener('click', function (e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.textContent = '';
    }
});

// Add this to clear results when search is cleared
searchInput.addEventListener('search', function () {
    if (this.value === '') {
        searchResults.textContent = '';
    }
});



// Form validation functions
function validateName(name) {
    const nameRegex = /^[a-zA-Z\s]{2,50}$/;
    return nameRegex.test(name.trim());
}

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email.trim());
}

function validatePhone(phone) {
    const phoneRegex = /^\+?[\d\s-]{10,15}$/;
    return phone.trim() === '' || phoneRegex.test(phone.trim()); // Phone is optional
}

function showError(input, message) {
    const formGroup = input.parentElement;
    const errorDiv = formGroup.querySelector('.error-message') || document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;

    if (!formGroup.querySelector('.error-message')) {
        formGroup.appendChild(errorDiv);
    }

    input.classList.add('invalid');
}

function clearError(input) {
    const formGroup = input.parentElement;
    const errorDiv = formGroup.querySelector('.error-message');

    if (errorDiv) {
        errorDiv.remove();
    }

    input.classList.remove('invalid');
}

// Replace the existing form submit listener with this enhanced version
registrationForm.addEventListener('submit', function (e) {
    e.preventDefault();
    let isValid = true;

    // Get form inputs
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');

    // Clear previous errors
    [nameInput, emailInput, phoneInput].forEach(input => clearError(input));

    // Validate name
    if (!validateName(nameInput.value)) {
        showError(nameInput, 'Please enter a valid name (2-50 characters, letters only)');
        isValid = false;
    }

    // Validate email
    if (!validateEmail(emailInput.value)) {
        showError(emailInput, 'Please enter a valid email address');
        isValid = false;
    }

    // Validate phone (if provided)
    if (phoneInput.value.trim() !== '' && !validatePhone(phoneInput.value)) {
        showError(phoneInput, 'Please enter a valid phone number (10-15 digits)');
        isValid = false;
    }

    if (isValid) {
        const eventId = this.dataset.eventId;
        const formData = {
            eventId: eventId,
            name: nameInput.value.trim(),
            email: emailInput.value.trim(),
            phone: phoneInput.value.trim()
        };

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.classList.add('loading');
        submitButton.textContent = 'Registering...';

        // Simulate API call (replace with actual API call)
        setTimeout(() => {
            console.log('Registration submitted:', formData);

            // Show success message
            const successMessage = document.createElement('div');
            successMessage.className = 'success-message';
            successMessage.textContent = 'Registration successful! You will receive a confirmation email shortly.';
            modal.querySelector('.modal-content').appendChild(successMessage);

            // Reset form and close modal
            setTimeout(() => {
                hideModal();
                submitButton.disabled = false;
                submitButton.classList.remove('loading');
                submitButton.textContent = 'Register for Event';
                successMessage.remove();
            }, 2000);
        }, 1500);
    }
});

// Add real-time validation
const inputs = {
    name: { element: document.getElementById('name'), validator: validateName },
    email: { element: document.getElementById('email'), validator: validateEmail },
    phone: { element: document.getElementById('phone'), validator: validatePhone }
};

Object.entries(inputs).forEach(([key, { element, validator }]) => {
    element.addEventListener('input', function () {
        if (this.value.trim() === '') {
            clearError(this);
        } else if (!validator(this.value)) {
            if (key === 'phone' && this.value.trim() === '') return;
            showError(this, `Please enter a valid ${key}`);
        } else {
            clearError(this);
        }
    });
});

// Continue with your existing initialization code
document.addEventListener('DOMContentLoaded', function () {
    generateCalendar();
    generateEventCards();
    document.querySelector('.upcoming-events').style.display = 'none';
});



// Event Listeners
calendarViewBtn.addEventListener('click', function () {
    this.classList.add('active');
    listViewBtn.classList.remove('active');
    document.querySelector('.calendar').style.display = 'block';
    document.querySelector('.upcoming-events').style.display = 'none';
});

listViewBtn.addEventListener('click', function () {
    this.classList.add('active');
    calendarViewBtn.classList.remove('active');
    document.querySelector('.calendar').style.display = 'none';
    document.querySelector('.upcoming-events').style.display = 'block';
});

closeModal.addEventListener('click', hideModal);

modal.addEventListener('click', function (e) {
    if (e.target === modal) {
        hideModal();
    }
});

registrationForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const eventId = this.dataset.eventId;
    const formData = {
        eventId: eventId,
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value
    };

    // Here you would typically send the formData to a server
    console.log('Registration submitted:', formData);

    // Show success message
    const successMessage = document.createElement('div');
    successMessage.className = 'success-message';
    successMessage.textContent = 'Registration successful! You will receive a confirmation email shortly.';
    modal.querySelector('.modal-content').appendChild(successMessage);

    setTimeout(() => {
        hideModal();
        successMessage.remove();
    }, 2000);
});

eventFilter.addEventListener('change', filterEvents);
searchInput.addEventListener('input', filterEvents);

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    generateCalendar();
    generateEventCards();
    document.querySelector('.upcoming-events').style.display = 'none';
});