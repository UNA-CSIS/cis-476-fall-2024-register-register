<?php session_start();
// session start here...

function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// get all 3 strings from the form (and scrub w/ validation function)
$username = validate($_POST['user']);
$password = validate($_POST['pwd']);
$repeat = validate($_POST['repeat']);

// make sure that the two password values match!
if ($password !== $repeat) {
    $_SESSION['error'] = 'Passwords do not match';
    header("Location: register.php");
    exit();
}

// create the password_hash using the PASSWORD_DEFAULT argument
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// login to the database
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "softball";
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $conn->real_escape_string($username);

// make sure that the new user is not already in the database
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $_SESSION['error'] = 'Username already exists';
    header("Location: register.php");
    exit();

} else {
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password_hash')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        $_SESSION['error'] = '';
        header("Location: index.php");
        exit();

    } else {
        $_SESSION['error'] = 'Error: ' . $conn->error;
        header("Location: register.php");
        exit();
    }
}

$conn->close();


// insert username and password hash into db (put the username in the session
// or make them login)
?>