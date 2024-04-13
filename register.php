<?php
// Database configuration
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'system_db';

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration form submission
$registrationMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirm_password']) && isset($_POST['user_type'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $userType = $_POST['user_type'];

        // Validate form data
        if ($password !== $confirmPassword) {
            $registrationMessage = "<div class='alert alert-danger'>Passwords do not match.</div>";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and execute a SQL statement to insert user details into the database
            $stmt = $conn->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $userType);
            $stmt->execute();

            // Check if the registration was successful
            if ($stmt->affected_rows === 1) {
                $registrationMessage = "<div class='alert alert-success'>Registration Successful! Now LOGIN</div>";
            } else {
                $registrationMessage = "<div class='alert alert-danger'>Error occurred during registration.</div>";
            }
        }
    } else {
        $registrationMessage = "<div class='alert alert-danger'>Invalid form data.</div>";
    }
}

// Define the user type options
$userTypes = array("user");

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .centered-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        body{
            background-color:  #9E9E9E;
        }
    </style>
</head>
<body>
    <div class="container centered-form">
        <div class="card p-4" style="width: 370px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
            <h2 style="text-align: center">Register</h2>
            <p style="color: red;"><?php echo $registrationMessage; ?></p> <!-- Display registration message -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <label for="user_type">Type:</label>
                    <select class="form-control" id="user_type" name="user_type" required>
                        <?php
                        foreach ($userTypes as $type) {
                            echo "<option value='$type'>$type</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
