<?php
include "database.php";
session_start();

if (!isset($_SESSION['DEPT_ID'])) {
    header("location:dlogin.php");
}

// Handle Add Student
if (isset($_POST['add_submit'])) {
    $student_reg = $_POST['student_reg'];
    $student_name = $_POST['student_name'];
    $student_email = $_POST['student_email'];
    $student_dept = $_POST['student_dept'];

    $insert_query = "INSERT INTO student (STUDENT_REG_NO, STUDENT_NAME, STUDENT_MAIL, STUDENT_DEPT) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($insert_query);
    $stmt->bind_param("ssss", $student_reg, $student_name, $student_email, $student_dept);
    if ($stmt->execute()) {
        echo "<script>alert('Student added successfully');</script>";
    } else {
        echo "<script>alert('Error adding student');</script>";
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM student WHERE STUDENT_ID = ?";
    $stmt = $db->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Student details deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting student');</script>";
    }
}

// Handle Update
if (isset($_POST['edit_submit'])) {
    $edit_student_id = $_POST['edit_id'];
    $edit_student_reg = $_POST['edit_student_reg'];
    $edit_student_name = $_POST['edit_student_name'];
    $edit_student_email = $_POST['edit_student_email'];
    $edit_student_dept = $_POST['edit_student_dept'];

    $update_query = "UPDATE student SET STUDENT_REG_NO = ?, STUDENT_NAME = ?, STUDENT_MAIL = ?, STUDENT_DEPT = ? WHERE STUDENT_ID = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("ssssi", $edit_student_reg, $edit_student_name, $edit_student_email, $edit_student_dept, $edit_student_id);
    if ($stmt->execute()) {
        echo "<script>alert('Student updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating student');</script>";
    }
}

// Check if access is granted
$access_granted = false;
$remaining_time = 0;
$check_query = "SELECT *, TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(approved_at, INTERVAL 1 HOUR)) AS remaining_seconds 
                FROM access_requests 
                WHERE user_id = '{$_SESSION['DEPT_ID']}' 
                  AND page_name = 'd_student_details.php' 
                  AND status = 'Approved' 
                  AND NOW() <= DATE_ADD(approved_at, INTERVAL 1 HOUR)";
$result = mysqli_query($db, $check_query);
if ($row = mysqli_fetch_assoc($result)) {
    $access_granted = true;
    $remaining_time = $row['remaining_seconds'];
}

// Handle request to admin
if (isset($_POST['request_access'])) {
    $page_name = "d_student_details.php";
    $user_id = $_SESSION['DEPT_ID'];
    $check_request_query = "SELECT * FROM access_requests WHERE user_id = '$user_id' AND page_name = '$page_name' AND status = 'Pending'";
    $request_result = mysqli_query($db, $check_request_query);

    if (mysqli_num_rows($request_result) === 0) {
        $query = "INSERT INTO access_requests (user_id, page_name, status, requested_at) VALUES ('$user_id', '$page_name', 'Pending', NOW())";
        mysqli_query($db, $query);
        $success_message = "Your request has been sent to the admin.";
    } else {
        $success_message = "You already have a pending request.";
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let timerElement = document.getElementById("countdown-timer");
            let remainingTime = <?= $remaining_time ?>; // Time in seconds

            function disableButtons() {
                const buttons = document.querySelectorAll(".action-button");
                buttons.forEach(button => {
                    button.disabled = true;
                    button.classList.add("disabled");
                });

                const addButton = document.getElementById("add-student-button");
                if (addButton) {
                    addButton.disabled = true;
                    addButton.classList.add("disabled");
                }

                const requestButton = document.getElementById("request-access-button");
                if (requestButton) {
                    requestButton.style.display = "none";
                }
            }

            if (remainingTime > 0) {
                const interval = setInterval(() => {
                    if (remainingTime <= 0) {
                        clearInterval(interval);
                        timerElement.textContent = "Access expired.";
                        disableButtons();
                        document.getElementById("request-access-form").style.display = "block";
                        return;
                    }

                    const hours = Math.floor(remainingTime / 3600);
                    const minutes = Math.floor((remainingTime % 3600) / 60);
                    const seconds = remainingTime % 60;

                    timerElement.textContent = `${hours}h ${minutes}m ${seconds}s remaining`;
                    remainingTime--;
                }, 1000);
            } else {
                timerElement.textContent = "Access expired.";
                disableButtons();
                document.getElementById("request-access-form").style.display = "block";
            }
        });
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
            <?php if ($access_granted): ?>
                <div id="countdown-timer" class="text-danger fw-bold" style="font-size: 1.2rem;"></div>
            <?php endif; ?>
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
            <h3 id="heading">View Student Details</h3>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal" id="add-student-button" <?= $access_granted ? '' : 'disabled' ?>>Add Student</button>
            <?php
            $sql = "SELECT student.* FROM student INNER JOIN depart_login ON student.STUDENT_DEPT = depart_login.DEPART_NAME";
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
                                <th>Actions</th>
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
                            <td>{$row['STUDENT_DEPT']}</td>";
                    if ($access_granted) {
                        echo "<td>
                                <a href='?edit_id={$row['STUDENT_ID']}' class='btn btn-sm btn-warning action-button'>Edit</a>
                                <a href='?delete_id={$row['STUDENT_ID']}' class='btn btn-sm btn-danger action-button' onclick=\"return confirm('Are you sure?')\">Delete</a>
                              </td>";
                    } else {
                        echo "<td class='text-muted'>Access expired. Request access to manage.</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p class='text-danger'>No Student Records Found</p>";
            }

            if (isset($_GET['edit_id'])) {
                $edit_id = $_GET['edit_id'];
                $edit_query = "SELECT * FROM student WHERE STUDENT_ID = ?";
                $stmt = $db->prepare($edit_query);
                $stmt->bind_param("i", $edit_id);
                $stmt->execute();
                $edit_result = $stmt->get_result();
                $edit_data = $edit_result->fetch_assoc();
                ?>
                <h3>Edit Student</h3>
                <form action="" method="post">
                    <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                    <label>Reg. No</label>
                    <input type="text" name="edit_student_reg" class="form-control mb-3" value="<?php echo $edit_data['STUDENT_REG_NO']; ?>" required>
                    <label>Student Name</label>
                    <input type="text" name="edit_student_name" class="form-control mb-3" value="<?php echo $edit_data['STUDENT_NAME']; ?>" required>
                    <label>Student Email</label>
                    <input type="email" name="edit_student_email" class="form-control mb-3" value="<?php echo $edit_data['STUDENT_MAIL']; ?>" required>
                    <label>Department</label>
                    <input type="text" name="edit_student_dept" class="form-control mb-3" value="<?php echo $edit_data['STUDENT_DEPT']; ?>" required>
                    <button type="submit" name="edit_submit" class="btn btn-success">Update</button>
                </form>
                <?php
            }
            ?>

            <!-- Request Access Form -->
            <form method="POST" action="" id="request-access-form" style="display: <?= $access_granted ? 'none' : 'block' ?>">
                <?php if (!empty($success_message)): ?>
                    <p class="text-warning"><?= $success_message ?></p>
                <?php endif; ?>
                <button type="submit" name="request_access" class="btn btn-primary" id="request-access-button">Request Access</button>
            </form>
        </div>
    </div>

    <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
        <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
