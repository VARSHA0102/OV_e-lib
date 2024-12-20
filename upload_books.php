<?php
    include "database.php";
    session_start();
    

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
            <h3 id="heading">Upload Books</h3>
            <div class="center">
                <?php
                    if(isset($_POST['submit'])){
                        $target_dir="upload/";
                        $target_file = $target_dir.basename($_FILES["efile"] ['name']);
                        if(move_uploaded_file($_FILES["efile"] ['tmp_name'],$target_file)){
                            $sql="insert into book(BOOK_TITLE,KEYWORDS,FILE) values('{$_POST["bname"]}','{$_POST["keys"]}','{$target_file}')";
                            $db->query($sql);
                            echo "<p class='success'>Book Uploaded Successfully";
                        }
                        else{
                            echo"<p class='error'>Error IN Uploaded</p>";
                        }
                    }
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
                    <label>Book Title</label>
                    <input type="text" name="bname" required>
                    <label>Keywords</label>
                    <textarea name="keys" required></textarea>
                    <label>Upload File</label>
                    <input type="file" name="efile" required>
                    <button name="submit" type="submit">Update Book</button>
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