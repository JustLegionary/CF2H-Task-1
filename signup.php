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
            $confirmPassword = htmlspecialchars($_POST['confirmPassword']);
    
            if (empty($username) || empty($password) || empty($confirmPassword)) {
                $response['error'] = "All fields are required";
            } elseif (strlen($password) < 4 || strlen($password) > 15) {
                $response['error'] = "Password must be between 4 and 15 characters long";
            } elseif ($password !== $confirmPassword) {
                $response['error'] = "Passwords do not match";
            } else {
                $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username=?");
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $response['error'] = "Username already exists";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
                    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
                    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['username'] = $username;
                        $response['redirect'] = 'welcome.php';
                    } else {
                        $response['error'] = "Error creating user";
                    }
                }
            }
            mysqli_close($conn);
        }
}

echo json_encode($response);
?>
