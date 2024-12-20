<?php
    include "database.php";
    session_start();
    

    if(!isset($_SESSION['STUDENT_ID'])){
        header("location:ulogin.php");
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
                        <?php include "userSideBar.php"; ?>
                    </div>
                </nav>
            </div>

            <div class="col-12 col-lg-9" id="wrapper">
            <h3 id="heading">Search Book</h3>
            <div class="center">
                
                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                   <label>Select Department</label>
                   <select name="department">
                       <option value="">All Departments</option>
                       <?php
                           // Fetch all departments from the `book` table
                           $dept_sql = "SELECT DISTINCT BOOK_DEPT FROM book";
                           $dept_res = $db->query($dept_sql);
                           while ($dept_row = $dept_res->fetch_assoc()) {
                               echo "<option value='{$dept_row['BOOK_DEPT']}'>{$dept_row['BOOK_DEPT']}</option>";
                           }
                       ?>
                   </select>
                   <label>Select Semester</label>
                   <select name="semester">
                       <option value="">All Semesters</option>
                       <?php
                           // Fetch all unique semesters from the `book` table
                           $sem_sql = "SELECT DISTINCT BOOK_SEM FROM book";
                           $sem_res = $db->query($sem_sql);
                           while ($sem_row = $sem_res->fetch_assoc()) {
                               echo "<option value='{$sem_row['BOOK_SEM']}'>{$sem_row['BOOK_SEM']}</option>";
                           }
                       ?>
                   </select>
                   <label>Select Year</label>
                   <select name="year">
                       <option value="">All Years</option>
                       <?php
                           // Fetch all unique years from the `book` table
                           $year_sql = "SELECT DISTINCT BOOK_YEAR FROM book";
                           $year_res = $db->query($year_sql);
                           while ($year_row = $year_res->fetch_assoc()) {
                               echo "<option value='{$year_row['BOOK_YEAR']}'>{$year_row['BOOK_YEAR']}</option>";
                           }
                       ?>
                   </select>
                    <button name="submit" type="submit">Search</button>
                </form>
            </div>
            <?php
            if(isset($_POST['submit'])){
                $department = $db->real_escape_string($_POST["department"]);
                $semester = $db->real_escape_string($_POST["semester"]);
                $year = $db->real_escape_string($_POST["year"]);

                // Base query
                $sql = "SELECT * FROM book WHERE 1=1";

                // Add filters dynamically 
                if ($department != '') {
                    $sql .= " AND BOOK_DEPT = '$department'";
                }
                if ($semester != '') {
                    $sql .= " AND BOOK_SEM = '$semester'";
                }
                if ($year != '') {
                    $sql .= " AND BOOK_YEAR = '$year'";
                }

                $res = $db->query($sql);
                if($res->num_rows>0){
                    echo "<table>
                        <tr>
                            <th>SNo</th>
                            <th>Book Name</th>
                            <th>Uploader Name</th>
                            <th>View</th>
                            <th>Download</th>
                            <th>Comment</th>
                        </tr>";
                    $i=0;
                    while($row=$res->fetch_assoc()){
                        $i++;
                        echo "<tr>";
                        echo "<td>{$i}</td>";
                        echo "<td>{$row['BOOK_TITLE']}</td>";
                        echo "<td>{$row['UPLOADER_NAME']}</td>";
                        echo "<td><a href='viewBook.php?id={$row["BOOK_ID"]}' target='_blank'>View</a></td>";
                        echo "<td><a href='logDownload.php?book_id={$row["BOOK_ID"]}' target='_blank'>Download</a></td>";
                        echo "<td><a href='comment.php?id={$row["BOOK_ID"]}'>GO</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                else{
                    echo "<p class='error'> No Books Records Found</p>";
                }
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
