<?php

require_once "../config/db.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid blog ID.");
}

$id = (int) $_GET["id"];


/* =========================
   Fetch Blog
========================= */

$sql = "SELECT * FROM blogs WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Blog not found.");
}

$blog = $result->fetch_assoc();


/* =========================
   Update Blog
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $category = trim($_POST["category"]);
    $short_description = trim($_POST["short_description"]);
    $content = trim($_POST["content"]);

    $old_image = $blog["image"];
    $old_pdf = $blog["pdf"];

    $new_image = $old_image;
    $new_pdf = $old_pdf;


    /* =========================
       Image Upload
    ========================= */

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] == 0 &&
        $_FILES["image"]["name"] != ""
    ) {

        $image_name = $_FILES["image"]["name"];
        $image_tmp = $_FILES["image"]["tmp_name"];

        $image_extension = strtolower(
            pathinfo($image_name, PATHINFO_EXTENSION)
        );

        $allowed_images = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($image_extension, $allowed_images)) {

            die("Invalid image format. Please use JPG, JPEG, PNG or WEBP.");

        }

        $new_image = time() . "_" . uniqid() . "." . $image_extension;

        $upload_path = "../uploads/" . $new_image;

        if (!move_uploaded_file($image_tmp, $upload_path)) {

            die("Image upload failed.");

        }


        /* Delete old image */

        if (
            !empty($old_image) &&
            file_exists("../uploads/" . $old_image)
        ) {

            unlink("../uploads/" . $old_image);

        }

    }


    /* =========================
       PDF Upload
    ========================= */

    if (
        isset($_FILES["pdf"]) &&
        $_FILES["pdf"]["error"] == 0 &&
        $_FILES["pdf"]["name"] != ""
    ) {

        $pdf_name = $_FILES["pdf"]["name"];
        $pdf_tmp = $_FILES["pdf"]["tmp_name"];

        $pdf_extension = strtolower(
            pathinfo($pdf_name, PATHINFO_EXTENSION)
        );

        if ($pdf_extension != "pdf") {

            die("Invalid PDF file. Please upload a PDF file.");

        }

        $new_pdf = time() . "_" . uniqid() . ".pdf";

        $pdf_path = "../uploads/" . $new_pdf;

        if (!move_uploaded_file($pdf_tmp, $pdf_path)) {

            die("PDF upload failed.");

        }


        /* Delete old PDF */

        if (
            !empty($old_pdf) &&
            file_exists("../uploads/" . $old_pdf)
        ) {

            unlink("../uploads/" . $old_pdf);

        }

    }


    /* =========================
       Update Database
    ========================= */

    $update_sql = "
        UPDATE blogs
        SET
            title = ?,
            category = ?,
            short_description = ?,
            content = ?,
            image = ?,
            pdf = ?,
            updated_at = NOW()
        WHERE id = ?
    ";

    $update_stmt = $conn->prepare($update_sql);

    $update_stmt->bind_param(
        "ssssssi",
        $title,
        $category,
        $short_description,
        $content,
        $new_image,
        $new_pdf,
        $id
    );


    if ($update_stmt->execute()) {

        header("Location: manage_blogs.php");
        exit;

    } else {

        echo "Error updating blog: " . $conn->error;

    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Blog</title>


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
        }

        .box {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        textarea {
            min-height: 180px;
            resize: vertical;
        }

        input[type="file"] {
            margin-bottom: 15px;
        }

        .current-file {
            background: #f1f1f1;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .current-image {
            max-width: 250px;
            max-height: 180px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
            margin-bottom: 10px;
        }

        .button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 5px;
            background: #222;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .button:hover {
            background: #444;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #222;
            font-weight: bold;
        }

        @media (max-width: 600px) {

            .container {
                width: 95%;
                margin: 25px auto;
            }

            .box {
                padding: 20px;
            }

        }

    </style>

</head>


<body>


<div class="container">

    <a href="manage_blogs.php" class="back">
        ← Back to Manage Blogs
    </a>


    <div class="box">

        <h1>Edit Blog</h1>


        <form
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- Title -->

            <label>Blog Title</label>

            <input
                type="text"
                name="title"
                value="<?php echo htmlspecialchars($blog["title"]); ?>"
                required
            >


            <!-- Category -->

            <label>Category</label>

            <select name="category" required>

                <option
                    value="Study Abroad Guides"
                    <?php echo ($blog["category"] == "Study Abroad Guides") ? "selected" : ""; ?>
                >
                    Study Abroad Guides
                </option>

                <option
                    value="Country Insights"
                    <?php echo ($blog["category"] == "Country Insights") ? "selected" : ""; ?>
                >
                    Country Insights
                </option>

                <option
                    value="ROI & Career"
                    <?php echo ($blog["category"] == "ROI & Career") ? "selected" : ""; ?>
                >
                    ROI & Career
                </option>

                <option
                    value="Student Tips"
                    <?php echo ($blog["category"] == "Student Tips") ? "selected" : ""; ?>
                >
                    Student Tips
                </option>

                <option
                    value="PPP Stories"
                    <?php echo ($blog["category"] == "PPP Stories") ? "selected" : ""; ?>
                >
                    PPP Stories
                </option>

            </select>


            <!-- Short Description -->

            <label>Short Description</label>

            <textarea
                name="short_description"
                required
            ><?php echo htmlspecialchars($blog["short_description"]); ?></textarea>


            <!-- Content -->

            <label>Blog Content</label>

            <textarea
                name="content"
                required
            ><?php echo htmlspecialchars($blog["content"]); ?></textarea>


            <!-- Current Image -->

            <label>Current Image</label>

            <?php if (!empty($blog["image"])): ?>

                <div class="current-file">

                    <img
                        src="../uploads/<?php echo htmlspecialchars($blog["image"]); ?>"
                        class="current-image"
                        alt="Current Blog Image"
                    >

                    Current Image:
                    <?php echo htmlspecialchars($blog["image"]); ?>

                </div>

            <?php else: ?>

                <div class="current-file">
                    No image uploaded.
                </div>

            <?php endif; ?>


            <!-- Replace Image -->

            <label>Replace Image (Optional)</label>

            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp"
            >


            <!-- Current PDF -->

            <label>Current PDF</label>

            <?php if (!empty($blog["pdf"])): ?>

                <div class="current-file">

                    Current PDF:
                    <?php echo htmlspecialchars($blog["pdf"]); ?>

                    <br><br>

                    <a
                        href="../uploads/<?php echo htmlspecialchars($blog["pdf"]); ?>"
                        target="_blank"
                    >
                        View Current PDF
                    </a>

                </div>

            <?php else: ?>

                <div class="current-file">
                    No PDF uploaded.
                </div>

            <?php endif; ?>


            <!-- Replace PDF -->

            <label>Replace PDF (Optional)</label>

            <input
                type="file"
                name="pdf"
                accept=".pdf"
            >


            <button
                type="submit"
                class="button"
            >
                Update Blog
            </button>


        </form>

    </div>

</div>


</body>

</html>