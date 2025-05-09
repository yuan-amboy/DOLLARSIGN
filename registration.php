<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/Exception.php';
require 'src/SMTP.php';

session_start();
require_once "database.php"; 

if(isset($_SESSION["users"])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT * FROM users WHERE token = ? AND is_active = 0";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $updateSql = "UPDATE users SET is_active = 1, token = NULL WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "i", $row['id']);
        mysqli_stmt_execute($updateStmt);

        $_SESSION["users"] = [
            "id" => $row["id"],
            "First_Name" => $row["First_Name"],
            "Last_Name" => $row["Last_Name"],
            "email" => isset($row["email"]) ? $row["email"] : null
        ];

        $_SESSION['verification_message'] = "Your account has been successfully verified!";
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid or already verified token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | DOLLARSIGN</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="body-register">
    <div class="container">
        <div class="brand-name">DOLLARSIGN</div>

        <h1 class="form-title">CREATE ACCOUNT</h1>

        <?php
        if(isset($_POST["submit"])) {
            $LastName = isset($_POST["LastName"]) ? htmlspecialchars(strip_tags(trim($_POST["LastName"]))) : '';
            $FirstName = isset($_POST["FirstName"]) ? htmlspecialchars(strip_tags(trim($_POST["FirstName"]))) : '';
            $email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
            $password = isset($_POST["password"]) ? $_POST["password"] : '';
            $ConfirmPassword = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : '';
            $errors = array();

            if (empty($LastName) || empty($FirstName) || empty($email) || empty($password) || empty($ConfirmPassword)) {
                array_push($errors, "All fields are required");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }

            if(strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }

            if ($password !== $ConfirmPassword) {
                array_push($errors, "Passwords do not match");
            }

            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                array_push($errors, "Email already exists!");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $token = bin2hex(random_bytes(50));

                $sql = "INSERT INTO users (Last_Name, First_Name, email, password, token, is_active) VALUES (?, ?, ?, ?, ?, 0)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssss", $LastName, $FirstName, $email, $passwordHash, $token);

                if (mysqli_stmt_execute($stmt)) {
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'yoshi.amboy@gmail.com';
                        $mail->Password = 'zvzb jodt mvxd fckf';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        $mail->setFrom('yoshi.amboy@gmail.com', 'DOLLARSIGN');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Verify Your Email Address';
                        $activationLink = "http://localhost/DOLLARSIGN/registration.php?token=$token";
                        $mail->Body = "
                            <h1>Hello, $FirstName!</h1>
                            <p>Click the link below to verify your email address:</p>
                            <a href='$activationLink'>Verify Your Email</a>
                        ";

                        $mail->send();
                        echo "<div class='email-notice'>We have sent an email to $email. Please check your inbox and verify your email address.</div>";
                    } catch (Exception $e) {
                        echo "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Something went wrong: " . mysqli_error($conn) . "</div>";
                }
            }
        }
        ?>

        <form action="registration.php" method="post">
            <div class="form-group">
                <label for="FirstName">First Name</label>
                <input type="text" class="form-control" name="FirstName">
            </div>
            <div class="form-group">
                <label for="LastName">Last Name</label>
                <input type="text" class="form-control" name="LastName">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password">
            </div>
            <div class="form-group">
                <input type="submit" class="btn-register" name="submit" value="CREATE ACCOUNT">
            </div>
        </form>

        <div><p>Already have an account? <a href="login.php">Login here.</a></p></div>

    </div>
</body>
</html>