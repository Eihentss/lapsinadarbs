<?php
require 'config.php';
session_start();

class BookLoan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function loanBook($book_id, $days, $availabilityInput) {
        $sql = "SELECT * FROM Books WHERE book_id = ? AND availability > 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1, $book_id);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) == 0) {
            return "Grāmata nav pieejama šobrīd";
        }

        $sql = "SELECT * FROM Users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1, $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) == 0) {
            return "Lietotājs neeksistē.";
        }

        $user_id = $result[0]['user_id'];

        if ($availabilityInput >= 1) {
            $sql = "INSERT INTO Book_Loans (book_id, user_id, loan_date, return_date)
            VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL ? DAY))";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(1, $book_id);
            $stmt->bindParam(2, $user_id);
            $stmt->bindParam(3, $days);

            if ($stmt->execute() === TRUE) {
                $sql = "SELECT availability FROM Books WHERE book_id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $book_id);
                $stmt->execute();
                $existingAvailability = $stmt->fetchColumn();
        
                $newAvailability = max(0, $existingAvailability - $availabilityInput);
        
                $sql = "UPDATE Books SET availability = ? WHERE book_id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $newAvailability);
                $stmt->bindParam(2, $book_id);
                $stmt->execute();
                
                $sql = "SELECT * FROM Book_Loans WHERE book_id = ? AND user_id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $book_id);
                $stmt->bindParam(2, $user_id);
                $stmt->execute();
                $loan_result = $stmt->fetchAll();

                return $newAvailability;
            } else {
                return 'Kļūda: ' . $sql . '<br>' . $this->pdo->errorInfo();
            }
        } else {
            $sql = "SELECT availability FROM Books WHERE book_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(1, $book_id);
            $stmt->execute();
            $currentAvailability = $stmt->fetchColumn();

            return $currentAvailability;
        }
    }
}

$bookLoan = new BookLoan($pdo);
echo $bookLoan->loanBook(intval($_POST['book_id']), intval($_POST['days']), intval($_POST['availabilityInput']));

$pdo = null;
?>
