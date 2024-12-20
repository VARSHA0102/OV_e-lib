<?php
    include "database.php";
    session_start();

    if (!isset($_SESSION['ADMIN_ID'])) {
        header("location:alogin.php");
        exit();
    }

    // Delete student
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $delete_query = "DELETE FROM student WHERE STUDENT_REG_NO = '$delete_id'";
        $db->query($delete_query);
        header("location:view_student.php");
        exit();
    }

    // Fetch student details for editing
    $edit_data = null;
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $edit_query = "SELECT * FROM student WHERE STUDENT_REG_NO = '$edit_id'";
        $edit_result = $db->query($edit_query);
        $edit_data = $edit_result->fetch_assoc();
    }

    // Update student details
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_student'])) {
        $reg_no = $_POST['student_reg_no'];
        $name = $_POST['student_name'];
        $email = $_POST['student_email'];
        $department = $_POST['student_department'];

        $update_query = "UPDATE student SET STUDENT_NAME = '$name', STUDENT_MAIL = '$email', STUDENT_DEPT = '$department' WHERE STUDENT_REG_NO = '$reg_no'";
        $db->query($update_query);
        header("location:view_student.php");
        exit();
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
            <h3 id="heading">View Student Details</h3>

            <?php if ($edit_data): ?>
                <!-- Edit Student Form -->
                <form action="" method="POST" class="mb-4">
                    <input type="hidden" name="student_reg_no" value="<?= $edit_data['STUDENT_REG_NO'] ?>">
                    <div class="mb-3">
                        <label for="student_name" class="form-label">Name</label>
                        <input type="text" id="student_name" name="student_name" class="form-control" value="<?= $edit_data['STUDENT_NAME'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_email" class="form-label">Email</label>
                        <input type="email" id="student_email" name="student_email" class="form-control" value="<?= $edit_data['STUDENT_MAIL'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_department" class="form-label">Department</label>
                        <input type="text" id="student_department" name="student_department" class="form-control" value="<?= $edit_data['STUDENT_DEPT'] ?>" required>
                    </div>
                    <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
                    <a href="view_student.php" class="btn btn-secondary">Cancel</a>
                </form>
            <?php endif; ?>

            <!-- Student Table -->
            <?php
            $sql = "SELECT * FROM student";
            $res = $db->query($sql);
            if ($res->num_rows > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>";
                $i = 0;
                while ($row = $res->fetch_assoc()) {
                    $i++;
                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['STUDENT_REG_NO']}</td>
                            <td>{$row['STUDENT_NAME']}</td>
                            <td>{$row['STUDENT_MAIL']}</td>
                            <td>{$row['STUDENT_DEPT']}</td>
                            <td><a href='?edit_id={$row['STUDENT_REG_NO']}' class='btn btn-warning btn-sm'>Edit</a></td>
                            <td><a href='?delete_id={$row['STUDENT_REG_NO']}' class='btn btn-danger btn-sm' onclick='return confirm('Are you sure you want to delete this student?')'>Delete</a></td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p class='text-danger'>No Student Records Found</p>";
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
