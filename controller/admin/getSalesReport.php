<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit();
}

require_once '../../model/database.php';

header('Content-Type: application/json');

// Get total revenue
$revenue_sql = "SELECT COALESCE(SUM(amount), 0) as total_revenue FROM Payments WHERE payment_status = 'Paid'";
$revenue_result = $conn->query($revenue_sql);
$total_revenue = $revenue_result->fetch_assoc()['total_revenue'];

// Get total orders
$orders_sql = "SELECT COUNT(*) as total_orders FROM Orders";
$orders_result = $conn->query($orders_sql);
$total_orders = $orders_result->fetch_assoc()['total_orders'];

// Calculate average order value
$average_order_value = $total_orders > 0 ? $total_revenue / $total_orders : 0;

// Get recent orders (last 10)
$recent_orders_sql = "SELECT o.order_id, u.username as customer_name, p.amount, o.created_at as order_date, o.order_status as status
                      FROM Orders o
                      JOIN Customers c ON o.customer_id = c.customer_id
                      JOIN Users u ON c.customer_id = u.user_id
                      LEFT JOIN Payments p ON o.order_id = p.order_id
                      ORDER BY o.created_at DESC LIMIT 10";
$recent_orders_result = $conn->query($recent_orders_sql);

$recent_orders = [];
if ($recent_orders_result->num_rows > 0) {
    while($row = $recent_orders_result->fetch_assoc()) {
        $recent_orders[] = $row;
    }
}

$sales_data = [
    'totalRevenue' => floatval($total_revenue),
    'totalOrders' => intval($total_orders),
    'averageOrderValue' => floatval($average_order_value),
    'recentOrders' => $recent_orders
];

echo json_encode(['success' => true, 'data' => $sales_data]);
$conn->close();
?>