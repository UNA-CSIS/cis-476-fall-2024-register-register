<?php session_start();

// login to the softball database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softball";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$endUser = $_POST['user'];
$endPwd = $_POST['pwd'];

$endUser = $conn->real_escape_string($endUser);
$endPwd = $conn->real_escape_string($endPwd);

// select password from users where username = <what the user typed in>
$sql = "SELECT password FROM users WHERE username = '$endUser'";
$result = $conn->query($sql);

// if no rows, then username is not valid (but don't tell Mallory) just send
// her back to the login
if ($result->num_rows > 0) {
  if ($row = $result->fetch_assoc()) {
    if (password_verify($endPwd, $row['password'])) {
      $_SESSION['username'] = $endUser;
      $_SESSION['error'] = '';
    } else {
      $_SESSION['error'] = 'Invalid username or password';
    }
  }
} else {
  $_SESSION['error'] = 'Invalid username or password';
}
$conn->close();
header("location:index.php");

// otherwise, password_verify(password from form, password from db)
// if good, put username in session, otherwise send back to login
?>