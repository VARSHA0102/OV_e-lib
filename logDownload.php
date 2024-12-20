<?php
include "database.php";
session_start();

// Ensure student is logged in
if (!isset($_SESSION['STUDENT_ID'])) {
    header("Location: ulogin.php");
    exit();
}

if (isset($_GET['book_id'])) {
    // Escape book ID to prevent SQL injection
    $book_id = $db->real_escape_string($_GET['book_id']);

    // Fetch book details
    $book_sql = "SELECT * FROM book WHERE BOOK_ID = '$book_id'";
    $book_res = $db->query($book_sql);

    if ($book_res && $book_res->num_rows > 0) {
        $book_row = $book_res->fetch_assoc();

        // Student details
        $reg_no = $_SESSION['STUDENT_REG_NO'];
        $dept = $book_row['BOOK_DEPT'];
        $year = $book_row['BOOK_YEAR'];
        $book_title = $book_row['BOOK_TITLE'];
        $file_path = $book_row['FILE'];

        // Insert into download_logs table using NOW() for the current time
        $log_sql = "INSERT INTO download_logs (reg_no, download_time, dept, year, book_title) 
                    VALUES ('$reg_no', NOW(), '$dept', '$year', '$book_title')";
        $db->query($log_sql);

        // Force file download
        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit();
        } else {
            echo "File not found!";
        }
    } else {
        echo "Book details not found!";
    }
} else {
    echo "Invalid book ID.";
}
?>
