<?php
// Include the common database connection code

// Additional code for login form submission
$loginMessage = ''; // Reset login message

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute a SQL statement to fetch user details from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user with the provided username exists
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                $loginMessage = "<div class='alert alert-success'>Login Successful!</div>";

                // Redirect to index.html
                header("Location: index.html");
                exit();
            } else {
                $loginMessage = "<div class='alert alert-danger'>Invalid username or password.</div>";
            }
        } else {
            $loginMessage = "<div class='alert alert-danger'>Invalid username or password.</div>";
        }
    } else {
        $loginMessage = "<div class='alert alert-danger'>Invalid form data.</div>";
    }
}

// Close the database connection if it is instantiated
if(isset($conn)) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .centered-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        body {
            background-color: #9E9E9E;
        }
    </style>
</head>
<body>
    <div class="container centered-form">
        <div class="card p-4" style="width: 370px; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);">
            <h2 style="text-align: center">Login</h2>
            <p style="color: red;"><?php echo $loginMessage; ?></p> <!-- Display login message -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</body>
</html>
