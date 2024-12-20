<?php
    include "database.php";
    session_start();
    function countRecord($sql,$db){
        $res=$db->query($sql);
        return $res->num_rows;
    }

    if(!isset($_SESSION['ADMIN_ID'])){
        header("location:alogin.php");
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
            <h3 id="heading">Welcome Admin!</h3>
            <div id="center">
            <ul class="record">
    <li>Total Students: <span><?php echo countRecord("select * from student", $db); ?></span></li>
    <li>Total Staff: <span><?php echo countRecord("select * from staff", $db); ?></span></li>
    <li>Total Books: <span><?php echo countRecord("select * from book", $db); ?></span></li>
    <li>Total Requests: <span><?php echo countRecord("select * from request", $db); ?></span></li>
    <li>Total Comments: <span><?php echo countRecord("select * from comment", $db); ?></span></li>
    <li>Total Pending Access Requests: 
    <span>
        <?php
        $pending_requests_query = "SELECT COUNT(*) AS pending_count FROM access_requests WHERE status = 'Pending'";
        $pending_requests_result = mysqli_query($db, $pending_requests_query);
        $pending_requests_count = mysqli_fetch_assoc($pending_requests_result)['pending_count'];
        echo $pending_requests_count;
        ?>
    </span>
</li>
</ul>

<!-- <p>Here You can see the total number of students , no of book are in the E-Library, You also can see the students request and review about the books. From the view student you can get the student details like there name , mailid and Department.</p> -->
            </div>
        </div>
        </div>
    <!-- Footer -->
    <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
    <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>