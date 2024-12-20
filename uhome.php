<?php
include "database.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['STUDENT_MAIL'];
    $password = $_POST['STUDENT_PASSWORD'];

    $sql = "SELECT * FROM student WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['STUDENT_MAIL'] = $row['email'];
        $_SESSION['STUDENT_NAME'] = $row['name']; // Set the STUDENT_NAME
        header("Location: uhome.php");
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
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
            <div class="col-12 col-lg-9" id="wrapper" style="line-height: 1.9;">
                <h3 id="heading">Welcome <?php echo $_SESSION["STUDENT_NAME"]; ?>!</h3>
                <p>
                    Welcome to the E-Library Management System! Here are the functionalities you can explore:
                </p>
                <ul>
                    <li>
                        <strong>Search Book:</strong> Use this feature to search for books available in the library. You can search by title, author, or subject to find the resources you need quickly.
                    </li>
                    <li>
                        <strong>Comment:</strong> Share your thoughts or feedback about a book or the library services. This helps improve the library's offerings and provides valuable input for others.
                    </li>
                    <li>
                        <strong>Request:</strong> If you can't find a book you need, you can place a request for it. The library will review and consider acquiring the requested resources.
                    </li>
                    <li>
                        <strong>Change Password:</strong> Update your account password regularly to ensure the security of your profile and prevent unauthorized access.
                    </li>
                </ul>
                <p>
                    Navigate through the menu to access these features and make the most of your library experience. Happy learning!
                </p>
            </div>
        </div>
        
        <footer id="footer" class="text-center mt-4 p-2 shadow rounded">
        <p>Copy &copy; Design and Developed by Department of Artificial Intelligence And Data Science 2024</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
