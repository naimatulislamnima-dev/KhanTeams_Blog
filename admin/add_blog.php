<?php

require_once "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST["title"];
    $category = $_POST["category"];
    $short_description = $_POST["short_description"];
    $content = $_POST["content"];

    // =========================
    // IMAGE UPLOAD
    // =========================

    $image_name = "";

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

        $allowed_image_types = ["jpg", "jpeg", "png", "webp"];

        $image_extension = strtolower(
            pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
        );

        if (!in_array($image_extension, $allowed_image_types)) {

            $message = "Only JPG, JPEG, PNG and WEBP images are allowed.";

        } else {

            $image_name = time() . "_" . basename($_FILES["image"]["name"]);

            $image_path = "../uploads/" . $image_name;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {

                $message = "Image upload failed.";
            }
        }

    } else {

        $message = "Please select an image.";
    }


    // =========================
    // PDF UPLOAD
    // =========================

    $pdf_name = "";

    if ($message == "") {

        if (isset($_FILES["pdf"]) && $_FILES["pdf"]["error"] == 0) {

            $pdf_extension = strtolower(
                pathinfo($_FILES["pdf"]["name"], PATHINFO_EXTENSION)
            );

            if ($pdf_extension != "pdf") {

                $message = "Only PDF files are allowed.";

            } else {

                $pdf_name = time() . "_" . basename($_FILES["pdf"]["name"]);

                $pdf_path = "../uploads/" . $pdf_name;

                if (!move_uploaded_file($_FILES["pdf"]["tmp_name"], $pdf_path)) {

                    $message = "PDF upload failed.";
                }
            }

        } else {

            $message = "Please select a PDF file.";
        }
    }


    // =========================
    // INSERT INTO DATABASE
    // =========================

    if ($message == "") {

        $sql = "INSERT INTO blogs
                (title, category, short_description, content, image, pdf, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssss",
            $title,
            $category,
            $short_description,
            $content,
            $image_name,
            $pdf_name
        );

        if ($stmt->execute()) {

            $message = "Blog added successfully!";

        } else {

            $message = "Error: " . $conn->error;
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Blog</title>

</head>

<body>

    <h1>Add New Blog</h1>


    <?php if ($message != ""): ?>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST" enctype="multipart/form-data">

        <label>Blog Title</label><br>

        <input
            type="text"
            name="title"
            required
        >

        <br><br>


        <label>Category</label><br>

        <select name="category" required>

            <option value="">Select Category</option>

            <option value="Study Abroad Guides">
                Study Abroad Guides
            </option>

            <option value="Country Insights">
                Country Insights
            </option>

            <option value="ROI & Career">
                ROI & Career
            </option>

            <option value="Student Tips">
                Student Tips
            </option>

            <option value="PPP Stories">
                PPP Stories
            </option>

        </select>

        <br><br>


        <label>Short Description</label><br>

        <textarea
            name="short_description"
            rows="5"
            required
        ></textarea>

        <br><br>


        <label>Blog Content</label><br>

        <textarea
            name="content"
            rows="12"
            required
        ></textarea>

        <br><br>


        <label>Blog Image</label><br>

        <input
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png,.webp"
            required
        >

        <br><br>


        <label>Blog PDF</label><br>

        <input
            type="file"
            name="pdf"
            accept=".pdf"
            required
        >

        <br><br>


        <button type="submit">
            Add Blog
        </button>

    </form>

</body>

</html>