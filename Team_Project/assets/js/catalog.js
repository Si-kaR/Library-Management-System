// State management
let currentView = 'grid';
let currentPage = 1;
let itemsPerPage = 12;
let filteredBooks = [];
let totalBooks = 0;
let isLoading = false;

// DOM Elements
const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');
const genreFilter = document.getElementById('genre-filter');
const sortSelect = document.getElementById('sort-select');
const resultsCount = document.getElementById('results-count');
const searchResults = document.getElementById('search-results');
const loadingIndicator = document.getElementById('loading');
const loadMoreButton = document.getElementById('load-more');
const quickFilters = document.querySelectorAll('.quick-filter');
const viewOptions = document.querySelectorAll('.view-option');

// Initialize
function initialize() {
    fetchBooks();
    setupEventListeners();
}

// Event Listeners
function setupEventListeners() {
    searchButton.addEventListener('click', handleSearch);
    searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') handleSearch();
    });

    genreFilter.addEventListener('change', handleFiltersChange);
    sortSelect.addEventListener('change', handleFiltersChange);
    loadMoreButton.addEventListener('click', loadMore);

    // Quick filters
    quickFilters.forEach(filter => {
        filter.addEventListener('click', () => {
            quickFilters.forEach(f => f.classList.remove('active'));
            filter.classList.add('active');
            currentPage = 1;
            fetchBooks(filter.dataset.filter);
        });
    });

    // View options
    viewOptions.forEach(option => {
        option.addEventListener('click', () => {
            viewOptions.forEach(o => o.classList.remove('active'));
            option.classList.add('active');
            changeView(option.dataset.view);
        });
    });
}

// Fetch books from API
async function fetchBooks(quickFilter = 'all') {
    try {
        isLoading = true;
        loadingIndicator.style.display = 'block';

        const params = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            search: searchInput.value,
            genre: genreFilter.value,
            sort: sortSelect.value,
            filter: quickFilter
        });

        const response = await fetch(`../actions/auth/fetch_books.php?${params}`);
        const data = await response.json();

        if (data.success) {
            totalBooks = data.total;
            
            if (currentPage === 1) {
                filteredBooks = data.books;
                searchResults.innerHTML = '';
            } else {
                filteredBooks = [...filteredBooks, ...data.books];
            }

            displayBooks();
            updateResultsCount();
            updateLoadMoreButton();
        } else {
            throw new Error(data.error);
        }
    } catch (error) {
        console.error('Error fetching books:', error);
        showError('An error occurred while fetching books');
    } finally {
        isLoading = false;
        loadingIndicator.style.display = 'none';
    }
}

// Handle search
function handleSearch() {
    currentPage = 1;
    fetchBooks();
}

// Handle filters change
function handleFiltersChange() {
    currentPage = 1;
    fetchBooks();
}

// Change view
function changeView(view) {
    currentView = view;
    searchResults.className = `book-grid ${view}-view`;
}

// Display books
function displayBooks() {
    filteredBooks.forEach(book => {
        const bookCard = createBookCard(book);
        searchResults.appendChild(bookCard);
    });
}

// Create book card
function createBookCard(book) {
    const card = document.createElement('div');
    card.className = 'book-card';
    
    card.innerHTML = `
        <img src="" alt="${book.title}" class="book-cover" onerror="this.src=''">
        <div class="book-info">
            <h3 class="book-title">${book.title}</h3>
             <img src="${book.cover}" alt="${book.title}" class="img-fluid" 
                     onerror="this.src=''">
            <p class="book-author">by ${book.author}</p>
            <span class="book-genre">${book.genre}</span>
        </div>
    `;
    
    card.addEventListener('click', () => showBookDetails(book));
    
    return card;
}

// Show book details
function showBookDetails(book) {
    const modal = new bootstrap.Modal(document.getElementById('bookModal'));
    const modalBody = document.querySelector('.modal-body');
    
    modalBody.innerHTML = `
        <div class="book-detail-grid">
            <div class="book-detail-cover">
                <img src="${book.cover}" alt="${book.title}" class="img-fluid" 
                     onerror="this.src=''">
                <div class="book-rating mt-3">
                    Rating: ${book.rating}/5
                </div>
            </div>
            <div class="book-detail-info">
                <h2>${book.title}</h2>
                <h4>by ${book.author}</h4>
                <p class="book-description">${book.description}</p>
                <div class="book-metadata">
                    <p><strong>Genre:</strong> ${book.genre}</p>
                    <p><strong>Year:</strong> ${book.year}</p>
                    <p><strong>ISBN:</strong> ${book.details.isbn}</p>
                    <p><strong>Publisher:</strong> ${book.details.publisher}</p>
                    <p><strong>Pages:</strong> ${book.details.pages}</p>
                    <p><strong>Language:</strong> ${book.details.language}</p>
                </div>
            </div>
            <form action="../actions/books/borrow_book.php" method="POST" >
                 <input type="hidden" name="book_id" value="${book.id};">
                 <button type="submit" class="auth-btn">Borrow</button>
            </form>

        </div>
    `;
    
    modal.show();
}

// Update results count
function updateResultsCount() {
    resultsCount.textContent = `${totalBooks} results found`;
}

// Update load more button
function updateLoadMoreButton() {
    const shouldShow = filteredBooks.length < totalBooks && !isLoading;
    loadMoreButton.style.display = shouldShow ? 'block' : 'none';
}

// Load more books
function loadMore() {
    if (!isLoading) {
        currentPage++;
        fetchBooks();
    }
}

// Show error message
function showError(message) {
    // Implement error messaging (could be a toast, alert, or custom error display)
    console.error(message);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initialize);