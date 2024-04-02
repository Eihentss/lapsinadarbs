<?php
// Include config.php to establish a connection with the database
require 'config.php';

// Get parameters from POST
$book_id = intval($_POST['book_id']);
$title = $_POST['title'];
$author = $_POST['author'];
$year = intval($_POST['year']);
$availability = intval($_POST['availability']);

// Update the record in the Books table
$sql = "UPDATE Books SET title = ?, author = ?, publication_year = ?, availability = ? WHERE book_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $title);
$stmt->bindParam(2, $author);
$stmt->bindParam(3, $year);
$stmt->bindParam(4, $availability);
$stmt->bindParam(5, $book_id);
$stmt->execute();


// Close the connection
$pdo = null;
?>
