<?php
    include "database.php";
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
                        <?php include "sideBar.php"; ?>
                    </div>
                </nav>
            </div>

            <div class="col-12 col-lg-9" id="wrapper">
            <h3 id="heading">New user registration</h3>
            <div class="center">
                <?php
                    if(isset($_POST['submit'])){
                        $sql="insert into student(STUDENT_NAME,STUDENT_PASS,STUDENT_MAIL,STUDENT_DEPT) values('{$_POST["name"]}','{$_POST["pass"]}','{$_POST["mail"]}','{$_POST["dept"]}')";
                        $db->query($sql);
                        echo "<p class='success'>User Registration Done</p>";
                    }
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" >
                    <label>Name</label>
                    <input type="text" name="name" required>
                    <label>Email ID</label>
                    <input type="email" name="mail" required>
                    <label>Password</label>
                    <input type="password" name="pass" required>
                    <label>Department</label>
                    <select name="dept" required>
                        <option value="">Select</option>
                        <option value="AIDS">Artificial Intelligence and Data Science</option>
                        <option value="IT">Information Technology</option>
                    </select>
                    <button name="submit" type="submit">
                        <i>📝</i>
                        <span>Register Now!</span>
                    </button>
                </form>
            </div>
        </div>
        <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
        <p>Copy &copy; Design and Developed by Department ofArtificial Intelligence And Data Science 2024</p>
        </footer>
    </div>
</body>
</html>