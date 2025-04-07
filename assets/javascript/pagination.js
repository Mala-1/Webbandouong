// pagination.js

function initPaginationEvents() {
    const container = document.querySelector(".phantrang");
    if (!container) return;
  
    container.querySelectorAll(".page-link-custom").forEach(link => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        const page = parseInt(this.dataset.page);
        const target = container.dataset.target || "pageproduct";
  
        document.dispatchEvent(new CustomEvent("pagination:change", {
          detail: { page, target }
        }));
      });
    });
  
    const input = container.querySelector("#pageInput");
    if (input) {
      input.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
          e.preventDefault();
          let page = parseInt(this.value);
          const max = parseInt(this.max);
          if (isNaN(page) || page < 1) page = 1;
          if (page > max) page = max;
  
          const target = container.dataset.target || "pageproduct";
  
          document.dispatchEvent(new CustomEvent("pagination:change", {
            detail: { page, target }
          }));
        }
      });
    }
  }
  
  // Tự động kích hoạt sau khi DOM load hoặc sau khi innerHTML render lại
  function autoInitPagination() {
    initPaginationEvents();
    const observer = new MutationObserver(() => {
      initPaginationEvents();
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }
  
  document.addEventListener("DOMContentLoaded", autoInitPagination);
  