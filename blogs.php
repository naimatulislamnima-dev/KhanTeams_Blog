<?php

require_once "config/db.php";

$category = isset($_GET["category"]) ? $_GET["category"] : "All Posts";

if ($category == "All Posts") {

    $sql = "SELECT * FROM blogs ORDER BY id DESC";
    $result = $conn->query($sql);

} else {

    $sql = "SELECT * FROM blogs WHERE category = ? ORDER BY id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();

    $result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Our Blogs</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <!-- Navbar -->

    <header class="navbar">

        <div class="logo">
            <div class="logo-icon">K</div>

            <div>
                <strong>Khan & Teams</strong>
                <span>Education</span>
            </div>
        </div>

        <nav>

            <a href="#">HOME</a>
            <a href="#">ABOUT US</a>
            <a href="#">SERVICES</a>
            <a href="#">PROGRAMS</a>
            <a href="#">DESTINATIONS</a>
            <a href="#">BLOG</a>
            <a href="#">CONTACT</a>

        </nav>

    </header>


    <!-- Hero -->

    <section class="hero">

        <div class="hero-content">

            <h1>OUR BLOGS</h1>

            <p>
                INSIGHTS, TIPS, AND STORIES FOR YOUR EDUCATIONAL JOURNEY
            </p>

        </div>

    </section>


    <!-- Blog Section -->

    <section class="blog-section">

        <h2>Latest Blog Posts</h2>

        <div class="title-line"></div>

        <p class="section-description">
            Explore our collection of articles covering study abroad guides,
            country insights, career advice, and student experiences.
        </p>


        <!-- Filters -->

        <div class="filters">

            <a
                href="blogs.php?category=All Posts"
                class="filter-btn <?php echo ($category == 'All Posts') ? 'active' : ''; ?>"
            >
                All Posts
            </a>

            <a
                href="blogs.php?category=Study Abroad Guides"
                class="filter-btn <?php echo ($category == 'Study Abroad Guides') ? 'active' : ''; ?>"
            >
                Study Abroad Guides
            </a>

            <a
                href="blogs.php?category=Country Insights"
                class="filter-btn <?php echo ($category == 'Country Insights') ? 'active' : ''; ?>"
            >
                Country Insights
            </a>

            <a
                href="blogs.php?category=ROI & Career"
                class="filter-btn <?php echo ($category == 'ROI & Career') ? 'active' : ''; ?>"
            >
                ROI & Career
            </a>

            <a
                href="blogs.php?category=Student Tips"
                class="filter-btn <?php echo ($category == 'Student Tips') ? 'active' : ''; ?>"
            >
                Student Tips
            </a>

            <a
                href="blogs.php?category=PPP Stories"
                class="filter-btn <?php echo ($category == 'PPP Stories') ? 'active' : ''; ?>"
            >
                PPP Stories
            </a>

        </div>


        <!-- Blog Cards -->

        <?php if ($result->num_rows > 0): ?>

            <div class="blog-grid">

                <?php while ($row = $result->fetch_assoc()): ?>

                    <div class="blog-card">

                        <div class="card-image">

                            <?php if (!empty($row["image"])): ?>

                                <img
                                    src="uploads/<?php echo htmlspecialchars($row["image"]); ?>"
                                    alt="<?php echo htmlspecialchars($row["title"]); ?>"
                                >

                            <?php else: ?>

                                <div class="default-image">
                                    <div class="default-logo">K</div>
                                    <h3>Khan & Teams</h3>
                                    <h3>Education</h3>
                                </div>

                            <?php endif; ?>


                            <span class="category">
                                <?php echo htmlspecialchars($row["category"]); ?>
                            </span>

                        </div>


                        <div class="card-content">

                            <div class="date">
                                📅
                                <?php echo date("F d, Y", strtotime($row["created_at"])); ?>
                            </div>


                            <h3>
                                <?php echo htmlspecialchars($row["title"]); ?>
                            </h3>


                            <p>
                                <?php echo htmlspecialchars($row["short_description"]); ?>
                            </p>


                            <div class="blog-actions">

                                <a
                                    href="blog_details.php?id=<?php echo $row['id']; ?>"
                                    class="read-more"
                                >
                                    Read More →
                                </a>


                                <?php if (!empty($row["pdf"])): ?>

                                    <a
                                        href="uploads/<?php echo htmlspecialchars($row["pdf"]); ?>"
                                        target="_blank"
                                        class="pdf-button"
                                    >
                                        📄 View PDF
                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <p class="no-blog">
                No blog posts found in this category.
            </p>

        <?php endif; ?>

    </section>


    <!-- Footer -->

    <footer class="footer">

        <div class="footer-content">

            <div class="footer-column">

                <h3>Contact Info</h3>
                <p>
    Email:
    <a href="mailto:naimatulislamnima@gmail.com">
        naimatulislamnima@gmail.com
    </a>
</p>

<p>
    Phone:
    <a href="tel:01792616719">01792616719</a>
</p>

<p>
    Phone:
    <a href="tel:01533824365">01533824365</a>
</p>

                
            </div>


            <div class="footer-column">

                <h3>Follow Us</h3>

                <p>
                    STAY CONNECTED WITH US THROUGH OUR SOCIAL MEDIA
                    CHANNELS FOR UPDATES AND EDUCATIONAL CONTENT.
                </p>

                <div class="social-icons">

                    <span>◎</span>
                    <span>f</span>
                    <span>in</span>
                    <span>◉</span>
                    <span>▶</span>
                    <span>𝕏</span>

                </div>

            </div>

        </div>


        <div class="copyright">

            COPYRIGHT © 2026 KHAN & TEAMS EDUCATION., LTD. ALL RIGHTS RESERVED.

        </div>

    </footer>

</body>

</html>