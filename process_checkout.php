<?php
// Include database connection file
require_once 'config.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
    $city = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '';
    $state = isset($_POST['state']) ? htmlspecialchars($_POST['state']) : '';
    $zipcode = isset($_POST['zipcode']) ? htmlspecialchars($_POST['zipcode']) : '';
    $card_name = isset($_POST['card_name']) ? htmlspecialchars($_POST['card_name']) : '';
    $card_number = isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : '';
    $expiry_date = isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : '';
    $cvv = isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : '';

    // Simple validation (can be extended)
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($zipcode) || empty($card_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        die("Please fill out all fields.");
    }

    try {
        // Connect to database
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert order into the database
        $sql = "INSERT INTO orders 
                (customer_name, customer_email, customer_phone, shipping_address, city, state, zipcode, card_name, card_number, expiry_date, cvv) 
                VALUES 
                (:customer_name, :customer_email, :customer_phone, :shipping_address, :city, :state, :zipcode, :card_name, :card_number, :expiry_date, :cvv)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':customer_name', $name);
        $stmt->bindParam(':customer_email', $email);
        $stmt->bindParam(':customer_phone', $phone);
        $stmt->bindParam(':shipping_address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':zipcode', $zipcode);
        $stmt->bindParam(':card_name', $card_name);
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':expiry_date', $expiry_date);
        $stmt->bindParam(':cvv', $cvv);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to a confirmation page
            header("Location: confirmation.php");
            exit();
        } else {
            echo "Error: Unable to process your order at this time.";
        }
    } catch (PDOException $e) {
        // Log the error for debugging
        error_log("Database error: " . $e->getMessage());
        echo "Error: Unable to process your order. Please try again later.";
    }
}
?>
