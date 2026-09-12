<?php
include 'connect.php';

function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$q = trim($_GET['q'] ?? '');
$status_filter = trim($_GET['status'] ?? '');
$allowed_statuses = ['Pending', 'Delivered'];
if (!in_array($status_filter, $allowed_statuses, true)) {
    $status_filter = '';
}

if (mb_strlen($q, 'UTF-8') > 80) {
    $q = mb_substr($q, 0, 80, 'UTF-8');
}

if ($q !== '' && $status_filter !== '') {
    $like = '%' . $q . '%';
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE (customer_name LIKE ? OR email LIKE ? OR products LIKE ? OR city LIKE ?) AND status = ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 'sssss', $like, $like, $like, $like, $status_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} elseif ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE customer_name LIKE ? OR email LIKE ? OR products LIKE ? OR city LIKE ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 'ssss', $like, $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} elseif ($status_filter !== '') {
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE status = ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 's', $status_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
}

if (!$result) {
    error_log('FreshBasket SELECT error: ' . mysqli_error($conn));
    http_response_code(500);
    exit('Unable to load orders right now. Please try again later.');
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'>
<title>FreshBasket - Orders</title>
<style>
*{box-sizing:border-box}body{font-family:Arial,Helvetica,sans-serif;background:#f7faf7;margin:0;color:#183b24}.container{width:96%;max-width:1500px;margin:35px auto}.top{display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap}.top h1{margin:0;font-size:32px}.btn{display:inline-block;text-decoration:none;background:#198754;color:#fff;padding:11px 18px;border-radius:8px;font-weight:700}.filters{margin-top:22px;background:#fff;border:1px solid #d9e5db;border-radius:12px;padding:18px;display:flex;gap:10px;flex-wrap:wrap}.filters input,.filters select{padding:11px;border:1px solid #ccd9cf;border-radius:8px;font-size:15px}.filters input{min-width:300px}.filters button{border:0;background:#198754;color:#fff;padding:11px 18px;border-radius:8px;font-weight:700;cursor:pointer}.clear{padding:11px 18px;color:#198754;text-decoration:none}.table-wrap{margin-top:24px;background:#fff;border:1px solid #d9e5db;border-radius:12px;overflow:auto;box-shadow:0 5px 20px rgba(0,0,0,.05)}table{width:100%;border-collapse:collapse;min-width:1300px}th,td{border-bottom:1px solid #e5ece6;padding:12px;text-align:left;vertical-align:top}th{background:#e9f6ec;color:#174c2a;white-space:nowrap}tr:hover td{background:#fafdfb}.badge{display:inline-block;padding:5px 9px;border-radius:20px;background:#fff3cd;color:#765b00;font-size:13px;font-weight:700}.amount{font-weight:700;white-space:nowrap}.small{font-size:12px;color:#66756b}.empty{text-align:center;padding:40px}
</style>
</head>
<body><div class='container'>
<div class='top'><div><h1>📦 FreshBasket Orders</h1><p class='small'>View, search and filter the orders stored in MySQL.</p></div><a class='btn' href='index.html'>← Back to Store</a></div>
<form class='filters' method='get' action='view.php'>
<input type='search' name='q' maxlength='80' value='<?php echo h($q); ?>' placeholder='Search customer, email, city or product'>
<select name='status'><option value=''>All Statuses</option><option value='Pending' <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option><option value='Delivered' <?php echo $status_filter === 'Delivered' ? 'selected' : ''; ?>>Delivered</option></select>
<button type='submit'>🔎 Search / Filter</button><a class='clear' href='view.php'>Clear</a>
</form>
<div class='table-wrap'><table><thead><tr><th>ID</th><th>Customer</th><th>Email</th><th>Phone</th><th>Address</th><th>Products</th><th>Total</th><th>Payment</th><th>Status</th><th>Order Date</th></tr></thead><tbody>
<?php if (mysqli_num_rows($result) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr><td><?php echo h($row['id']); ?></td><td><?php echo h($row['customer_name']); ?></td><td><?php echo h($row['email']); ?></td><td><?php echo h($row['phone']); ?></td><td><?php echo h($row['address'] . ', ' . $row['city'] . ', ' . $row['state'] . ' - ' . $row['pincode']); ?></td><td><?php echo h($row['products']); ?></td><td class='amount'>₹<?php echo number_format((float)$row['total_amount'],2); ?></td><td><?php echo h($row['payment_method']); ?></td><td><span class='badge'><?php echo h($row['status']); ?></span></td><td><?php echo h($row['created_at']); ?></td></tr>
<?php endwhile; ?>
<?php else: ?><tr><td class='empty' colspan='10'>No matching orders found.</td></tr><?php endif; ?>
</tbody></table></div></div></body></html>
<?php mysqli_close($conn); ?>
