<?php session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("location: index.php");
    exit;
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "softball";
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT opponent, location, result FROM games";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
</head>

<body>
    Display games here...
    <?php
    // put your code here
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Date</th><th>Opponent</th><th>Location</th><th>Result</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['game_date'] . "</td>";
            echo "<td>" . $row['opponent'] . "</td>";
            echo "<td>" . $row['location'] . "</td>";
            echo "<td>" . $row['result'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No games found.";
    }
    if ($conn) {
        $conn->close();
    }
    ?>
</body>

</html>