<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Review</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, #4a90e2, #9013fe); /* Gradient background */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.form-container {
    background-color: #333; /* Dark background for the form */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); /* Slightly darker shadow */
    max-width: 400px;
    width: 100%;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #fff; /* White text for better contrast */
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #ccc; /* Light gray for labels */
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #555; /* Darker border */
    border-radius: 4px;
    font-size: 14px;
    background-color: #444; /* Dark background for inputs */
    color: #fff; /* White text for inputs */
    box-sizing: border-box;
}

input:focus, textarea:focus {
    border-color: #007BFF; /* Highlight border on focus */
    outline: none; /* Remove default outline */
}

button {
    width: 100%;
    background-color: #007bff; /* Primary button color */
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3; /* Darker shade on hover */
}
</style>
</head>
<body>
    <div class="form-container">
        <h1>Submit Your Review</h1>
        <form action="process.php" method="POST" class="review-form">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="5" required></textarea>

            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>
</body>
</html>


<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "reviews_db";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $review = htmlspecialchars(trim($_POST['review']));

    if (!empty($name) && !empty($email) && !empty($review)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO reviews (name, email, review) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $review);

            // Execute and check if successful
            if ($stmt->execute()) {
                echo "Review submitted successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Invalid email format.";
        }
    } else {
        echo "All fields are required.";
    }
}

// Close connection
$conn->close();
?>
