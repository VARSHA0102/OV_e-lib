<?php
    include "database.php";
    session_start();

    if (!isset($_SESSION['DEPT_ID'])) {
        header("location:dlogin.php");
    }

    // Handle deletion
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

    // Handle update
    if (isset($_POST['update_submit'])) {
        $update_id = $_POST['update_id'];
        $dept = $_POST['update_dept'];
        $uploader = $_POST['update_uploader'];
        $title = $_POST['update_title'];
        $keywords = $_POST['update_keywords'];
        $year = $_POST['update_year'];
        $sem = $_POST['update_sem'];

        $update_query = "UPDATE book SET BOOK_DEPT = ?, UPLOADER_NAME = ?, BOOK_TITLE = ?, KEYWORDS = ?, BOOK_YEAR = ?, BOOK_SEM = ? WHERE BOOK_ID = ?";
        $stmt = $db->prepare($update_query);
        $stmt->bind_param("sssssii", $dept, $uploader, $title, $keywords, $year, $sem, $update_id);
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
                        <?php include "departSideBar.php"; ?>
                    </div>
                </nav>
            </div>
            <div class="col-12 col-lg-9" id="wrapper">
                <h3 id="heading">View Book Details</h3>
                <?php
                $sql = "SELECT * FROM book INNER JOIN depart_login ON book.BOOK_DEPT = depart_login.DEPART_NAME";
                $res = $db->query($sql);
                if ($res->num_rows > 0) {
                    echo "<table>
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>DEPT</th>
                                <th>Uploader Name</th>
                                <th>Book Name</th>
                                <th>Keywords</th>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>View</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>";
                    $i = 0;
                    
                    while ($row = $res->fetch_assoc()) {
                        $i++;
                        echo "<tr>
                                <td>{$i}</td>
                                <td>{$row['BOOK_DEPT']}</td>
                                <td>{$row['UPLOADER_NAME']}</td>
                                <td>{$row['BOOK_TITLE']}</td>
                                <td>{$row['KEYWORDS']}</td>
                                <td>{$row['BOOK_YEAR']}</td>
                                <td>{$row['BOOK_SEM']}</td>
                                <td><a href='viewBook.php?id={$row["BOOK_ID"]}' target='_blank'>View</a></td>
                                <td>
                                    <a href='?edit_id={$row['BOOK_ID']}' class='btn btn-sm btn-warning'>Edit</a>
                                    <a href='?delete_id={$row['BOOK_ID']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure to delete?');\">Delete</a>
                                </td>
                            </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p class='error'>No Book Records Found</p>";
                }

                // Edit form
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
                        <label>Year</label>
                        <input type="text" name="update_year" class="form-control mb-3" value="<?php echo $edit_data['BOOK_YEAR']; ?>" required>
                        <label>Semester</label>
                        <input type="text" name="update_sem" class="form-control mb-3" value="<?php echo $edit_data['BOOK_SEM']; ?>" required>
                        <button type="submit" name="update_submit" class="btn btn-success">Update</button>
                    </form>
                <?php
                }
                ?>
            </div>
        </div>

        <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
            <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
