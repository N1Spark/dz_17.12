<?php
global $conn;
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotel_id = htmlspecialchars($_POST['hotel_id']);
    $user_name = htmlspecialchars($_POST['user_name']);
    $comment = htmlspecialchars($_POST['comment']);

    $sql = "INSERT INTO comments (hotel_id, user_name, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $hotel_id, $user_name, $comment);

    if ($stmt->execute()) {
        header("Location: hotelinfo.php?id=" . $hotel_id);
        exit;
    }
    else
        echo "Ошибка: " . $stmt->error;

    $stmt->close();
}

$hotels_result = $conn->query(
        "SELECT id, name FROM hotels");
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Comment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="comment-form" action="comments.php" method="POST">
        <h3>Share your comment</h3>
        <select name="hotel_id" id="hotel_id" required>
            <option value="" disabled selected>Choose hotel</option>
            <?php
            if ($hotels_result->num_rows > 0) {
                while ($row = $hotels_result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                }
            }
            ?>
        </select>
        <input type="text" name="user_name" id="user_name" placeholder="Name" required>
        <textarea name="comment" id="comment" placeholder="Comment" required></textarea>
        <button type="submit">Send</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
