<!DOCTYPE html>
<html>
<head>
	<title>Trang đổi trả hàng</title>
</head>
<body>
	<h1>Đổi trả hàng</h1>
	<form method="post" action="process_return.php">
		<label for="order">Chọn đơn hàng cần đổi trả:</label>
		<select name="order" id="order">
			<?php
				// Kết nối cơ sở dữ liệu
        require ("../BackEnd/ConnectionDB/DB_driver.php");

				// Truy vấn các đơn hàng có trạng thái "Đã giao hàng" hoặc "Đang giao hàng"
				$sql = "SELECT * FROM hoadon WHERE TrangThai IN ('Đã giao hàng', 'Đang giao hàng')";
				$result = mysqli_query($conn, $sql);

				// Hiển thị danh sách các đơn hàng có thể đổi trả
				while ($row = mysqli_fetch_assoc($result)) {
					echo "<option value='" . $row['MaHD'] . "'>Đơn hàng #" . $row['MaHD'] . " - " . $row['MaKH'] . " (" . $row['NgayLap'] . ")</option>";
				}

				// Đóng kết nối cơ sở dữ liệu
				mysqli_close($conn);
			?>
		</select>
		<br><br>
		<input type="submit" value="Đổi trả">
	</form>
</body>
</html>
<?php
	// Kết nối cơ sở dữ liệu
  require ("../BackEnd/ConnectionDB/DB_driver.php");



	// Lấy thông tin về đơn hàng
	$order_id = $_POST['order'];
	$sql = "SELECT * FROM hoadon WHERE MaHD = $order_id";
	$result = mysqli_query($conn, $sql);
	$order = mysqli_fetch_assoc($result);

	// Lấy thông tin về các sản phẩm trong đơn hàng
	$sql = "SELECT * FROM chitiethoadon WHERE MaHD = $order_id";
	$result = mysqli_query($conn, $sql);

	// Hiển thị form cho phép người dùng chọn sản phẩm cần đổi trả và nhập thông tin yêu cầu đổi trả
	echo "<h1>Đổi trả hàng cho đơn hàng #" . $order['MaHD'] . "</h1>";
	echo "<form method='post' action='process_return_request.php'>";
	echo "<input type='hidden' name='order_id' value='" . $order_id . "'>";
	echo "<table>";
	echo "<tr><th>Mã sản phẩm</th><th>Tên sản phẩm</th><th>Số lượng đặt</th><th>Số lượng đổi trả</th><th>Lý do</th></tr>";
	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr>";
		echo "<td>" . $row['masp'] . "</td>";
		echo "<td>" . $row['tensp'] . "</td>";
		echo "<td>" . $row['soluong'] . "</td>";
		echo "<td><input type='number' name='quantity_" . $row['masp'] . "' min='1' max='" . $row['soluong'] . "'></td>";
		echo "<td><input type='text' name='reason_" . $row['masp'] . "'></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<br>";
	echo "<input type='submit' value='Gửi yêu cầu đổi trả'>";
	echo "</form>";

	// Đóng kết nối cơ sở dữ liệu
	mysqli_close($conn);
?>