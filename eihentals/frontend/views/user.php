<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user page</title>
    <link rel="stylesheet" href="../css/user.css">

</head>
<body>
<?php
// Include config.php to establish a connection with the database
require '../../backend/config.php';
require 'navbar.php';
session_start();
if (isset($_SESSION['username'])) {
    // Output books and a borrow button for each record
    $sql = "SELECT * FROM Books"; // SQL vaicājums, lai iegūtu visus ierakstus no Books tabulas
    $result = $pdo->query($sql); // Izpildam vaicājumu
    if ($result->rowCount() > 0) { // Pārbaudam, vai ir iegūti rezultāti
        $rows = $result->fetchAll(); // Iegūstam visus ierakstus
        echo "<div class='main'>";
        foreach($rows as $row) { // Iterējam caur katru ierakstu
            echo "<div class='admin-divs'>";
            // Izvadam grāmatas informāciju
            echo "<h1 id='book" . $row["book_id"] . "'>" . $row["title"] . "</h1><br>";
            echo "<div id='author" . $row["book_id"] . "'> Autors: " . $row["author"] . "</div><br>";
            echo "<div id='year" . $row["book_id"] . "'> Izlaišanas gads: " . $row["publication_year"] . "</div><br>";
            
            // Pārbauda, vai pieejamība ir 0, un ja tā, tad attēlo tekstu "Nav pieejams"
            if ($row["availability"] == 0) {
                echo "<div id='availability" . $row["book_id"] . "'> Nav pieejams</div><br>";
            } else {
                echo "<div id='availability" . $row["book_id"] . "'> Daudzums: " . $row["availability"] . "</div><br>";
                echo "<button onclick='showBorrowForm(" . $row["book_id"] . ")'>Aizņemties grāmatu</button><br>";
                echo "<div id='borrowForm" . $row["book_id"] . "' style='display: none;'>
                <input type='number' id='days" . $row["book_id"] . "' class='input-small' min='1' max='7' placeholder='Dienas'>
                <input type='number' id='availabilityInput" . $row["book_id"] . "' class='input-small' placeholder='Daudzums'>
                
                        <button onclick='borrowBook(" . $row["book_id"] . ", " . $row["book_id"] . ")'>Apstiprināt</button>
                    </div>";
            }
            
            // Iegūstam attēla ceļu no Book_Images tabulas, ja tāds eksistē
            $image_sql = "SELECT * FROM Book_Images WHERE book_id = :book_id";
            $image_stmt = $pdo->prepare($image_sql);
            $image_stmt->execute(['book_id' => $row['book_id']]);
            $image_row = $image_stmt->fetch(PDO::FETCH_ASSOC);
            

            
            echo "</div>";
        }
        
        
        
        echo "</div>"; // Aizveram pēdējo main div, lai nodrošinātu pareizu izkārtojumu
    } else {
        echo "Grāmatas nav pieejamas";
    }
} else {
    // Ja lietotājs nav ieņemies, izvadam paziņojumu
    echo "<p>Please login to view taken books.</p>";
}
?>




<script>
function showBorrowForm(bookId) {
    var borrowForm = document.getElementById('borrowForm' + bookId);
    if (borrowForm.style.display === 'block') {
        borrowForm.style.display = 'none'; // Ja jau ir redzams, tad padarīt neredzamu
    } else {
        borrowForm.style.display = 'block'; // Ja nav redzams, tad padarīt redzamu
    }
}

function borrowBook(bookId, counter) {
    var days = document.getElementById('days' + bookId).value;
    var availabilityInput = document.getElementById('availabilityInput' + bookId).value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Atbildes apstrāde
            // Atjauno grāmatas pieejamības daļu ar rezultātu no servera
            var response = this.responseText;
            document.getElementById('availability' + bookId).innerHTML = 'Daudzums: ' + response;
        }
    };
    xhttp.open('POST', '../../backend/borrowBook.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('book_id=' + bookId + '&days=' + days + '&availabilityInput=' + availabilityInput);
}
</script>
</body>
</html>
