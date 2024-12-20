<?php
include "database.php";
session_start();

if (!isset($_SESSION['DEPT_ID'])) {
    header("location:dlogin.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM subject WHERE SUBJECT_ID = ?";
    $stmt = $db->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Subject deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting subject');</script>";
    }
}

// Handle Edit
if (isset($_POST['edit_submit'])) {
    $edit_id = $_POST['edit_id'];
    $dept = $_POST['edit_dept'];
    $year = $_POST['edit_year'];
    $sem = $_POST['edit_sem'];
    $sub_code = $_POST['edit_sub_code'];
    $sub_name = $_POST['edit_sub_name'];
    $sub_staff = $_POST['edit_sub_staff'];

    $update_query = "UPDATE subject SET SUB_DEPT = ?, SUB_YEAR = ?, SUB_SEM = ?, SUB_CODE = ?, SUB_NAME = ?, SUB_STAFF = ? WHERE SUBJECT_ID = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("ssssssi", $dept, $year, $sem, $sub_code, $sub_name, $sub_staff, $edit_id);
    if ($stmt->execute()) {
        echo "<script>alert('Subject updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating subject');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Subject Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-4">
        <div id="header" class="text-center mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <img src="AIHT.png" alt="Logo" class="img-fluid" style="max-height: 100px;">
                </div>
                <div class="text-center flex-grow-1 mx-3">
                    <h1>Anand Institute Of Higher Technology</h1>
                    <h3>E-Library</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-3">
                <nav>
                    <button class="navbar-toggler d-lg-none w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                        ☰ Menu
                    </button>
                    <div class="collapse d-lg-block" id="navMenu">
                        <?php include "departSideBar.php"; ?>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9">
                <h3>View Subject Details</h3>
                <?php
                $sql = "SELECT * FROM subject 
                        INNER JOIN depart_login 
                        ON subject.SUB_DEPT = depart_login.DEPART_NAME 
                        WHERE depart_login.DEPT_ID = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("s", $_SESSION['DEPT_ID']);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows > 0) {
                    echo "<table>
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>DEPT</th>
                                <th>YEAR</th>
                                <th>Semester</th>
                                <th>Sub Code</th>
                                <th>Sub Name</th>
                                <th>Sub Staff</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>";
                    $i = 0;
                    while ($row = $res->fetch_assoc()) {
                        $i++;
                        echo "<tr>
                                <td>{$i}</td>
                                <td>{$row['SUB_DEPT']}</td>
                                <td>{$row['SUB_YEAR']}</td>
                                <td>{$row['SUB_SEM']}</td>
                                <td>{$row['SUB_CODE']}</td>
                                <td>{$row['SUB_NAME']}</td>
                                <td>{$row['SUB_STAFF']}</td>
                                <td>
                                    <a href='?edit_id={$row['SUBJECT_ID']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='?delete_id={$row['SUBJECT_ID']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure to delete?');\">Delete</a>
                                </td>
                            </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p class='text-danger'>No Subject Records Found</p>";
                }

                if (isset($_GET['edit_id'])) {
                    $edit_id = $_GET['edit_id'];
                    $edit_query = "SELECT * FROM subject WHERE SUBJECT_ID = ?";
                    $stmt = $db->prepare($edit_query);
                    $stmt->bind_param("i", $edit_id);
                    $stmt->execute();
                    $edit_result = $stmt->get_result();
                    $edit_data = $edit_result->fetch_assoc();
                ?>
                    <h3>Edit Subject</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">

                        <label>Department</label>
                        <input type="text" name="edit_dept" class="form-control mb-3" value="<?php echo $edit_data['SUB_DEPT']; ?>" required>

                        <label>Year</label>
                        <select name="edit_year" class="form-control mb-3" required>
                            <option value="<?php echo $edit_data['SUB_YEAR']; ?>"><?php echo $edit_data['SUB_YEAR']; ?></option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>

                        <label>Semester</label>
                        <select name="edit_sem" class="form-control mb-3" required>
                            <option value="<?php echo $edit_data['SUB_SEM']; ?>"><?php echo $edit_data['SUB_SEM']; ?></option>
                            <?php for ($j = 1; $j <= 8; $j++) {
                                echo "<option value='0$j'>0$j</option>";
                            } ?>
                        </select>

                        <label>Subject Code</label>
                        <input type="text" name="edit_sub_code" class="form-control mb-3" value="<?php echo $edit_data['SUB_CODE']; ?>" required>

                        <label>Subject Name</label>
                        <input type="text" name="edit_sub_name" class="form-control mb-3" value="<?php echo $edit_data['SUB_NAME']; ?>" required>

                        <label>Subject Staff</label>
                        <input type="text" name="edit_sub_staff" class="form-control mb-3" value="<?php echo $edit_data['SUB_STAFF']; ?>" required>

                        <button type="submit" name="edit_submit" class="btn btn-success">Update</button>
                    </form>
                <?php } ?>
            </div>
        </div>

        <footer class="text-center mt-4 p-2 shadow rounded">
            <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
