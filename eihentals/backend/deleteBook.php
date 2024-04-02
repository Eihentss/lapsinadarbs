<?php
// Include config.php to establish a connection with the database
require 'config.php';

// Get book_id from POST parameters
$book_id = intval($_POST['book_id']);

// Delete the record from the Books table
$sql = "DELETE FROM Books WHERE book_id = :book_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':book_id', $book_id);
$stmt->execute();

// Close the connection
$pdo = null;

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
