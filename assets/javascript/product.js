
// nút qua trái phải của thể loại
function scrollCategories(direction) {
    const container = document.querySelector('.category-scroll');
    container.style.scrollBehavior = 'smooth';
    const scrollAmount = 150; // chỉnh khoảng cách cuộn
    container.scrollLeft += direction * scrollAmount;
}

// kéo scroll
const slider = document.querySelector('.category-scroll');
let isDown = false;
let startX;
let scrollLeft;

slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('active');
    slider.style.scrollBehavior = 'auto';
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
});

slider.addEventListener('mouseleave', () => {
    isDown = false;
});

slider.addEventListener('mouseup', () => {
    isDown = false;
});

slider.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1; // tốc độ kéo
    slider.scrollLeft = scrollLeft - walk;
});

document.querySelector('.category-scroll').addEventListener('scroll', updateScrollButtons);

// Xử lý ẩn nút left right của thể loại
function updateScrollButtons() {
    const container = document.querySelector('.category-scroll');
    const btnLeft = document.querySelector('.scroll-left');
    const btnRight = document.querySelector('.scroll-right');


    const scrollLeft = container.scrollLeft;
    const maxScrollLeft = container.scrollWidth - container.clientWidth;

    if (container.scrollWidth <= container.clientWidth) {
        btnLeft.style.display = 'none';
        btnRight.style.display = 'none';
    } else {
        btnLeft.style.display = scrollLeft > 0 ? 'flex' : 'none';
        btnRight.style.display = scrollLeft < maxScrollLeft ? 'flex' : 'none';
    }
}

// khi load dom xong thì hiển thị nút trái phải của thể loại 
// + load sản phẩm theo thể loại
document.addEventListener('DOMContentLoaded', function () {
    updateScrollButtons();
    window.addEventListener('resize', updateScrollButtons);
    window.addEventListener('resize', loadProductsByCategory());
});




function getActiveCategoryId() {
    const activeItem = document.querySelector('.category.active');
    return activeItem ? activeItem.getAttribute('data-category-id') : 1;
}

function loadProductsByCategory(page = 1) {
    const categoryId = getActiveCategoryId();
    const limit = getResponsiveLimit();
    const productWrap = document.querySelector('.product-wrap');
    const paginationWrap = document.querySelector('.pagination-wrap');

    const selectedSort = document.querySelector('.filter-group[data-type="single"] .filter-option.active')?.dataset.sort || '';
    const selectedBrands = Array.from(document.querySelectorAll('.brand-wrap .brand-option.active')).map(img => img.getAttribute('data-brand-id') || '');
    const selectedPackaging = Array.from(document.querySelectorAll('.packaging_type-wrap .filter-option.active')).map(el => el.getAttribute('data-packaging-type') || '');
    const selectedSizes = Array.from(document.querySelectorAll('.size-wrap .filter-option.active')).map(el => el.getAttribute('data-size') || '');


    // ✅ Chuyển mảng sang chuỗi để gửi qua GET (dùng encodeURIComponent để an toàn)
    const brandsParam = encodeURIComponent(JSON.stringify(selectedBrands));
    const packagingParam = encodeURIComponent(JSON.stringify(selectedPackaging));
    const sizesParam = encodeURIComponent(JSON.stringify(selectedSizes));
    const sortParam = encodeURIComponent(selectedSort);

    const url = `../ajax/product_ajax.php?category_id=${categoryId}&limit=${limit}&page=${page}&sort=${sortParam}&brands=${brandsParam}&packaging=${packagingParam}&sizes=${sizesParam}`;

    fetch(url)
        .then(response => response.text())
        .then(data => {
            const parts = data.split('SPLIT');
            const html = parts[0] || ''; // nếu không có thì dùng chuỗi rỗng
            const pagination = parts[1] || ''; // nếu không có thì dùng chuỗi rỗng
            productWrap.innerHTML = html;
            paginationWrap.innerHTML = pagination;

        })
        .catch(err => console.error('Lỗi:', err));
}



document.querySelectorAll('.category').forEach(item => {
    item.addEventListener('click', () => {
        // Bỏ active cũ
        document.querySelectorAll('.category').forEach(i => i.classList.remove('active'));
        item.classList.add('active');

        // reset lọc
        document.getElementById('advancedFilterModal').querySelectorAll('.active').forEach(el => {
            el.classList.remove('active');
        });

        loadProductsByCategory();

        // Cập nhật span
        const name = item.getAttribute('data-name');
        document.getElementById('categoryNameSpan').textContent = name;
    });
});

document.addEventListener("pagination:change", function (e) {
    const { page, target } = e.detail;

    if (target === "pageproduct") {
        loadProductsByCategory(page);
    }

    // Add more targets as needed
});

// từ kích thước màn hình lấy số lưuognj sản phẩmphẩm
function getResponsiveLimit() {
    const width = window.innerWidth;

    if (width < 768) return 6; // Mobile nhỏ
    if (width < 1200) return 8; // Tablet
    return 10; // Desktop
}


// hàm gán lại sự kiện cho các btn trong bộ lọc
function attachFilterEvents() {

    // Thêm sự kiện gán class active khi click vào thương hiệu trong bộ lọc
    document.querySelector('.brand-wrap').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('brand-option')) {
            e.target.classList.toggle('active');
            console.log('click brand');
        }
    });

    // Gắn sự kiện cho từng nhóm lọc (dùng event delegation)
    document.querySelectorAll(".filter-group").forEach(group => {
        const type = group.getAttribute("data-type"); // single / multiple

        group.addEventListener("click", (e) => {
            const option = e.target.closest(".filter-option");
            if (!option) return; // click không phải vào .filter-option thì bỏ qua

            if (type === "single") {
                // chỉ được chọn 1 => bỏ active các option khác
                group.querySelectorAll(".filter-option").forEach(o => o.classList.remove("active"));
                option.classList.add("active");
            } else {
                // chọn nhiều => toggle
                option.classList.toggle("active");
            }
        });
    });

    // Gắn sự kiện reset trong modal (giữ nguyên vì hợp lý)
    const modal = document.getElementById('advancedFilterModal');
    const resetBtn = modal.querySelector('.btn-reset-filters');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            modal.querySelectorAll('.active').forEach(el => {
                el.classList.remove('active');
            });
        });
    }


    document.querySelector('.btn-filter').addEventListener('click', function () {
        loadProductsByCategory();

        // Đóng modal Bootstrap
        const modalEl = document.getElementById('advancedFilterModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

    });
}

document.addEventListener('DOMContentLoaded', function () {
    attachFilterEvents();
});

let loadedCategoryId = null;

function load_filter_options() {
    const categoryId = getActiveCategoryId();
    if (loadedCategoryId === categoryId) {
        console.log(`Filter options for category ${categoryId} đã được load rồi.`);
        return;
    }
    fetch('../ajax/load_filter_options.php?category_id=' + categoryId)
        .then(response => response.text())
        .then(data => {
            let [brandImageHtml, packagingTypeHtml, sizeHtml] = data.split('SPLIT');
            document.querySelector('.brand-wrap').innerHTML = brandImageHtml;
            document.querySelector('.packaging_type-wrap').innerHTML = packagingTypeHtml;
            document.querySelector('.size-wrap').innerHTML = sizeHtml;
            
            loadedCategoryId = categoryId;
        })
        .catch(err => console.error('Lỗi:', err));
}

document.getElementById('advancedFilterModal').addEventListener('show.bs.modal', function () {
    load_filter_options();
});