<?php
require_once "database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"] ?? '';

    if (!empty($token)) {
        $sql = "SELECT * FROM users WHERE token = ? AND is_active = 0";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $updateSql = "UPDATE users SET is_active = 1, token = NULL WHERE token = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "s", $token);
            
            if (mysqli_stmt_execute($updateStmt)) {
                $_SESSION["users"] = [
                    "id" => $row["id"],
                    "First_Name" => $row["First_Name"],
                    "Last_Name" => $row["Last_Name"],
                    "email" => $row["email"]
                ];
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Activation failed."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid or already activated token."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing token."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
