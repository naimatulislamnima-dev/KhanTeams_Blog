<?php

require_once "config/db.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid blog ID.");
}

$id = (int) $_GET["id"];

$sql = "SELECT * FROM blogs WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Blog not found.");
}

$blog = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($blog["title"]); ?>
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
        }

        .hero {
            background: #222;
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        .hero h1 {
            margin: 0;
            font-size: 38px;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 50px auto;
        }

        .blog-details {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        }

        .blog-image {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .category {
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
        }

        .blog-details h2 {
            font-size: 34px;
            line-height: 1.3;
            margin: 10px 0 15px;
        }

        .date {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .short-description {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.7;
            color: #444;
            margin-bottom: 25px;
        }

        .content {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
            white-space: pre-line;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 35px;
        }

        .back-button,
        .pdf-button {
            display: inline-block;
            padding: 11px 18px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }

        .back-button {
            background: #222;
            color: white;
        }

        .back-button:hover {
            background: #444;
        }

        .pdf-button {
            background: #007bff;
            color: white;
        }

        .pdf-button:hover {
            background: #0056b3;
        }

        @media (max-width: 600px) {

            .container {
                width: 95%;
                margin: 30px auto;
            }

            .blog-details {
                padding: 20px;
            }

            .hero h1 {
                font-size: 30px;
            }

            .blog-details h2 {
                font-size: 27px;
            }

            .short-description {
                font-size: 16px;
            }

        }

    </style>

</head>

<body>


    <section class="hero">

        <h1>Blog Details</h1>

    </section>


    <div class="container">

        <div class="blog-details">


            <?php if (!empty($blog["image"])): ?>

                <img
                    src="uploads/<?php echo htmlspecialchars($blog["image"]); ?>"
                    alt="<?php echo htmlspecialchars($blog["title"]); ?>"
                    class="blog-image"
                >

            <?php endif; ?>


            <span class="category">

                <?php echo htmlspecialchars($blog["category"]); ?>

            </span>


            <h2>

                <?php echo htmlspecialchars($blog["title"]); ?>

            </h2>


            <div class="date">

                Published:
                <?php echo htmlspecialchars($blog["created_at"]); ?>

            </div>


            <div class="short-description">

                <?php echo htmlspecialchars($blog["short_description"]); ?>

            </div>


            <div class="content">

                <?php echo htmlspecialchars($blog["content"]); ?>

            </div>


            <div class="actions">


                <a
                    href="blogs.php"
                    class="back-button"
                >
                    ← Back to Blogs
                </a>


                <?php if (!empty($blog["pdf"])): ?>

                    <a
                        href="uploads/<?php echo htmlspecialchars($blog["pdf"]); ?>"
                        target="_blank"
                        class="pdf-button"
                    >
                        View PDF →
                    </a>

                <?php endif; ?>


            </div>


        </div>

    </div>


</body>

</html>