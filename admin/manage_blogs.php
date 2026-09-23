<?php

require_once "../config/db.php";

$sql = "SELECT * FROM blogs ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
</head>

<body>

    <h1>Manage Blogs</h1>

    <a href="add_blog.php">+ Add New Blog</a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">

        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Short Description</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>

            <?php while ($row = $result->fetch_assoc()): ?>

                <tr>
                    <td><?php echo $row["id"]; ?></td>

                    <td><?php echo htmlspecialchars($row["title"]); ?></td>

                    <td><?php echo htmlspecialchars($row["category"]); ?></td>

                    <td>
                        <?php echo htmlspecialchars($row["short_description"]); ?>
                    </td>

                    <td><?php echo $row["created_at"]; ?></td>

                    <td>
                        <a href="edit_blog.php?id=<?php echo $row["id"]; ?>">
                            Edit
                        </a>

                        |

                        <a href="delete_blog.php?id=<?php echo $row["id"]; ?>"
                           onclick="return confirm('Are you sure you want to delete this blog?');">
                            Delete
                        </a>
                    </td>
                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">No blogs found.</td>
            </tr>

        <?php endif; ?>

    </table>

</body>
</html>