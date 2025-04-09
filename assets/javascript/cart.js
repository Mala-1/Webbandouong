// script.js

// Giả lập trạng thái đăng nhập (true nếu đã đăng nhập)
let isLoggedIn = false;

// Dữ liệu mẫu của giỏ hàng
const products = [
  { id: 1, name: "CocaCoca - 1 lon", price: 15000, quantity: 1, selected: false, image: "../assets/images/SanPham/coca_1_lon.jpg" },
  { id: 2, name: "Budweiser - Thùng 12 lon", price: 20000, quantity: 1, selected: false, image: "../assets/images/SanPham/heniken1.jpg" },
  { id: 3, name: "Pepsi - 1 lon", price: 18000, quantity: 1, selected: false, image: "../assets/images/SanPham/Pepsi_1_lon.jpg" },
];

// Hiển thị trạng thái đăng nhập
function renderAuthStatus() {
  const authDiv = document.getElementById("auth-status");
  if (isLoggedIn) {
    authDiv.innerHTML = `
      <span class="me-2">Xin chào, User!</span>
      <button class="btn btn-outline-danger btn-sm">Đăng xuất</button>
    `;
  } else {
    authDiv.innerHTML = `
      <button class="btn btn-primary btn-sm me-2">Đăng nhập</button>
      <button class="btn btn-success btn-sm">Đăng ký</button>
    `;
  }
}

// Render danh sách sản phẩm
function renderProducts() {
  const productList = document.getElementById("product-list");
  productList.innerHTML = "";
  products.forEach((product, index) => {
    const div = document.createElement("div");
    div.className = "row align-items-center product-row";
    div.innerHTML = `
      <div class="col-1 text-center">
        <input type="checkbox" class="form-check-input product-checkbox" data-index="${index}" ${product.selected ? "checked" : ""}>
      </div>
      <div class="col-2 text-center">
        <img src="${product.image}" alt="${product.name}" class="img-thumbnail product-img">
      </div>
      <div class="col-3">
        <strong>${product.name}</strong><br>
        <small>Giá: ${product.price.toLocaleString()} VND</small>
      </div>
      <div class="col-3 d-flex align-items-center">
        <button class="btn btn-secondary quantity-btn me-1" data-index="${index}" data-action="decrease">-</button>
        <input type="text" class="form-control text-center" style="max-width:50px" value="${product.quantity}" data-index="${index}">
        <button class="btn btn-secondary quantity-btn ms-1" data-index="${index}" data-action="increase">+</button>
      </div>
      <div class="col-3">
        <button class="btn btn-danger btn-sm" data-index="${index}" data-action="remove">Xóa</button>
      </div>
    `;
    productList.appendChild(div);
  });
  attachEventListeners();
  updateTotal();
}

// Cập nhật tổng tiền
function updateTotal() {
  let total = 0;
  products.forEach(p => {
    if (p.selected) total += p.price * p.quantity;
  });
  document.getElementById("total-price").innerText = total.toLocaleString();
}

// Gắn sự kiện cho checkbox, tăng/giảm, xóa
function attachEventListeners() {
  document.querySelectorAll(".product-checkbox").forEach(cb => {
    cb.addEventListener("change", () => {
      const i = cb.dataset.index;
      products[i].selected = cb.checked;
      updateTotal();
    });
  });
  document.querySelectorAll("button[data-action]").forEach(btn => {
    btn.addEventListener("click", () => {
      const i = btn.dataset.index;
      const action = btn.dataset.action;
      if (action === "increase") products[i].quantity++;
      else if (action === "decrease" && products[i].quantity > 1) products[i].quantity--;
      else if (action === "remove") products.splice(i, 1);
      renderProducts();
    });
  });
}

// Khởi tạo khi DOM đã sẵn sàng
document.addEventListener("DOMContentLoaded", () => {
  renderAuthStatus();
  renderProducts();

  document.getElementById("checkout-button").addEventListener("click", () => {
    const itemsToCheckout = products
      .filter(p => p.selected)
      .map(p => ({
        id: p.id,
        name: p.name,
        image: p.image,
        price: p.price,
        quantity: p.quantity,
        subtotal: p.price * p.quantity
      }));
    if (itemsToCheckout.length === 0) {
      alert("Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.");
      return;
    }
    localStorage.setItem("checkoutItems", JSON.stringify(itemsToCheckout));
    window.location.href = "payment.html";
  });
});
