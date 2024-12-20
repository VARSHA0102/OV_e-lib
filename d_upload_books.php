<?php
    include "database.php";
    session_start();

    if (!isset($_SESSION['DEPT_ID'])) {
        header("location:dlogin.php");
        exit();
    }

    $dept_id = $_SESSION['DEPT_ID'];
    $dept_query = "SELECT DEPART_NAME FROM depart_login WHERE DEPT_ID = ?";
    $stmt = $db->prepare($dept_query);
    $stmt->bind_param("i", $dept_id);
    $stmt->execute();
    $dept_result = $stmt->get_result();
    $department = $dept_result->fetch_assoc();
    $department_name = $department['DEPART_NAME'];

    // Fetch subjects for the department
    $subjects_query = "SELECT DISTINCT SUB_CODE, SUB_NAME, SUB_YEAR, SUB_SEM FROM subject WHERE SUB_DEPT = ?";
    $stmt = $db->prepare($subjects_query);
    $stmt->bind_param("s", $department_name);
    $stmt->execute();
    $subjects_result = $stmt->get_result();

    // Fetch unique years
    $years_query = "SELECT DISTINCT SUB_YEAR FROM subject WHERE SUB_DEPT = ?";
    $stmt = $db->prepare($years_query);
    $stmt->bind_param("s", $department_name);
    $stmt->execute();
    $years_result = $stmt->get_result();

    // Fetch unique semesters
    $semesters_query = "SELECT DISTINCT SUB_SEM FROM subject WHERE SUB_DEPT = ?";
    $stmt = $db->prepare($semesters_query);
    $stmt->bind_param("s", $department_name);
    $stmt->execute();
    $semesters_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
        function toggleFields() {
            const bookType = document.getElementById('book_type').value;
            document.getElementById('foreign_fields').style.display = bookType === 'Foreign Author Book' ? 'block' : 'none';
            document.getElementById('local_fields').style.display = bookType === 'Local Author Book' ? 'block' : 'none';
        }
    </script>
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
                <h3 id="heading">Upload Books</h3>
                <div class="center">
                    <?php
                    if (isset($_POST['submit'])) {
                        $book_type = $_POST['book_type'];
                        $uploader_name = $_POST['uploader_name'];
                        $subject_details = $_POST['subject']; 
                        list($sub_code, $sub_name) = explode('|', $subject_details);
                        $book_year = $_POST['book_year'];
                        $book_sem = $_POST['book_sem'];
                        $keywords = $_POST['keys'];
                        $target_dir = "upload/";
                        $target_file = $target_dir . basename($_FILES["efile"]["name"]);

                        if ($book_type === "Foreign Author Book") {
                            $book_name = $_POST['book_name'];
                            $author_name = $_POST['author_name'];
                            $edition = $_POST['edition'];
                            $book_code = $_POST['book_code'];

                            $sql = "INSERT INTO book (L_F_AUTHOR_BOOK, BOOK_TITLE, AUTHOR_NAME, BOOK_EDITION, BOOK_CODE, BOOK_YEAR, BOOK_SEM, KEYWORDS, FILE, UPLOADER_NAME, BOOK_DEPT) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("sssssssssss", $book_type, $book_name, $author_name, $edition, $book_code, $book_year, $book_sem, $keywords, $target_file, $uploader_name, $department_name);
                        } else {
                            $book_title = $_POST['book_title'];

                            $sql = "INSERT INTO book (L_F_AUTHOR_BOOK, BOOK_TITLE, BOOK_YEAR, BOOK_SEM, KEYWORDS, FILE, UPLOADER_NAME, BOOK_DEPT) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("ssssssss", $book_type, $book_title, $book_year, $book_sem, $keywords, $target_file, $uploader_name, $department_name);
                        }

                        if (move_uploaded_file($_FILES["efile"]["tmp_name"], $target_file)) {
                            $stmt->execute();
                            echo "<p class='success'>Book Uploaded Successfully</p>";
                        } else {
                            echo "<p class='error'>Error in Uploading</p>";
                        }
                    }
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                        <label>Book Type</label>
                        <select id="book_type" name="book_type" class="form-control mb-3" onchange="toggleFields()" required>
                            <option value="" disabled selected>Select Book Type</option>
                            <option value="Foreign Author Book">Foreign Author Book</option>
                            <option value="Local Author Book">Local Author Book</option>
                        </select>

                        <label>Subject</label>
                        <select name="subject" class="form-control mb-3" required>
                            <option value="" disabled selected>Select Subject</option>
                            <?php
                            while ($row = $subjects_result->fetch_assoc()) {
                                echo "<option value='{$row['SUB_CODE']}|{$row['SUB_NAME']}'>{$row['SUB_CODE']} - {$row['SUB_NAME']}</option>";
                            }
                            ?>
                        </select>

                        <label>Year</label>
                        <select name="book_year" class="form-control mb-3" required>
                            <option value="" disabled selected>Select Year</option>
                            <?php
                            while ($row = $years_result->fetch_assoc()) {
                                echo "<option value='{$row['SUB_YEAR']}'>{$row['SUB_YEAR']}</option>";
                            }
                            ?>
                        </select>

                        <label>Semester</label>
                        <select name="book_sem" class="form-control mb-3" required>
                            <option value="" disabled selected>Select Semester</option>
                            <?php
                            while ($row = $semesters_result->fetch_assoc()) {
                                echo "<option value='{$row['SUB_SEM']}'>{$row['SUB_SEM']}</option>";
                            }
                            ?>
                        </select>

                        <div id="foreign_fields" style="display: none;">
                            <label>Book Name</label>
                            <input type="text" name="book_name" class="form-control mb-3">

                            <label>Author Name</label>
                            <input type="text" name="author_name" class="form-control mb-3">

                            <label>Edition</label>
                            <input type="text" name="edition" class="form-control mb-3">

                            <label>Book Code</label>
                            <input type="text" name="book_code" class="form-control mb-3">
                        </div>

                        <div id="local_fields" style="display: none;">
                            <label>Book Title</label>
                            <input type="text" name="book_title" class="form-control mb-3">
                        </div>

                        <label>Keywords</label>
                        <textarea name="keys" class="form-control mb-3" required></textarea>

                        <label>Uploader Name</label>
                        <input type="text" name="uploader_name" class="form-control mb-3" required>

                        <label>Upload File</label>
                        <input type="file" name="efile" class="form-control mb-3" required>

                        <button name="submit" type="submit" class="btn btn-primary">Upload Book</button>
                    </form>
                </div>
            </div>
        </div>

        <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
            <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
