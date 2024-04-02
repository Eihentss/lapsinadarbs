<?php


class BookLoan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function returnBook($id) {
        // Start a transaction
        $this->pdo->beginTransaction();

        // Get the book_id from the Book_Loans table
        $sql = "SELECT book_id FROM Book_Loans WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $book_id = $stmt->fetchColumn();

        // Delete the loan record
        $sql = "DELETE FROM Book_Loans WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        // Update the availability in the Books table
        $sql = "UPDATE Books SET availability = availability + 1 WHERE book_id = :book_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['book_id' => $book_id]);

        // Commit the transaction
        $this->pdo->commit();
    }
}

// Check if id is set
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $bookLoan = new BookLoan($pdo);
    $bookLoan->returnBook($id);
}
?>
