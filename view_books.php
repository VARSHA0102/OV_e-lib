<?php
    include "database.php";
    session_start();
    

    if(!isset($_SESSION['ADMIN_ID'])){
        header("location:alogin.php");
    }

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $delete_query = "DELETE FROM book WHERE BOOK_ID = ?";
        $stmt = $db->prepare($delete_query);
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            echo "<script>alert('Book deleted successfully');</script>";
        } else {
            echo "<script>alert('Error deleting book');</script>";
        }
    }

    if (isset($_POST['update_submit'])) {
        $update_id = $_POST['update_id'];
        $dept = $_POST['update_dept'];
        $uploader = $_POST['update_uploader'];
        $title = $_POST['update_title'];
        $keywords = $_POST['update_keywords'];
        $update_query = "UPDATE book SET BOOK_DEPT = ?, UPLOADER_NAME = ?, BOOK_TITLE = ?, KEYWORDS = ? WHERE BOOK_ID = ?";
        $stmt = $db->prepare($update_query);
        $stmt->bind_param("ssssi", $dept, $uploader, $title, $keywords, $update_id);
        if ($stmt->execute()) {
            echo "<script>alert('Book updated successfully');</script>";
        } else {
            echo "<script>alert('Error updating book');</script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-4">
    <div id="header" class="text-center mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <img src="AIHT.png" alt="Left Image" class="img-fluid" style="max-height: 100px;">
                </div>
                <div class="text-center flex-grow-1 mx-3">
                    <h1 class="mb-1">Anand Institute Of Higher Technology</h1>
                    <h3 class="mt-0">E-Library</h3>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-12 col-lg-3 mb-3">
                <nav class="d-lg-block">
                    <button
                        class="navbar-toggler d-lg-none w-100 mb-2"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navMenu"
                        aria-controls="navMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        ☰ Menu
                    </button>
                    <div class="collapse d-lg-block" id="navMenu">
                        <?php include "adminSideBar.php"; ?>
                    </div>
                </nav>
            </div>
            <div class="col-12 col-lg-9" id="wrapper">
            <h3 id="heading">View Book Details</h3>
            <?php
                $sql = "SELECT * FROM book";
                $res = $db->query($sql);
                if($res->num_rows>0){
                    echo "<table>
                        <tr>
                            <th>SNo</th>
                            <th>DEPT</th>
                            <th>Uploader Name</th>
                            <th>Book Name</th>
                            <th>Keywords</th>
                            <th>View</th>
                            <th>Actions</th>
                        </tr>";
                    $i=0;
                    while($row=$res->fetch_assoc()){
                        $i++;
                        echo "<tr>";
                        echo "<td>{$i}</td>";
                        echo "<td>{$row['BOOK_DEPT']}</td>";
                        echo  "<td>{$row['UPLOADER_NAME']}</td>";
                        echo "<td>{$row['BOOK_TITLE']}</td>";
                        echo "<td>{$row['KEYWORDS']}</td>";
                        echo "<td><a href='{$row["FILE"]}' target='_blank'>View</a></td>";
                        echo "<td>
                                    <a href='?edit_id={$row['BOOK_ID']}' class='btn btn-sm btn-warning'>Edit</a>
                                    <a href='?delete_id={$row['BOOK_ID']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure to delete?');\">Delete</a>
                                </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                else{
                    echo "<p class='error'> No Books Records Found</p>";
                }
        
                if (isset($_GET['edit_id'])) {
                    $edit_id = $_GET['edit_id'];
                    $edit_query = "SELECT * FROM book WHERE BOOK_ID = ?";
                    $stmt = $db->prepare($edit_query);
                    $stmt->bind_param("i", $edit_id);
                    $stmt->execute();
                    $edit_result = $stmt->get_result();
                    $edit_data = $edit_result->fetch_assoc();
                ?>
                    <h3>Edit Book</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="update_id" value="<?php echo $edit_id; ?>">
                        <label>Department</label>
                        <input type="text" name="update_dept" class="form-control mb-3" value="<?php echo $edit_data['BOOK_DEPT']; ?>" required>
                        <label>Uploader Name</label>
                        <input type="text" name="update_uploader" class="form-control mb-3" value="<?php echo $edit_data['UPLOADER_NAME']; ?>" required>
                        <label>Book Title</label>
                        <input type="text" name="update_title" class="form-control mb-3" value="<?php echo $edit_data['BOOK_TITLE']; ?>" required>
                        <label>Keywords</label>
                        <input type="text" name="update_keywords" class="form-control mb-3" value="<?php echo $edit_data['KEYWORDS']; ?>" required>
                        <button type="submit" name="update_submit" class="btn btn-success">Update</button>
                    </form>
                <?php
                }
                ?>
?>
        </div>
        <!-- Footer -->
    <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
    <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
        </footer>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>