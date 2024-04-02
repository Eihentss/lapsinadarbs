<?php
// Include config.php to establish a connection with the database
require 'config.php';

// Get parameters from POST
$title = $_POST['title'];
$author = $_POST['author'];
$year = intval($_POST['year']);
$availability = intval($_POST['availability']);

// Insert the new book into the Books table
$sql = "INSERT INTO Books (title, author, publication_year, availability) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $title);
$stmt->bindParam(2, $author);
$stmt->bindParam(3, $year);
$stmt->bindParam(4, $availability);
$stmt->execute();



// Close the connection
$pdo = null;
?>
