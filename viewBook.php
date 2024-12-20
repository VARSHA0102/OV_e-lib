<?php
include "database.php";

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Increment the view count
    $update_query = "UPDATE book SET VIEW_COUNT = VIEW_COUNT + 1 WHERE BOOK_ID = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();

    // Retrieve the file path
    $file_query = "SELECT FILE FROM book WHERE BOOK_ID = ?";
    $stmt = $db->prepare($file_query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        header("Location: " . $data['FILE']);
        exit();
    } else {
        echo "File not found!";
    }
} else {
    echo "Invalid Request!";
}
?>
