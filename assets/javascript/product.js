

// Thêm active khi click vào thể loại
document.querySelectorAll('.category').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.category').forEach(i => { i.classList.remove('active') });
        item.classList.add('active');
        loadBrandsByCategory();
    });
});

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

document.addEventListener('DOMContentLoaded', function () {
    updateScrollButtons();
    window.addEventListener('resize', updateScrollButtons);
    window.addEventListener('resize', loadProductsByCategory());
});

document.querySelector('.brand-wrap').addEventListener('click', function (e) {
    if (e.target.classList.contains('brand-option')) {
        e.target.classList.toggle('active');
    }
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".filter-option").forEach(item => {
        item.addEventListener("click", () => {
            item.classList.toggle("active");
        });
    });

    document.querySelectorAll(".filter-group").forEach(group => {
        const type = group.getAttribute("data-type");

        group.querySelectorAll(".filter-option").forEach(option => {
            option.addEventListener("click", () => {
                if (type === "single") {
                    group.querySelectorAll(".filter-option").forEach(o => o.classList.remove("active"));
                    option.classList.add("active");
                } else {
                    option.classList.toggle("active");
                }
            });
        });
    });
});

document.querySelector('.btn-reset-filters').addEventListener('click', function () {
    // Bỏ tất cả class 'active' trong form lọc
    document.querySelectorAll('.active').forEach(el => {
        el.classList.remove('active');
    });

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

    fetch('../ajax/product_ajax.php?category_id=' + categoryId +'&limit=' + limit + '&page=' + page)
        .then(response => response.text())
        .then(data => {
            const parts = data.split('SPLIT');
            const html = parts[0] || ''; // nếu không có thì dùng chuỗi rỗng
            const pagination = parts[1] || ''; // nếu không có thì dùng chuỗi rỗng
            productWrap.innerHTML = html;
            if (pagination !== '') {
                paginationWrap.innerHTML = pagination;
                // if (typeof initPaginationEvents === "function") {
                //     initPaginationEvents(); // Gắn lại sự kiện pagination sau khi render
                // }
            }
        })
        .catch(err => console.error('Lỗi:', err));
}


function loadBrandsByCategory() {
    const categoryId = getActiveCategoryId();

    fetch('../ajax/brand_ajax.php?category_id=' + categoryId)
        .then(response => response.text())
        .then(data => {
            console.log(data)
            const brandWrap = document.querySelector('.brand-wrap');
            brandWrap.innerHTML = data;
        })
        .catch(err => console.error('Lỗi:', err));
}

document.addEventListener("DOMContentLoaded", function () {
    loadProductsByCategory();
    loadBrandsByCategory();
});


document.querySelectorAll('.category').forEach(item => {
    item.addEventListener('click', () => {
        // Bỏ active cũ
        document.querySelectorAll('.category').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
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
