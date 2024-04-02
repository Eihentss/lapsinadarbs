<?php
require 'config.php';
require '../frontend/views/navbar.php';
session_start();

require 'updated_books.php';

class BookContainer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function displayBooks() {
        if (isset($_SESSION['username'])) {
            $sql = "SELECT Books.*, Book_Loans.id, Book_Loans.return_date, Users.username
                    FROM Books 
                    INNER JOIN Book_Loans ON Books.book_id = Book_Loans.book_id 
                    INNER JOIN Users ON Book_Loans.user_id = Users.user_id
                    WHERE Users.username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['username' => $_SESSION['username']]);
            
            if ($stmt->rowCount() > 0) {
                echo "<div class='taken-books-container'>";
                echo "<h2>Taken Books</h2>";
                
                while ($row = $stmt->fetch()) {
                    echo "<div class='book'>";
                    echo "<p><strong>Title:</strong> " . $row["title"] . "</p>";
                    echo "<p><strong>Author:</strong> " . $row["author"] . "</p>";
                    echo "<p><strong>Publication Year:</strong> " . $row["publication_year"] . "</p>";
                    echo "<p><strong>Return Date:</strong> " . $row["return_date"] . "</p>";
                    echo "<button onclick='returnBook(" . $row["id"] . ")'>Atgriezt grāmatu</button><br>";
                    echo "</div>";
                }
                
                echo "</div>";
            } else {
                echo "<div class='taken-books-container'>";
                echo "<p>Jūs neēsat paņēmis nevienu grāmatu</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='taken-books-container'>";
            echo "<p>Please login to view taken books.</p>";
            echo "</div>";
        }
    }
}

$bookContainer = new BookContainer($pdo);
$bookContainer->displayBooks();
?>
