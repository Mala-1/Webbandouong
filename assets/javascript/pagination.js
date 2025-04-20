// pagination.js

let paginationHandlerAttached = false;

function attachGlobalPaginationEvents() {
  if (paginationHandlerAttached) return; // Chặn gắn trùng
  paginationHandlerAttached = true;

  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".page-link-custom");
    const container = document.querySelector(".phantrang");

    if (btn && container && container.contains(btn)) {
      e.preventDefault();
      e.stopPropagation();

      const page = parseInt(btn.dataset.page || "1");
      const target = container.dataset.target || "pageproduct";

      console.log("[DEBUG] Delegated click:", page);

      document.dispatchEvent(new CustomEvent("pagination:change", {
        detail: { page, target }
      }));
    }
  }, true);

  document.addEventListener("keypress", function (e) {
    if (e.target && e.target.id === "pageInput" && e.key === "Enter") {
      e.preventDefault();
      const page = parseInt(e.target.value);
      const max = parseInt(e.target.max);
      const validPage = isNaN(page) || page < 1 ? 1 : (page > max ? max : page);

      const container = e.target.closest(".phantrang"); // 🔥 FIX: lấy đúng pagination tương ứng
      const target = container?.dataset.target || "pageproduct";

      document.dispatchEvent(new CustomEvent("pagination:change", {
        detail: { page: validPage, target }
      }));
    }
  });
}

// Gắn 1 lần duy nhất khi DOM ready
document.addEventListener("DOMContentLoaded", attachGlobalPaginationEvents);
