<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>



<div class="container">

    <?php
// Include config.php to establish a connection with the database
require '../../backend/config.php';
require 'navbar.php';
session_start();
$role = $_SESSION['role'];

if ($role == 'user') {
    header("Location: user.php");
} 
// Output books and a borrow button for each record
$sql = "SELECT * FROM Books"; // SQL vaicājums, lai iegūtu visus ierakstus no Books tabulas

$result = $pdo->query($sql);

if ($result->rowCount() > 0) {
    $rows = $result->fetchAll();
    $counter = 1;
    echo "<button onclick='showAddForm()'>ADD</button>";
        echo "<div id='addForm' style='display: none;'>";
            echo "<input type='text' id='newTitle' placeholder='Title'><br>";
            echo "<input type='text' id='newAuthor' placeholder='Author'><br>";
            echo "<input type='number' id='newYear' placeholder='Publication Year'><br>";
            echo "<input type='number' id='newAvailability' placeholder='Availability'><br>";
            echo "<button onclick='addBook()'>Submit</button><br>";
        echo "</div>";

    foreach($rows as $row) {
        $availability = $row["availability"] == 1 ? 'Pieejams' : 'Nav Pieejams';
        echo "<div class='main'>";
            echo "<div class='admin-divs'>";
                echo "<input type='text' id='title" . $row["book_id"] . "' value='" . $row["title"] . "'><br>";
                echo "<input type='text' id='author" . $row["book_id"] . "' value='" . $row["author"] . "'><br>";
                echo "<input type='number' id='year" . $row["book_id"] . "' value='" . $row["publication_year"] . "'><br>";
                echo "<input type='number' id='availability" . $row["book_id"] . "' value='" . $row["availability"] . "'><br>";
                echo "<button onclick='showSettings(" . $row["book_id"] . ")'>Settings</button><div id='settings" . $row["book_id"] . "' style='display: none;'>";

               
                echo "<button onclick='editBook(" . $row["book_id"] . ")'>Rediģēt</button><br>";

                ?>
                    <form method="post" action="../../backend/deleteBook.php">
                        <input type="hidden" name="book_id" id="book_id" value="<?php echo $row['book_id']; ?>">
                        <button type="submit">Dzēst grāmatu</button>
                    </form>
                <?php
            echo "</div>";
            echo "</div>";
            
            
        

        echo "</div>";
    }
} else {
    echo "0 results";
}








// Close the connection
$pdo = null;
?>
<script>
function reloadPage() {
    location.reload();
}

function showBorrowForm(bookId) {
    document.getElementById('borrowForm' + bookId).style.display = 'block';
}
function borrowBook(bookId, counter) {
    var days = document.getElementById('days' + bookId).value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Izvada atbildi, ja ir izsaukta funkcija
            var resultElement = document.getElementById('result' + counter);
            if (resultElement) {
                resultElement.innerHTML = this.responseText;
            }
        }
    };
    xhttp.open('POST', '../../backend/borrowBook.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('book_id=' + bookId + '&days=' + days);
}

function returnBook(bookId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var resultElement = document.getElementById('result' + bookId);
            if (resultElement !== null) { // Pārbauda, vai elements ir pieejams
                resultElement.innerHTML = this.responseText;
            }
            // Paslēpj rezultāta divu, ja tas ir atrasts
            if (resultElement) {
                resultElement.style.display = 'none';
            }
        }
    };
    xhttp.open('POST', '../../backend/returnBook.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('book_id=' + bookId);
}




function editBook(bookId) {
    var title = document.getElementById('title' + bookId).value;
    var author = document.getElementById('author' + bookId).value;
    var year = document.getElementById('year' + bookId).value;
    var availability = document.getElementById('availability' + bookId).value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            reloadPage();
        }
    };
    xhttp.open('POST', '../../backend/editBook.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('book_id=' + bookId + '&title=' + title + '&author=' + author + '&year=' + year + '&availability=' + availability);
}

function showSettings(bookId) {
    var settingsDiv = document.getElementById('settings' + bookId);
    if (settingsDiv.style.display === 'none' || settingsDiv.style.display === '') {
        settingsDiv.style.display = 'block'; // Show the settings div
    } else {
        settingsDiv.style.display = 'none'; // Hide the settings div
    }
}

function showAddForm() {
    var addForm = document.getElementById('addForm');
    addForm.style.display = 'block'; // Show the add form
}

function addBook() {
    var title = document.getElementById('newTitle').value;
    var author = document.getElementById('newAuthor').value;
    var year = document.getElementById('newYear').value;
    var availability = document.getElementById('newAvailability').value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            reloadPage();
        }
    };
    xhttp.open('POST', '../../backend/addBook.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('title=' + title + '&author=' + author + '&year=' + year + '&availability=' + availability);
}


</script>

</body>
</html>