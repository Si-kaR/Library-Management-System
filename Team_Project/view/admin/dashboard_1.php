<?php
session_start();
require_once '../../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get user's activity data
$userStats = [];
if ($user['role'] === 'user') {
    // Get borrowed books
    $borrowed_sql = "SELECT b.title, br.borrow_date, br.due_date, br.status 
                    FROM borrowing_records br 
                    JOIN books b ON br.book_id = b.id 
                    WHERE br.user_id = ? 
                    ORDER BY br.borrow_date DESC";
    $borrowed_stmt = $conn->prepare($borrowed_sql);
    $borrowed_stmt->bind_param("i", $user_id);
    $borrowed_stmt->execute();
    $userStats['borrowed_books'] = $borrowed_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Get admin statistics
$adminStats = [];
if ($user['role'] === 'admin') {
    $adminStats['total_books'] = $conn->query("SELECT COUNT(*) as total FROM books")->fetch_assoc()['total'];
    $adminStats['total_users'] = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
    $adminStats['active_borrowings'] = $conn->query("SELECT COUNT(*) as total FROM borrowing_records WHERE status = 'borrowed'")->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Dashboard</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <style>
        /* Add your custom styles here */
        .dashboard-nav-active {
            background-color: rgba(59, 130, 246, 0.1);
            border-left: 4px solid #3B82F6;
        }
        
        .admin-section, .user-section {
            display: none;
        }
        
        .section-active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <?php echo $user['role'] === 'admin' ? 'Admin Panel' : 'User Dashboard'; ?>
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Welcome, <?php echo htmlspecialchars($user['fname']); ?>
                </p>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6">
                <?php if ($user['role'] === 'admin'): ?>
                    <!-- Admin Navigation -->
                    <a href="#" onclick="showSection('dashboard')" class="flex items-center px-6 py-3 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-tachometer-alt w-5 h-5 text-gray-500"></i>
                        <span class="mx-3">Dashboard</span>
                    </a>
                    <a href="#" onclick="showSection('books')" class="flex items-center px-6 py-3 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-book w-5 h-5 text-gray-500"></i>
                        <span class="mx-3">Manage Books</span>
                    </a>
                    <a href="#" onclick="showSection('users')" class="flex items-center px-6 py-3 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-users w-5 h-5 text-gray-500"></i>
                        <span class="mx-3">Manage Users</span>
                    </a>
                <?php else: ?>
                    <!-- User Navigation -->
                    <a href="#" onclick="showSection('borrowed')" class="flex items-center px-6 py-3 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-book-reader w-5 h-5 text-gray-500"></i>
                        <span class="mx-3">My Books</span>
                    </a>
                    <a href="#" onclick="showSection('history')" class="flex items-center px-6 py-3 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-history w-5 h-5 text-gray-500"></i>
                        <span class="mx-3">History</span>
                    </a>
                <?php endif; ?>
                <a href="logout.php" class="flex items-center px-6 py-3 hover:bg-red-100 transition-colors mt-auto">
                    <i class="fas fa-sign-out-alt w-5 h-5 text-red-500"></i>
                    <span class="mx-3 text-red-500">Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Admin Sections -->
            <?php if ($user['role'] === 'admin'): ?>
                <!-- Admin Dashboard -->
                <div id="section-dashboard" class="admin-section p-6">
                    <h2 class="text-2xl font-semibold mb-6">Dashboard Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Stats Cards -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                                    <i class="fas fa-book text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Total Books</p>
                                    <p class="text-lg font-semibold"><?php echo $adminStats['total_books']; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Total Users</p>
                                    <p class="text-lg font-semibold"><?php echo $adminStats['total_users']; ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                                    <i class="fas fa-book-reader text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-500">Active Borrowings</p>
                                    <p class="text-lg font-semibold"><?php echo $adminStats['active_borrowings']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-4">Recent Activities</h3>
                            <!-- Add your recent activities table here -->
                        </div>
                    </div>
                </div>

                <!-- Books Management Section -->
                <div id="section-books" class="admin-section p-6" style="display: none;">
                    <h2 class="text-2xl font-semibold mb-6">Manage Books</h2>
                    <div class="mb-6">
                        <a href="add_book.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Add New Book
                        </a>
                    </div>
                    <!-- Add your books management table here -->
                </div>

                <!-- Users Management Section -->
                <div id="section-users" class="admin-section p-6" style="display: none;">
                    <h2 class="text-2xl font-semibold mb-6">Manage Users</h2>
                    <!-- Add your users management table here -->
                </div>

            <?php else: ?>
                <!-- User Sections -->
                <!-- Borrowed Books Section -->
                <div id="section-borrowed" class="user-section p-6">
                    <h2 class="text-2xl font-semibold mb-6">My Borrowed Books</h2>
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Book Title
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Borrow Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Due Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($userStats['borrowed_books'] as $book): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($book['title']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo date('M d, Y', strtotime($book['borrow_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo date('M d, Y', strtotime($book['due_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php echo $book['status'] === 'borrowed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                            <?php echo ucfirst($book['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- History Section -->
                <div id="section-history" class="user-section p-6" style="display: none;">
                    <h2 class="text-2xl font-semibold mb-6">Borrowing History</h2>
                    <!-- Add borrowing history table here -->
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.admin-section, .user-section').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show selected section
            const selectedSection = document.getElementById('section-' + sectionName);
            if (selectedSection) {
                selectedSection.style.display = 'block';
            }
            
            // Update navigation active states
            document.querySelectorAll('nav a').forEach(link => {
                link.classList.remove('dashboard-nav-active');
            });
            event.currentTarget.classList.add('dashboard-nav-active');
        }

        // Show default section on load
        document.addEventListener('DOMContentLoaded', function() {
            const defaultSection = '<?php echo $user['role'] === 'admin' ? 'dashboard' : 'borrowed'; ?>';
            showSection(defaultSection);
        });
    </script>
</body>
</html>