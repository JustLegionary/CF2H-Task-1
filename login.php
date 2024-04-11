<?php
session_start();
$response = array('error' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $config = parse_ini_file('config.ini');

    $conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['database']);

    if (!$conn) {
        $response['error'] = "Connection failed";
    } else {

        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        if (empty($username) || empty($password)) {
            $response['error'] = "Invalid username or password";
        } else {

            $query = "SELECT * FROM users WHERE username=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password, $row['password'])) {

                    session_regenerate_id(true);
                    
                    $_SESSION['username'] = $username;
                    $response['redirect'] = 'welcome.php';
                } else {
                    $response['error'] = "Invalid username or password";
                }
            } else {
                $response['error'] = "Invalid username or password";
            }
        }
        mysqli_close($conn);
    }
}

echo json_encode($response);
?>
