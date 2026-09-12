<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

function clean_text(string $value, int $max_length): string {
    $value = trim($value);
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
    return mb_substr($value, 0, $max_length, 'UTF-8');
}

$customer_name = clean_text($_POST['customer_name'] ?? '', 100);
$email = clean_text($_POST['email'] ?? '', 150);
$phone = clean_text($_POST['phone'] ?? '', 10);
$address = clean_text($_POST['address'] ?? '', 255);
$city = clean_text($_POST['city'] ?? '', 100);
$state = clean_text($_POST['state'] ?? '', 100);
$pincode = clean_text($_POST['pincode'] ?? '', 6);
$payment_method = clean_text($_POST['payment_method'] ?? 'Cash on Delivery', 50);

$allowed_states = ['Maharashtra','Gujarat','Madhya Pradesh','Karnataka','Delhi','Rajasthan','Other'];
$allowed_payment_methods = ['Cash on Delivery'];

$allowed_products = [
    'Apple' => 150.00,
    'Banana' => 60.00,
    'Alphonso Mango' => 180.00,
    'Orange' => 100.00,
    'Green Grapes' => 120.00,
    'Watermelon' => 40.00
];

$selected_products = $_POST['products'] ?? [];
if (!is_array($selected_products)) {
    $selected_products = [];
}

$valid_basic = ($customer_name !== '' && $email !== '' && $phone !== '' &&
    $address !== '' && $city !== '' && $state !== '' && $pincode !== '' &&
    !empty($selected_products));

if (!$valid_basic || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[0-9]{10}$/', $phone) ||
    !preg_match('/^[0-9]{6}$/', $pincode) ||
    !in_array($state, $allowed_states, true) ||
    !in_array($payment_method, $allowed_payment_methods, true)) {
    http_response_code(400);
    exit("<h2>Invalid order details.</h2><p>Please go back and enter valid information.</p><a href='index.html'>Back to Store</a>");
}

// Server-side price calculation: never trust the price/total sent by the browser.
$products_summary_parts = [];
$total_amount = 0.00;
$seen_products = [];

foreach ($selected_products as $product_name) {
    if (!is_string($product_name) || !array_key_exists($product_name, $allowed_products)) {
        http_response_code(400);
        exit("<h2>Invalid product selection.</h2><a href='index.html'>Back to Store</a>");
    }

    if (isset($seen_products[$product_name])) {
        http_response_code(400);
        exit("<h2>Invalid product selection.</h2><a href='index.html'>Back to Store</a>");
    }
    $seen_products[$product_name] = true;

    $quantity_key = 'quantity_' . str_replace(' ', '_', $product_name);
    $quantity = filter_var($_POST[$quantity_key] ?? null, FILTER_VALIDATE_INT);

    if ($quantity === false || $quantity < 1 || $quantity > 100) {
        http_response_code(400);
        exit("<h2>Invalid quantity.</h2><a href='index.html'>Back to Store</a>");
    }

    $unit_price = $allowed_products[$product_name];
    $total_amount += $unit_price * $quantity;
    $products_summary_parts[] = $product_name . ' x ' . $quantity;
}

$products_summary = implode(', ', $products_summary_parts);
$status = 'Pending';

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO orders
    (customer_name, email, phone, address, city, state, pincode, products, total_amount, payment_method, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    error_log('FreshBasket prepare error: ' . mysqli_error($conn));
    http_response_code(500);
    exit("<h2>Something went wrong.</h2><p>Please try again later.</p><a href='index.html'>Back to Store</a>");
}

mysqli_stmt_bind_param(
    $stmt,
    'ssssssssdss',
    $customer_name,
    $email,
    $phone,
    $address,
    $city,
    $state,
    $pincode,
    $products_summary,
    $total_amount,
    $payment_method,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    $order_id = mysqli_insert_id($conn);
    $safe_order_id = htmlspecialchars((string)$order_id, ENT_QUOTES, 'UTF-8');
    $safe_products = htmlspecialchars($products_summary, ENT_QUOTES, 'UTF-8');
    $safe_total = number_format($total_amount, 2);

    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'><title>Order Saved</title>
    <style>body{font-family:Arial,sans-serif;background:#f5fff7;margin:0;padding:50px;text-align:center;color:#183b24}.card{max-width:560px;margin:auto;background:#fff;padding:35px;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.08)}a{display:inline-block;margin:8px;padding:11px 18px;border-radius:8px;background:#198754;color:#fff;text-decoration:none}</style>
    </head><body><div class='card'><div style='font-size:52px'>🎉</div><h1>Order Placed Successfully!</h1><p>Your FreshBasket order has been saved in MySQL.</p><p><strong>Order ID: #{$safe_order_id}</strong></p><p>Items: <strong>{$safe_products}</strong></p><p>Total: <strong>₹{$safe_total}</strong></p><a href='index.html'>Back to Store</a><a href='view.php'>View All Orders</a></div></body></html>";
} else {
    error_log('FreshBasket insert error: ' . mysqli_stmt_error($stmt));
    http_response_code(500);
    exit("<h2>Something went wrong.</h2><p>Your order could not be saved. Please try again later.</p><a href='index.html'>Back to Store</a>");
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
