<?php
    include "database.php";
    session_start();
    

    if(!isset($_SESSION['DEPT_ID'])){
        header("location:dlogin.php");
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
            <h3 id="heading">View Comments</h3>
            <?php
                $sql = "SELECT book.BOOK_TITLE,student.STUDENT_NAME,comment.COMM,comment.LOGS from COMMENT inner join book on book.BOOK_ID=comment.BOOK_ID INNER JOIN student on comment.SID=student.STUDENT_ID";
                $res = $db->query($sql);
                if($res->num_rows>0){
                    echo "<table>
                        <tr>
                            <th>SNo</th>
                            <th>Book</th>
                            <th>Name</th>
                            <th>Comment</th>
                            <th>Logs</th>
                        </tr>";
                    $i=0;
                    while($row=$res->fetch_assoc()){
                        $i++;
                        echo "<tr>";
                        echo "<td>{$i}</td>";
                        echo "<td>{$row['BOOK_TITLE']}</td>";
                        echo "<td>{$row['STUDENT_NAME']}</td>";
                        echo "<td>{$row['COMM']}</td>";
                        echo "<td>{$row['LOGS']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                else{
                    echo "<p class='error'> No Comments Records Found</p>";
                }
            ?>
        </div>

        <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
        <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>