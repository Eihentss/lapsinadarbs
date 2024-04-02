<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taken Books</title>
    <link rel="stylesheet" href="../css/books.css">
</head>
<body>

<?php
session_start();
require '../../backend/config.php';
require 'navbar.php';


echo "<div book-container'>";
    // Pārbaudam, vai lietotājs ir ieņēmies
    if (isset($_SESSION['username'])) {
        // Izveidojam SQL vaicājumu, lai iegūtu visas paņemtās grāmatas
        $sql = "SELECT Books.*, Book_Loans.id, Book_Loans.return_date, Users.username
                FROM Books 
                INNER JOIN Book_Loans ON Books.book_id = Book_Loans.book_id 
                INNER JOIN Users ON Book_Loans.user_id = Users.user_id
                WHERE Users.username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $_SESSION['username']]);
        
        // Parbaudam, vai atrasts vismaz viens rezultāts
        if ($stmt->rowCount() > 0) {
            echo "<div class='taken-books-container'>";
            echo "<h2>Taken Books</h2>";
            
            // Parādam katru paņemto grāmatu
            while ($row = $stmt->fetch()) {
                echo "<div class='book'>";
                echo "<p><strong>Title:</strong> " . $row["title"] . "</p>";
                echo "<p><strong>Author:</strong> " . $row["author"] . "</p>";
                echo "<p><strong>Publication Year:</strong> " . $row["publication_year"] . "</p>";
                echo "<p><strong>Return Date:</strong> " . $row["return_date"] . "</p>";
                
                // Pārbaudam, vai šo grāmatu paņēmis šis lietotājs

                    echo "<button onclick='returnBook(" . $row["id"] . ")'>Atgriezt grāmatu</button><br>";
                
                
                echo "</div>";
            }
            
            echo "</div>";
        } else {
            // Ja nav atrasts neviens rezultāts, izvadam paziņojumu
            echo "<div class='taken-books-container'>";
            echo "<p>Jūs neēsat paņēmis nevienu grāmatu</p>";
            echo "</div>";
        }
    } else {
        // Ja lietotājs nav ieņemies, izvadam paziņojumu
        echo "<div class='taken-books-container'>";
        echo "<p>Please login to view taken books.</p>";
        echo "</div>";
    }
echo "</div>";
?>
<script>
function returnBook(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Replace the contents of the page with the response from the AJAX call
            document.body.innerHTML = this.responseText;
        }
    };
    xhttp.open('POST', '../../backend/returnBook.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('id=' + id);
}


</script>
</body>
</html>
