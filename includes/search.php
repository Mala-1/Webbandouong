<?php
$query = $_GET['query'] ?? '';
$category = $_GET['category'] ?? '';
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';

echo "<h3>Kết quả tìm kiếm:</h3>";
echo "<p>Tên gần đúng: <b>" . htmlspecialchars($query) . "</b></p>";
echo "<p>Thể loại: <b>" . htmlspecialchars($category) . "</b></p>";
echo "<p>Giá từ <b>$min</b> đến <b>$max</b></p>";

//Thực hiện truy vấn SQL tại đây 
?>