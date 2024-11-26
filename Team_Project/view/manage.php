<?php
session_start();
require_once '../db/config.php';
require_once '../auth_functions/book_functions.php';
// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: ../index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Books - Library System</title>
    <!-- <link rel="stylesheet" href="../../assets/css/styles.css"> -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #f5f7fa, #e8ecf1);
            min-height: 100vh;
        }

        .container {
            max-width: 75rem;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #2B4570;
            font-weight: 600;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 0.0625rem solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 0.0625rem solid #f5c6cb;
        }

        .actions {
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #2B4570;
            color: white;
            text-decoration: none;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn:hover {
            background-color: #1a2a43;
            transform: translateY(-0.125rem);
        }

        .books-table {
            width: 100%;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 1rem rgba(43, 69, 112, 0.1);
            border-collapse: collapse;
            overflow: hidden;
        }

        .books-table th,
        .books-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 0.0625rem solid #e9ecef;
        }

        .books-table th {
            background-color: #2B4570;
            color: white;
            font-weight: 500;
        }

        .books-table tr:hover {
            background-color: #f8f9fa;
        }

        .books-table img {
            width: 3.75rem;
            height: 5rem;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .edit-btn, 
        .delete-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .edit-btn {
            background-color: #2B4570;
            color: white;
        }

        .edit-btn:hover {
            background-color: #1a2a43;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-available {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-borrowed {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Responsive Design */
        @media screen and (max-width: 64rem) {
            .container {
                padding: 1.5rem;
            }

            .books-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media screen and (max-width: 48rem) {
            h1 {
                font-size: 1.75rem;
            }

            .btn {
                display: block;
                width: 100%;
                text-align: center;
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .edit-btn, 
            .delete-btn {
                text-align: center;
            }

            .books-table th,
            .books-table td {
                padding: 0.75rem;
                font-size: 0.875rem;
            }
        }

        @media screen and (max-width: 36rem) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .books-table img {
                width: 3rem;
                height: 4rem;
            }
        }

        /* Delete Confirmation Modal */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-width: 90%;
            width: 30rem;
        }

        .modal.active {
            display: block;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .modal-overlay.active {
            display: block;
        }

        .modal-title {
            color: #2B4570;
            margin-bottom: 1rem;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .modal-cancel {
            background-color: #6c757d;
        }

        .modal-confirm {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Books</h1>
        


        <div class="actions">
            <a href="add.php" class="btn">Add New Book</a>
        </div>
        
        <table class="books-table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
            displayBooks($conn);

            ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php $conn->close(); ?>
