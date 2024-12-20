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
                        <?php include "sideBar.php"; ?>
                    </div>
                </nav>
            </div>

            <div class="col-12 col-lg-9" id="wrapper">
                <p>
                    An e-library, or digital library, represents a transformative leap from traditional libraries,
                    offering vast digital resources such as e-books, academic papers, and multimedia content readily
                    accessible through the internet. Unlike conventional libraries, which require physical presence and
                    adherence to operational hours, e-libraries are available 24/7, providing users the flexibility to
                    access information from anywhere at any time. This perpetual availability is particularly beneficial
                    for students, researchers, and lifelong learners who seek to maximize their time and optimize their
                    research efforts.
                </p>
                <p>
                    The versatility of e-libraries is further enhanced by advanced search functionalities and interactive
                    tools that streamline the process of finding relevant information. Users can swiftly navigate through
                    extensive databases, filter search results, and employ various digital tools to annotate, highlight,
                    and organize their findings. Moreover, e-libraries often offer personalized features, such as
                    recommendations based on user preferences and reading history, thus enriching the overall user
                    experience and promoting continued engagement with the digital content.
                </p>
                <p>
                    One of the most significant advantages of e-libraries is their role in democratizing knowledge. By
                    eliminating geographical and physical barriers, e-libraries make information more inclusive and
                    accessible to a broader audience, including those in remote or underserved areas. This inclusivity
                    promotes educational equality and empowers individuals to pursue their intellectual interests and
                    professional development without the constraints of limited local resources. In essence, e-libraries
                    play a crucial role in bridging the digital divide and fostering a culture of continuous learning and
                    intellectual curiosity across the globe.
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
