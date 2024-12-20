<?php
include "database.php";
session_start();

if (!isset($_SESSION['DEPT_ID'])) {
    header("location:dlogin.php");
    exit();
}

// Handle request to admin
if (isset($_POST['request_access'])) {
    $page_name = "d_staff_details.php";
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

// Check if access is granted
$access_granted = false;
$remaining_time = 0;
$check_query = "SELECT *, TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(approved_at, INTERVAL 1 MINUTE)) AS remaining_seconds 
                FROM access_requests 
                WHERE user_id = '{$_SESSION['DEPT_ID']}' 
                  AND page_name = 'd_staff_details.php' 
                  AND status = 'Approved' 
                  AND NOW() <= DATE_ADD(approved_at, INTERVAL 1 MINUTE)";
$result = mysqli_query($db, $check_query);
if ($row = mysqli_fetch_assoc($result)) {
    $access_granted = true;
    $remaining_time = $row['remaining_seconds'];
}

// Add new staff
if (isset($_POST['add_staff']) && $access_granted && !isset($_GET['edit_id'])) {
    $name = $_POST['staff_name'];
    $mail_id = $_POST['staff_mail_id'];
    $phone_number = $_POST['staff_phone_number'];
    $working_department = $_POST['staff_working_department'];

    $query = "INSERT INTO staff (staff_name, staff_mail_id, staff_phone_number, staff_working_department) 
              VALUES ('$name', '$mail_id', '$phone_number', '$working_department')";
    mysqli_query($db, $query);
    header("location:d_staff_details.php");
}

// Edit staff
if (isset($_GET['edit_id']) && isset($_POST['edit_staff'])) {
    $staff_id = $_GET['edit_id'];
    $name = $_POST['staff_name'];
    $mail_id = $_POST['staff_mail_id'];
    $phone_number = $_POST['staff_phone_number'];
    $working_department = $_POST['staff_working_department'];

    $query = "UPDATE staff SET 
                staff_name = '$name', 
                staff_mail_id = '$mail_id', 
                staff_phone_number = '$phone_number', 
                staff_working_department = '$working_department' 
              WHERE staff_id = $staff_id";
    mysqli_query($db, $query);
    header("location:d_staff_details.php");
}

// Delete staff
if (isset($_GET['delete_id'])) {
    $staff_id = $_GET['delete_id'];
    $query = "DELETE FROM staff WHERE staff_id = $staff_id";
    mysqli_query($db, $query);
    header("location:d_staff_details.php");
}

// Fetch staff details
$query = "
    SELECT staff.* 
    FROM staff 
    INNER JOIN depart_login 
    ON staff.staff_working_department = depart_login.depart_name 
    WHERE depart_login.DEPT_ID = '{$_SESSION['DEPT_ID']}'
";
$result = mysqli_query($db, $query);

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

                const addButton = document.getElementById("add-staff-button");
                if (addButton) {
                    addButton.disabled = true;
                    addButton.classList.add("disabled");
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
            <div><img src="AIHT.png" alt="Left Image" class="img-fluid" style="max-height: 100px;"></div>
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
                <button class="navbar-toggler d-lg-none w-100 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false"
                        aria-label="Toggle navigation">☰ Menu</button>
                <div class="collapse d-lg-block" id="navMenu">
                    <?php include "departSideBar.php"; ?>
                </div>
            </nav>
        </div>

        <div class="col-12 col-lg-9" id="wrapper">
            <h3 id="heading">Staff Details</h3>

            <!-- Form to add staff -->
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="staff_name" class="form-control" placeholder="Name" required>
                </div>
                <div class="col-md-4">
                    <input type="email" name="staff_mail_id" class="form-control" placeholder="Mail ID" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="staff_phone_number" class="form-control" placeholder="Phone Number" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="staff_working_department" class="form-control" placeholder="Working Department" required>
                </div>
                <div class="col-12">
                    <button type="submit" name="add_staff" class="btn btn-primary action-button" id="add-staff-button" <?= $access_granted ? '' : 'disabled' ?>>Add Staff</button>
                </div>
            </form>

            <!-- Staff List -->
            <table>
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Name</th>
                    <th>Mail ID</th>
                    <th>Phone Number</th>
                    <th>Working Department</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $serial = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$serial}</td>";
                    echo "<td>{$row['staff_name']}</td>";
                    echo "<td>{$row['staff_mail_id']}</td>";
                    echo "<td>{$row['staff_phone_number']}</td>";
                    echo "<td>{$row['staff_working_department']}</td>";
                    if ($access_granted) {
                        echo "<td><a href='?edit_id={$row['staff_id']}' class='btn btn-warning btn-sm action-button'>Edit</a></td>";
                        echo "<td><a href='?delete_id={$row['staff_id']}' class='btn btn-danger btn-sm action-button'>Delete</a></td>";
                    } else {
                        echo "<td><button class='btn btn-warning btn-sm action-button' disabled>Edit</button></td>";
                        echo "<td><button class='btn btn-danger btn-sm action-button' disabled>Delete</button></td>";
                    }
                    echo "</tr>";
                    $serial++;
                }
                ?>
                </tbody>
            </table>

            <!-- Request Access Form -->
            <form method="POST" action="" id="request-access-form">
                <?php
                $pending_request_query = "SELECT * FROM access_requests 
                                        WHERE user_id = '{$_SESSION['DEPT_ID']}' 
                                            AND page_name = 'd_staff_details.php' 
                                            AND status = 'Pending'";
                $pending_request_result = mysqli_query($db, $pending_request_query);

                if (mysqli_num_rows($pending_request_result) > 0) {
                    echo "<p class='text-warning'>Waiting for admin access...</p>";
                } else {
                    echo '<button type="submit" name="request_access" class="btn btn-primary">Request Access</button>';
                }
                ?>
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
