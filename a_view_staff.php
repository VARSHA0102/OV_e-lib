<?php
include "database.php";
session_start();

if (!isset($_SESSION['ADMIN_ID'])) {
    header("location:alogin.php");
    exit();
}

// Add new staff
if (isset($_POST['add_staff']) && !isset($_GET['edit_id'])) {
    $name = $_POST['staff_name'];
    $mail_id = $_POST['staff_mail_id'];
    $phone_number = $_POST['staff_phone_number'];
    $working_department = $_POST['staff_working_department'];

    $query = "INSERT INTO staff (staff_name, staff_mail_id, staff_phone_number, staff_working_department) 
              VALUES ('$name', '$mail_id', '$phone_number', '$working_department')";
    if (!mysqli_query($db, $query)) {
        die("Database Error: " . mysqli_error($db));
    }
    header("location:a_view_staff.php");
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
    if (!mysqli_query($db, $query)) {
        die("Database Error: " . mysqli_error($db));
    }
    header("location:a_view_staff.php");
}

// Delete staff
if (isset($_GET['delete_id'])) {
    $staff_id = $_GET['delete_id'];
    $query = "DELETE FROM staff WHERE staff_id = $staff_id";
    if (!mysqli_query($db, $query)) {
        die("Database Error: " . mysqli_error($db));
    }
    header("location:a_view_staff.php");
}

// Fetch staff details
$query = "SELECT * FROM staff";
$result = mysqli_query($db, $query);

// Preload data for edit
$edit_staff = null;
if (isset($_GET['edit_id'])) {
    $staff_id = $_GET['edit_id'];
    $query = "SELECT * FROM staff WHERE staff_id = $staff_id";
    $result_edit = mysqli_query($db, $query);
    $edit_staff = mysqli_fetch_assoc($result_edit);
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
            <div><img src="AIHT.png" alt="Left Image" class="img-fluid" style="max-height: 100px;"></div>
            <div class="text-center flex-grow-1 mx-3">
                <h1 class="mb-1">Anand Institute Of Higher Technology</h1>
                <h3 class="mt-0">E-Library</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-3 mb-3">
            <nav class="d-lg-block">
                <button class="navbar-toggler d-lg-none w-100 mb-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false"
                        aria-label="Toggle navigation">☰ Menu</button>
                <div class="collapse d-lg-block" id="navMenu">
                    <?php include "adminSideBar.php"; ?>
                </div>
            </nav>
        </div>

        <div class="col-12 col-lg-9" id="wrapper">
            <h3 id="heading">Staff Details</h3>

            <!-- Form to add or edit staff -->
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="staff_name" class="form-control" placeholder="Name" 
                           value="<?= $edit_staff['staff_name'] ?? '' ?>" required>
                </div>
                <div class="col-md-4">
                    <input type="email" name="staff_mail_id" class="form-control" placeholder="Mail ID" 
                           value="<?= $edit_staff['staff_mail_id'] ?? '' ?>" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="staff_phone_number" class="form-control" placeholder="Phone Number" 
                           value="<?= $edit_staff['staff_phone_number'] ?? '' ?>" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="staff_working_department" class="form-control" placeholder="Working Department" 
                           value="<?= $edit_staff['staff_working_department'] ?? '' ?>" required>
                </div>
                <div class="col-12">
                    <?php if ($edit_staff): ?>
                        <button type="submit" name="edit_staff" class="btn btn-primary action-button">Update Staff</button>
                    <?php else: ?>
                        <button type="submit" name="add_staff" class="btn btn-primary action-button">Add Staff</button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Staff List -->
            <table class="table table-bordered mt-3">
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
                    echo "<td><a href='a_view_staff.php?edit_id={$row['staff_id']}' class='btn btn-warning btn-sm action-button'>Edit</a></td>";
                    echo "<td><a href='a_view_staff.php?delete_id={$row['staff_id']}' class='btn btn-danger btn-sm action-button' onclick='return confirm(\"Are you sure you want to delete this staff member?\");'>Delete</a></td>";
                    echo "</tr>";
                    $serial++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
        <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
