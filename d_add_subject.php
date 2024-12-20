<?php
    include "database.php";
    session_start();
    
    if(!isset($_SESSION['DEPT_ID'])){
        header("location:dlogin.php");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input data
        $sub_sem = $db->real_escape_string($_POST['sub_sem']);
        $sub_year = $db->real_escape_string($_POST['sub_year']);
        $sub_code = $db->real_escape_string($_POST['sub_code']);
        $sub_name = $db->real_escape_string($_POST['sub_name']);
        $sub_staff = $db->real_escape_string($_POST['sub_staff']);
        $sub_dept = $_SESSION['DEPART_NAME']; // Use the logged-in user's department
        
        // Insert the new subject
        $sql = "INSERT INTO subject (SUB_DEPT, SUB_YEAR, SUB_SEM, SUB_CODE, SUB_NAME, SUB_STAFF) VALUES ('$sub_dept', '$sub_year', '$sub_sem', '$sub_code', '$sub_name', '$sub_staff')";
        if ($db->query($sql)) {
            $message = "Subject added successfully!";
        } else {
            $error_message = "Error adding subject: " . $db->error;
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
            <h3 id="heading">ADD SUBJECT...</h3>
            <div class="center">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="mb-3">
                        <label for="sub_year" class="form-label">Subject Year</label>
                        <select id="sub_year" name="sub_year" class="form-control mb-3" required>
                            <option value="" disabled selected>Select Year</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="sub_sem" class="form-label">Subject Semester</label>
                        <select id="sub_sem" name="sub_sem" class="form-control mb-3" required>
                            <option value="" disabled selected>Select Semester</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                        </select>

                    </div>

                    <div class="mb-3">
                        <label for="sub_code" class="form-label">Subject Code</label>
                        <input type="text" name="sub_code" id="sub_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="sub_name" class="form-label">Subject Name</label>
                        <input type="text" name="sub_name" id="sub_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="sub_staff" class="form-label">Subject Staff</label>
                        <input type="text" name="sub_staff" id="sub_staff" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </form>

                <?php if(isset($message)): ?>
                    <div class="alert alert-success mt-3"><?php echo $message; ?></div>
                <?php elseif(isset($error_message)): ?>
                    <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
        <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                    