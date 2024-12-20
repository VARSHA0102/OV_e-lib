<?php
include "database.php";
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['ADMIN_ID'])) {
    header("location:alogin.php");
    exit();
}

// Approve access
if (isset($_GET['approve_id'])) {
    $request_id = $_GET['approve_id'];
    $query = "UPDATE access_requests SET status = 'Approved', approved_at = NOW() WHERE id = $request_id";
    mysqli_query($db, $query);
    header("location:manage_access.php");
}

// Fetch pending requests
$query = "SELECT * FROM access_requests WHERE status = 'Pending'";
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
            <h3 id="heading">Access Management</h3>
        <table>
        <thead>
        <tr>
            <th>User ID</th>
            <th>Page Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thea>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['user_id'] ?></td>
                <td><?= $row['page_name'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <a href="?approve_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<footer id="footer" class="text-center mt-4 p-2 shadow rounded">
        <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>