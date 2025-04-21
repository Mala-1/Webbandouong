<?php
require_once '../includes/DBConnect.php';
$db = DBConnect::getInstance();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Số bản ghi mỗi trang
$offset = ($page - 1) * $limit;

$search_username = isset($_GET['search_username']) ? $_GET['search_username'] : '';
$search_phone = isset($_GET['search_phone']) ? $_GET['search_phone'] : '';

$where = "role_id = 1";
$params = [];
if ($search_username) {
    $where .= " AND username LIKE ?";
    $params[] = "%$search_username%";
}
if ($search_phone) {
    $where .= " AND phone LIKE ?";
    $params[] = "%$search_phone%";
}

$sql = "SELECT user_id, username, password, phone FROM users WHERE $where LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$customers = $db->select($sql, $params);

$sqlCount = "SELECT COUNT(*) as total FROM users WHERE $where";
$countResult = $db->select($sqlCount, array_slice($params, 0, -2));
$total = $countResult[0]['total'];
$totalPages = ceil($total / $limit);

$output = '';
foreach ($customers as $customer) {
    $output .= "<tr>";
    $output .= "<td>" . htmlspecialchars($customer['user_id']) . "</td>";
    $output .= "<td>" . htmlspecialchars($customer['username']) . "</td>";
    $output .= "<td>" . htmlspecialchars($customer['password']) . "</td>";
    $output .= "<td>" . htmlspecialchars($customer['phone'] ?? '') . "</td>";
    if (in_array('edit', $_SESSION['permissions']['customers'] ?? [])) {
        $output .= "<td class='action-icons'>";
        $output .= "<i class='fas fa-pen text-primary btn-edit-customer' data-id='" . $customer['user_id'] . "' data-username='" . htmlspecialchars($customer['username']) . "' data-phone='" . htmlspecialchars($customer['phone'] ?? '') . "'></i>";
        $output .= "<i class='fas fa-trash text-danger btn-delete-customer' data-id='" . $customer['user_id'] . "' data-bs-toggle='modal' data-bs-target='#modalXoaCustomer'></i>";
        $output .= "</td>";
    }
    $output .= "</tr>";
}

$pagination = '';
if ($totalPages > 1) {
    $pagination .= "<nav><ul class='pagination justify-content-center'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $pagination .= "<li class='page-item " . ($i == $page ? 'active' : '') . "'>";
        $pagination .= "<a class='page-link' href='#' onclick='triggerPagination($i, \"customerpage\")'>$i</a>";
        $pagination .= "</li>";
    }
    $pagination .= "</ul></nav>";
}

echo $output . 'SPLIT' . $pagination;
?>