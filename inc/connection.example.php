<?PHP

// TO DO
$hostDB = "";
$userDB = "";
$passwordDB = "";
$databaseDB = "";
// END OF TO DO

$link = mysqli_connect($hostDB, $userDB, $passwordDB, $databaseDB);

if (!$link) {
    echo "Error: Unable to connect to MySQL ".mysqli_connect_error();
}

$query = "SELECT * from users WHERE id='".$_SESSION['login_id']."'";
$session_row = mysqli_fetch_assoc(mysqli_query($link,$query));

?>
