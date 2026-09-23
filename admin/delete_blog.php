<?php

require_once "../config/db.php";

if (!isset($_GET["id"])) {
    die("Blog ID not found.");
}

$id = intval($_GET["id"]);

$sql = "DELETE FROM blogs WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: manage_blogs.php");
    exit;
} else {
    echo "Error deleting blog: " . $conn->error;
}

$stmt->close();

?>