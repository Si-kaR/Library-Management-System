<?php
session_start();
require_once '../../db/config.php';

if (!isset($_SESSION['user_id'])) {
   $_SESSION['error'] = "Please login to create discussions";
   header("Location: ../../view/auth/login.php");
   exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $user_id = $_SESSION['user_id'];
   $category_id = (int)$_POST['category_id'];
   $title = trim($_POST['title']);
   $content = trim($_POST['content']);
   $tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';

   // Validate inputs
   if (empty($title) || empty($content) || empty($category_id)) {
       $_SESSION['error'] = "Title, content and category are required";
       header("Location: ../../view/forum/create.php");
       exit();
   }

   try {
       // Start transaction
       $conn->begin_transaction();

       // Insert discussion
       $sql = "INSERT INTO discussions (category_id, user_id, title, content, tags) 
               VALUES (?, ?, ?, ?, ?)";
       
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("iisss", 
           $category_id,
           $user_id,
           $title,
           $content,
           $tags
       );

       if ($stmt->execute()) {
           $discussion_id = $conn->insert_id;
           
           // Process tags if provided
           if (!empty($tags)) {
               $tag_array = array_map('trim', explode(',', $tags));
               foreach ($tag_array as $tag) {
                   $tag = strtolower($tag);
                   // You might want to store tags in a separate table
                   // and create relationships here
               }
           }

           $conn->commit();
           $_SESSION['success'] = "Discussion created successfully";
           header("Location: ../../view/forum/discussion.php?id=" . $discussion_id);
           exit();
       }

   } catch (Exception $e) {
       $conn->rollback();
       $_SESSION['error'] = "Error creating discussion: " . $e->getMessage();
       header("Location: ../../view/forum/create.php");
       exit();
   }

   $stmt->close();
} else {
   header("Location: ../../view/forum/create.php");
   exit();
}

$conn->close();
?>