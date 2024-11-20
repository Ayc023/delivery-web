<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Welcome to the checkout page!";

// Include database connection file
require_once 'config.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $customer_name = isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : '';
    $customer_email = isset($_POST['customer_email']) ? htmlspecialchars($_POST['customer_email']) : '';
    $customer_phone = isset($_POST['customer_phone']) ? htmlspecialchars($_POST['customer_phone']) : '';
    $shipping_address = isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : '';
    $city = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '';
    $state = isset($_POST['state']) ? htmlspecialchars($_POST['state']) : '';
    $zipcode = isset($_POST['zipcode']) ? htmlspecialchars($_POST['zipcode']) : '';
    $card_name = isset($_POST['card_name']) ? htmlspecialchars($_POST['card_name']) : '';
    $card_number = isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : '';
    $expiry_date = isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : '';
    $cvv = isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : '';
    
    // Automatically set the order date
    $order_date = date("Y-m-d H:i:s");

    // Simple validation (can be extended)
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($shipping_address) || empty($city) || empty($state) || empty($zipcode) || empty($card_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        die("Please fill out all fields.");
    }

    try {
        // Connect to database
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert order into the database
        $sql = "INSERT INTO orders 
                (customer_name, customer_email, customer_phone, shipping_address, city, state, zipcode, card_name, card_number, expiry_date, cvv, order_date) 
                VALUES 
                (:customer_name, :customer_email, :customer_phone, :shipping_address, :city, :state, :zipcode, :card_name, :card_number, :expiry_date, :cvv, :order_date)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':customer_email', $customer_email);
        $stmt->bindParam(':customer_phone', $customer_phone);
        $stmt->bindParam(':shipping_address', $shipping_address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);
        $stmt->bindParam(':zipcode', $zipcode);
        $stmt->bindParam(':card_name', $card_name);
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':expiry_date', $expiry_date);
        $stmt->bindParam(':cvv', $cvv);
        $stmt->bindParam(':order_date', $order_date);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to a confirmation page
            header("Location: confirmation.html");
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


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <style>
    .navbar {
      background-color: #124704 !important;
    }
    .btn-custom {
      background-color: #ff9800;
      color: #fff;
      border: none;
    }
    .btn-custom:hover {
      background-color: #e68900;
      color: #fff;
    }
    .footer {
      background-color: #124704; /* Matches the navbar */
      color: #ffffff;
    }
    .footer a {
      color: #ff9800; /* Matches the button for accent links */
      text-decoration: none;
    }
    .footer a:hover {
      color: #e68900; /* Slightly darker shade for hover */
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">DeliveryNow</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="products.html">Products</a></li>
            <li class="nav-item"><a class="nav-link" href="cart.html">Cart</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <!-- Checkout Form -->
  <section class="checkout py-5">
    <div class="container">
      <h2 class="text-center text-primary mb-4">Checkout</h2>
      <form action="" method="POST">
  <div class="row g-3">
    <!-- Customer Details -->
    <div class="col-md-6">
      <label for="customer_name" class="form-label">Full Name</label>
      <input type="text" class="form-control" id="customer_name" name="customer_name" required>
    </div>
    <div class="col-md-6">
      <label for="customer_email" class="form-label">Email</label>
      <input type="email" class="form-control" id="customer_email" name="customer_email" required>
    </div>
    <div class="col-md-6">
      <label for="customer_phone" class="form-label">Phone Number</label>
      <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
    </div>
    <!-- Address -->
    <div class="col-md-6">
      <label for="shipping_address" class="form-label">Address</label>
      <input type="text" class="form-control" id="shipping_address" name="shipping_address" required>
    </div>
    <div class="col-md-4">
      <label for="city" class="form-label">City</label>
      <input type="text" class="form-control" id="city" name="city" required>
    </div>
    <div class="col-md-4">
      <label for="state" class="form-label">State</label>
      <input type="text" class="form-control" id="state" name="state" required>
    </div>
    <div class="col-md-4">
      <label for="zipcode" class="form-label">Zip Code</label>
      <input type="text" class="form-control" id="zipcode" name="zipcode" pattern="\d{5}" title="Please enter a valid 5-digit ZIP code" required>
    </div>
    <!-- Payment Details -->
    <div class="col-md-6">
      <label for="card_name" class="form-label">Cardholder Name</label>
      <input type="text" class="form-control" id="card_name" name="card_name" required>
    </div>
    <div class="col-md-6">
      <label for="card_number" class="form-label">Card Number</label>
      <input type="text" class="form-control" id="card_number" name="card_number" pattern="\d{16}" title="Please enter a 16-digit card number" required>
    </div>
    <div class="col-md-6">
      <label for="expiry_date" class="form-label">Expiry Date (MM/YY)</label>
      <input type="text" class="form-control" id="expiry_date" name="expiry_date" pattern="\d{2}/\d{2}" title="Please enter a valid expiry date in MM/YY format" required>
    </div>
    <div class="col-md-6">
      <label for="cvv" class="form-label">CVV</label>
      <input type="text" class="form-control" id="cvv" name="cvv" pattern="\d{3}" title="Please enter a 3-digit CVV" required>
    </div>
  </div>
  <button type="submit" class="btn btn-custom mt-4 w-100">Place Order</button>
</form>

    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2024 DeliveryNow. All Rights Reserved.</p>
  </footer>

  <!-- JavaScript -->
  <script>
    document.getElementById('checkoutForm').addEventListener('submit', function(event) {
      const cardNumber = document.getElementById('card_number').value;
      const cvv = document.getElementById('cvv').value;

      if (!/^\d{16}$/.test(cardNumber)) {
        alert('Please enter a valid 16-digit card number.');
        event.preventDefault();
      }

      if (!/^\d{3}$/.test(cvv)) {
        alert('Please enter a valid 3-digit CVV.');
        event.preventDefault();
      }
    });
  </script>
</body>
</html>
