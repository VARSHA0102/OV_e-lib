<?php
include "database.php";
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['ADMIN_ID'])) {
    header("location:alogin.php");
    exit();
}

// Admin authentication can be added here

// Search functionality
$search_query = '';
$search_value = '';
if (isset($_GET['search'])) {
    $search_value = $db->real_escape_string($_GET['search']);
    $search_query = "WHERE reg_no LIKE '%$search_value%' OR book_title LIKE '%$search_value%' OR year LIKE '%$search_value%' OR dept LIKE '%$search_value%'";
}

$sql = "SELECT * FROM download_logs $search_query ORDER BY download_time DESC";
$res = $db->query($sql);
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
                <h1 id="heading">Download Logs</h1>
                
                    <form action="" method="GET" class="d-flex">
                        <input type="text" name="search" value="<?php echo $search_value; ?>" class="form-control me-2" placeholder="Search...">
                        <button type="submit" class="btn btn-outline-secondary">Search</button>
                    </form>
                <?php if ($res->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Reg No</th>
                                <th>Date & Time</th>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Book Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            while ($row = $res->fetch_assoc()) {
                                $i++;
                                echo "<tr>";
                                echo "<td>{$i}</td>";
                                echo "<td>{$row['reg_no']}</td>";
                                echo "<td>{$row['download_time']}</td>";
                                echo "<td>{$row['dept']}</td>";
                                echo "<td>{$row['year']}</td>";
                                echo "<td>{$row['book_title']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-danger text-center">No download logs found.</p>
                <?php endif; ?>
            </>
        </div>

        <!-- Footer -->
        <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
            <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
