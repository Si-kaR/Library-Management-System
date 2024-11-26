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

function returnBook(bookId) {
    console.log(bookId)
    const book = userData.borrowingHistory.find(b => b.id === bookId);
    if (!book) return;
    
    // Here you would typically make an API call
    showMessage('Book return processed successfully', 'success');
}

// fetch('path_to_php_file')
//     .then(response => response.json())
//     .then(data => {
//         if (data.error) {
//             console.error(data.error); // Log error
//             return;
//         }

//         if (data.message) {
//             console.log(data.message); // Log message if no books
//             return;
//         }

//         // If books are returned, render them
//         displayBorrowings(data);
//     })
//     .catch(error => {
//         console.error('Error:', error);
//     });


