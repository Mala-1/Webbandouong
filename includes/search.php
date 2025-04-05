<?php
$q = $_GET['query'] ?? '';
echo "Kết quả tìm kiếm cho: " . htmlspecialchars($q);
?>