// Đọc dữ liệu từ file duLieuMau.txt và thêm vào giỏ hàng
document.getElementById('loadCartBtn').addEventListener('click', () => {
  fetch('duLieuMau.txt')
      .then(response => response.text())
      .then(text => {
          const lines = text.trim().split(/\r?\n/);
          let grandTotal = 0;
          const tbody = document.querySelector('#cartTable tbody');
          tbody.innerHTML = '';

          lines.forEach(line => {
              const [id, qty] = line.split(',').map(s => s.trim());
              if (!id || !qty) return;

              fetch(`cart.php?action=getProduct&id=${id}&qty=${qty}`)
                  .then(resp => resp.json())
                  .then(product => {
                      if (product.error) {
                          console.warn(product.error);
                          return;
                      }
                      const tr = document.createElement('tr');
                      tr.innerHTML = `
                          <td>${product.id}</td>
                          <td>${product.name}</td>
                          <td>${product.price.toLocaleString()}</td>
                          <td>${product.quantity}</td>
                          <td>${product.total.toLocaleString()}</td>
                      `;
                      tbody.appendChild(tr);

                      grandTotal += product.total;
                      document.getElementById('grandTotal').textContent = grandTotal.toLocaleString();
                  })
                  .catch(err => console.error('Lỗi khi lấy sản phẩm:', err));
          });
      })
      .catch(err => console.error('Không đọc được file duLieuMau.txt:', err));
});
