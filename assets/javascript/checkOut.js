document.addEventListener("DOMContentLoaded", () => {
    // 1. Lấy dữ liệu từ localStorage
    const itemsJson = localStorage.getItem("checkoutItems");
    if (!itemsJson) {
      alert("Không tìm thấy thông tin đơn hàng. Vui lòng quay lại giỏ hàng.");
      window.location.href = "cart.html";
      return;
    }
    const items = JSON.parse(itemsJson);
  
    // 2. Render bảng đơn hàng
    const tbody = document.getElementById("order-table");
    let total = 0;
    items.forEach((item, idx) => {
      total += item.subtotal;
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td><img src="${item.image}" alt="${item.name}" class="img-thumbnail product-img"></td>
        <td>${item.name}</td>
        <td>${item.quantity}</td>
        <td>${item.price.toLocaleString()} VND</td>
        <td><strong>${item.subtotal.toLocaleString()} VND</strong></td>
      `;
      tbody.appendChild(tr);
  
      // Tạo hidden inputs để gửi lên server
      const hiddenDiv = document.getElementById("hidden-items");
      hiddenDiv.innerHTML += `
        <input type="hidden" name="items[${idx}][id]" value="${item.id}">
        <input type="hidden" name="items[${idx}][name]" value="${item.name}">
        <input type="hidden" name="items[${idx}][price]" value="${item.price}">
        <input type="hidden" name="items[${idx}][quantity]" value="${item.quantity}">
      `;
    });
  
    // 3. Hiển thị tổng
    document.getElementById("order-total").innerText = total.toLocaleString();
  
    // 4. (Tuỳ chọn) Xoá dữ liệu giỏ hàng đã chuyển
    localStorage.removeItem("checkoutItems");
  });
  