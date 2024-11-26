// Sample discussion data
const discussions = [
    {
        id: 1,
        title: "Monthly Book Club: 'The Great Gatsby' Discussion",
        category: "book-clubs",
        author: {
            name: "Sarah Johnson",
            avatar: "The Great Gatsby.jpeg"
        },
        content: "Let's discuss our thoughts on The Great Gatsby...",
        tags: ["book-club", "classics", "fiction"],
        createdAt: "2024-02-15T10:30:00",
        replies: 24,
        view: 156,
        likes: 18,
        isSticky: true
    },
    {
        id: 2,
        title: "African Literature Reading Group: 'Things Fall Apart' Analysis",
        category: "book-clubs",
        author: {
            name: "Kwame Mensah",
            avatar: "Things Fall Apart_Chinua Achebe.jpg"
        },
        content: "Let's explore Chinua Achebe's masterpiece. This month we're focusing on the clash between Igbo traditions and colonial influence. How does Okonkwo's character represent this conflict?",
        tags: ["african-literature", "classics", "colonialism"],
        createdAt: "2024-02-18T10:00:00",
        replies: 45,
        view: 320,
        likes: 28,
        isSticky: true
    },
    {
        id: 3,
        title: "Homegoing vs Things Fall Apart: Portrayal of Colonial Impact",
        category: "book-discussions",
        author: {
            name: "Literary Scholar",
            avatar: "Home Going_Yaa Gyasi.jpg"
        },
        content: "Comparing Gyasi's and Achebe's approaches to colonialism and its generational effects. How do these narratives complement each other?",
        tags: ["comparative-literature", "colonialism", "african-literature"],
        createdAt: "2024-02-16T09:15:00",
        replies: 83,
        view: 567,
        likes: 92,
        isSticky: false
    },
    {
        id: 4,
        title: "Science Book Club: 'A Brief History of Time' Discussion",
        category: "study-groups",
        author: {
            name: "Physics Enthusiast",
            avatar: "Adichie.jpeg.jpg"
        },
        content: "This week we're discussing Chapters 1-3 of Hawking's masterpiece. Share your insights on spacetime and black holes. Questions welcome!",
        tags: ["science", "physics", "cosmology"],
        createdAt: "2024-02-15T16:45:00",
        replies: 34,
        view: 289,
        likes: 25,
        isSticky: false
    },
    {
        id: 5,
        title: "Modern African Women Writers: Adichie's Impact",
        category: "book-clubs",
        author: {
            name: "Literature Prof",
            avatar: "school boy.jpeg"
        },
        content: "From 'We Should All Be Feminists' to 'Americanah', how has Adichie shaped contemporary African literature and feminist discourse?",
        tags: ["feminism", "contemporary-literature", "african-literature"],
        createdAt: "2024-02-14T11:20:00",
        replies: 92,
        view: 678,
        likes: 105,
        isSticky: true
    }
       // Add more sample discussions...
];

// DOM Elements
document.addEventListener('DOMContentLoaded', function() {
    const discussionsList = document.querySelector('.discussions-list');
    const searchInput = document.querySelector('.search-bar input');
    const categoryLinks = document.querySelectorAll('.list-group-item');
    const sortSelect = document.querySelector('.filters select');
    const newTopicForm = document.getElementById('newTopicForm');

    // Initialize
    loadDiscussions();
    setupEventListeners();

    // Event Listeners
    function setupEventListeners() {
        // Search
        searchInput.addEventListener('input', debounce(handleSearch, 300));

        // Category Filter
        categoryLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                categoryLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                filterByCategory(link.dataset.category);
            });
        });

        // Sort
        sortSelect.addEventListener('change', handleSort);

        // New Topic Form
        newTopicForm?.addEventListener('submit', handleNewTopic);
    }

    // Load Discussions
    function loadDiscussions(filteredDiscussions = discussions) {
        discussionsList.innerHTML = '';
        
        if (filteredDiscussions.length === 0) {
            discussionsList.innerHTML = `
                <div class="text-center text-muted p-4">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>No discussions found</p>
                </div>
            `;
            return;
        }

        filteredDiscussions.forEach(discussion => {
            const discussionElement = createDiscussionElement(discussion);
            discussionsList.appendChild(discussionElement);
        });
    }

    // Create Discussion Element
    function createDiscussionElement(discussion) {
        const div = document.createElement('div');
        div.className = 'discussion-item';
        
        div.innerHTML = `
            <div class="discussion-header">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="${discussion.author.avatar}" alt="" class="user-avatar">
                        <div>
                            <h3 class="discussion-title">${discussion.title}</h3>
                            <div class="discussion-meta">
                                Started by ${discussion.author.name} · ${formatDate(discussion.createdAt)}
                            </div>
                        </div>
                    </div>
                    <span class="discussion-category">${discussion.category}</span>
                </div>
                <div class="discussion-stats">
                    <span><i class="fas fa-comment"></i> ${discussion.replies}</span>
                    <span><i class="fas fa-eye"></i> ${discussion.view}</span>
                    <span><i class="fas fa-heart"></i> ${discussion.likes}</span>
                </div>
            </div>
            <div class="discussion-tags">
                ${discussion.tags.map(tag => `<a href="#" class="tag">#${tag}</a>`).join('')}
            </div>
        `;
        
        div.addEventListener('click', () => viewDiscussion(discussion.id));
        
        return div;
    }

    // Search Handler
    function handleSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        
        const filteredDiscussions = discussions.filter(discussion => 
            discussion.title.toLowerCase().includes(searchTerm) ||
            discussion.content.toLowerCase().includes(searchTerm) ||
            discussion.tags.some(tag => tag.toLowerCase().includes(searchTerm))
        );
        
        loadDiscussions(filteredDiscussions);
    }

    // Category Filter
    function filterByCategory(category) {
        if (category === 'all') {
            loadDiscussions();
            return;
        }
        
        const filteredDiscussions = discussions.filter(
            discussion => discussion.category === category
        );
        
        loadDiscussions(filteredDiscussions);
    }

    // Sort Handler
    function handleSort() {
        const sortBy = sortSelect.value;
        const sortedDiscussions = [...discussions];
        
        switch(sortBy) {
            case 'recent':
                sortedDiscussions.sort((a, b) => 
                    new Date(b.createdAt) - new Date(a.createdAt)
                );
                break;
            case 'popular':
                sortedDiscussions.sort((a, b) => b.view - a.view);
                break;
            case 'unanswered':
                sortedDiscussions.sort((a, b) => a.replies - b.replies);
                break;
        }
        
        loadDiscussions(sortedDiscussions);
    }

    // New Topic Handler
    function handleNewTopic(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(e.target);
        const newTopic = {
            title: formData.get('title'),
            category: formData.get('category'),
            content: formData.get('content'),
            tags: formData.get('tags').split(',').map(tag => tag.trim()),
            createdAt: new Date().toISOString(),
            author: {
                name: "Current User", // Replace with actual user data
                avatar: "/api/placeholder/40/40"
            },
            replies: 0,
            view: 0,
            likes: 0
        };
        
        // Add to discussions array (in real app, would be API call)
        discussions.unshift(newTopic);
        
        // Reset form and close modal
        e.target.reset();
        bootstrap.Modal.getInstance(document.getElementById('newTopicModal')).hide();
        
        // Reload discussions
        loadDiscussions();
    }

    // View Discussion
    function viewDiscussion(id) {
        // Redirect to discussion page
        window.location.href = `discussion.html?id=${id}`;
    }

    // Utility Functions
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});