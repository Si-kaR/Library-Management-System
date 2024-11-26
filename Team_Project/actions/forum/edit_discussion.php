<?php
session_start();
require_once '../../db/config.php';

if (!isset($_SESSION['user_id'])) {
   $_SESSION['error'] = "Please login to edit discussions";
   header("Location: ../../view/auth/login.php");
   exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['discussion_id'])) {
   $discussion_id = (int)$_POST['discussion_id'];
   $user_id = $_SESSION['user_id'];
   $title = trim($_POST['title']);
   $content = trim($_POST['content']);
   $tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';

   try {
       // Check if user owns the discussion or is admin
       $check_sql = "SELECT user_id FROM discussions WHERE id = ?";
       $check_stmt = $conn->prepare($check_sql);
       $check_stmt->bind_param("i", $discussion_id);
       $check_stmt->execute();
       $result = $check_stmt->get_result();
       
       if ($result->num_rows === 0) {
           throw new Exception("Discussion not found");
       }
       
       $discussion = $result->fetch_assoc();
       if ($discussion['user_id'] != $user_id && $_SESSION['role'] != 'admin') {
           throw new Exception("Unauthorized to edit this discussion");
       }

       // Validate inputs
       if (empty($title) || empty($content)) {
           throw new Exception("Title and content are required");
       }

       // Start transaction
       $conn->begin_transaction();

       // Update discussion
       $update_sql = "UPDATE discussions 
                     SET title = ?, 
                         content = ?, 
                         tags = ?,
                         updated_at = CURRENT_TIMESTAMP 
                     WHERE id = ?";
       
       $update_stmt = $conn->prepare($update_sql);
       $update_stmt->bind_param("sssi", $title, $content, $tags, $discussion_id);
       
       if ($update_stmt->execute()) {
           $conn->commit();
           $_SESSION['success'] = "Discussion updated successfully";
           header("Location: ../../view/forum/discussion.php?id=" . $discussion_id);
           exit();
       }

   } catch (Exception $e) {
       $conn->rollback();
       $_SESSION['error'] = $e->getMessage();
       header("Location: ../../view/forum/edit.php?id=" . $discussion_id);
       exit();
   }

   // Close statements
   if (isset($check_stmt)) $check_stmt->close();
   if (isset($update_stmt)) $update_stmt->close();

} else {
   header("Location: ../../view/forum/index.php");
   exit();
}

$conn->close();
?>