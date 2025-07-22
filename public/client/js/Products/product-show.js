// Sticky-action-bar và các chức năng sản phẩm

// Lấy các biến toàn cục từ window (được gán trong Blade)
const variantCombinations = window.variantsForJs;
const totalAttributes = window.totalAttributes;
const ramAttrId = window.ramAttrId;
const attributeOptionsWithPrices = window.attributeOptionsWithPrices;

const thumbnailContainer = document.getElementById('thumbnailContainer');
let selectedOptions = {};
let selectedVariantId = null;
let selectedPrice = window.selectedPrice || 0;

function selectOption(el) {
    const attrId = el.dataset.attribute;
    const optId = parseInt(el.dataset.option);

    selectedOptions[attrId] = optId;

    document.querySelectorAll(`.option-box[data-attribute="${attrId}"]`).forEach(box => {
        box.classList.remove('border-primary', 'bg-primary', 'text-white', 'selected');
    });

    el.classList.add('border-primary', 'selected');

    let matchedVariant = null;
    for (const variant of variantCombinations) {
        let isMatch = true;
        for (const [aId, oId] of Object.entries(selectedOptions)) {
            if (parseInt(variant.options[aId]) !== parseInt(oId)) {
                isMatch = false;
                break;
            }
        }

        if (isMatch && Object.keys(variant.options).length === Object.keys(selectedOptions).length) {
            matchedVariant = variant;
            break;
        }
    }

    if (matchedVariant) {
        selectedVariantId = matchedVariant.id;
        selectedPrice = parseFloat(matchedVariant.price);
        document.getElementById('selected-variant-id').value = selectedVariantId;
        document.getElementById('currentPrice').textContent = selectedPrice.toLocaleString('vi-VN') + ' ₫';
    } else {
        selectedVariantId = null;
        document.getElementById('selected-variant-id').value = '';
    }

    updateAvailableOptions();
    checkVariantSelection();
    updateSelectedOptionsDisplay();
}

function checkVariantSelection() {
    const actionBar = document.getElementById('sticky-action-bar');
    const isAllSelected = Object.keys(selectedOptions).length === totalAttributes && selectedVariantId;
    if (isAllSelected) {
        updateStickyBar();
        actionBar.style.display = 'flex';
    } else {
        actionBar.style.display = 'none';
    }
    updateSelectedOptionsDisplay();
}

function updateStickyBar() {
    if (!selectedVariantId) return;
    const variant = variantCombinations.find(v => v.id === selectedVariantId);
    if (!variant) return;
    const priceEls = document.querySelectorAll('#sticky-action-bar .price');
    priceEls.forEach(priceEl => {
        priceEl.textContent = parseFloat(variant.price).toLocaleString('vi-VN') + ' ₫';
    });

    // Cập nhật số lượng tồn kho
    const stockEl = document.getElementById('sticky-bar-stock');
    if (stockEl) {
        stockEl.textContent = ` - Kho: ${variant.stock_quantity}`;
    }

    updateSelectedOptionsDisplay();
}

function updateSelectedOptionsDisplay() {
    const selectedOptionsEl = document.getElementById('sticky-selected-options');
    if (!selectedOptionsEl) return;
    const selectedOptionsText = [];
    for (const [attrId, optId] of Object.entries(selectedOptions)) {
        const attribute = attributeOptionsWithPrices[attrId];
        if (attribute) {
            const option = attribute.options[optId];
            if (option) {
                selectedOptionsText.push(option.value);
            }
        }
    }
    if (selectedOptionsText.length > 0) {
        selectedOptionsEl.textContent = selectedOptionsText.join(' • ');
        selectedOptionsEl.style.display = 'block';
    } else {
        selectedOptionsEl.style.display = 'none';
    }
}

function updateAvailableOptions() {
    const attributes = Object.keys(attributeOptionsWithPrices);
    for (const attrId of attributes) {
        document.querySelectorAll(`.option-box[data-attribute="${attrId}"]`).forEach(optionEl => {
            const currentOptId = parseInt(optionEl.dataset.option);
            const simulatedSelection = {
                ...selectedOptions,
                [attrId]: currentOptId
            };
            const isValid = variantCombinations.some(variant => {
                return Object.entries(simulatedSelection).every(([aId, oId]) =>
                    variant.options[aId] && parseInt(variant.options[aId]) === parseInt(oId)
                );
            });
            if (isValid) {
                optionEl.classList.remove('disabled-option');
                optionEl.style.pointerEvents = 'auto';
                optionEl.style.opacity = 1;
            } else {
                optionEl.classList.add('disabled-option');
                optionEl.style.pointerEvents = 'none';
                optionEl.style.opacity = 0.3;
            }
        });
    }
}

async function addToCart() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const productId = window.productId;
    if (!selectedVariantId || Object.keys(selectedOptions).length < totalAttributes) {
        showToast('Cảnh báo', 'Vui lòng chọn đầy đủ các phiên bản sản phẩm!', 'error');
        return;
    }
    try {
        const response = await fetch(window.cartAddUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                product_id: productId,
                variant_id: selectedVariantId,
                quantity: 1
            })
        });
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            if (data.status === 'success') {
                if (document.getElementById('cart-count')) {
                    document.getElementById('cart-count').textContent = data.total_quantity;
                }
                if (document.getElementById('cart-total')) {
                    document.getElementById('cart-total').textContent = data.total_amount;
                }
                showToast('Thành công', 'Đã thêm sản phẩm vào giỏ hàng!', 'success');
            } else {
                showToast('Lỗi', data.message || 'Thêm vào giỏ hàng thất bại!', 'error');
            }
        } else {
            const raw = await response.text();
            showToast('Lỗi', 'Phản hồi không hợp lệ từ máy chủ!', 'error');
        }
    } catch (error) {
        showToast('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
    }
}

function showToast(title, message, type = 'success') {
    const toastId = 'custom-toast-' + Date.now();
    const toastHtml = `
    <div id="${toastId}" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 fade show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) toast.remove();
    }, 3000);
}

function buyNow() {
    const variantId = selectedVariantId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (!variantId) {
        Swal.fire({
            icon: 'warning',
            title: 'Thiếu thông tin',
            text: 'Vui lòng chọn đầy đủ các phiên bản sản phẩm trước khi mua!',
            confirmButtonText: 'Đã hiểu',
            confirmButtonColor: '#3085d6'
        });
        return;
    }
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.cartBuyNowUrl;
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = csrfToken;
    form.appendChild(csrf);
    const variantInput = document.createElement('input');
    variantInput.type = 'hidden';
    variantInput.name = 'variant_id';
    variantInput.value = variantId;
    form.appendChild(variantInput);
    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = 'quantity';
    qtyInput.value = 1;
    form.appendChild(qtyInput);
    document.body.appendChild(form);
    form.submit();
}

function scrollThumbnails(direction) {
    const container = document.getElementById('thumbnailContainer');
    const scrollAmount = 108;
    container.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}

function changeImage(el, url) {
    document.getElementById('mainImage').src = url;
    document.querySelectorAll('#thumbnailContainer img').forEach(img => {
        img.classList.remove('border-primary');
    });
    el.classList.add('border-primary');
    const container = document.getElementById('thumbnailContainer');
    const thumbnails = Array.from(container.querySelectorAll('img'));
    const currentIndex = thumbnails.indexOf(el);
    if (currentIndex !== -1 && currentIndex + 1 < thumbnails.length) {
        const nextImg = thumbnails[currentIndex + 1];
        const containerRect = container.getBoundingClientRect();
        const nextImgRect = nextImg.getBoundingClientRect();
        if (nextImgRect.right > containerRect.right) {
            nextImg.scrollIntoView({
                behavior: 'smooth',
                inline: 'end',
                block: 'nearest'
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const options = document.querySelectorAll('.variant-option');
    options.forEach(option => {
        option.addEventListener('change', checkVariantSelection);
    });
    checkVariantSelection();
    // Reset button
    const resetBtn = document.getElementById('reset-options');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            selectedOptions = {};
            selectedVariantId = null;
            document.querySelectorAll('.option-box').forEach(box => {
                box.classList.remove('border-primary', 'selected');
            });
            const defaultPrice = document.getElementById('default-price').textContent;
            document.getElementById('currentPrice').textContent = defaultPrice;
            document.getElementById('selected-variant-id').value = '';
            updateAvailableOptions();
            const selectedOptionsEl = document.getElementById('sticky-selected-options');
            if (selectedOptionsEl) {
                selectedOptionsEl.textContent = '';
                selectedOptionsEl.style.display = 'none';
            }
            checkVariantSelection();
        });
    }
});

// Ẩn sticky-action-bar khi lăn chuột, hiện lại khi dừng lăn
let stickyBarTimeout = null;
window.addEventListener('scroll', function() {
    const actionBar = document.getElementById('sticky-action-bar');
    if (actionBar && actionBar.style.display === 'flex') {
        actionBar.style.opacity = '0';
        actionBar.style.pointerEvents = 'none';
    }
    clearTimeout(stickyBarTimeout);
    stickyBarTimeout = setTimeout(() => {
        if (actionBar && actionBar.style.display === 'flex') {
            actionBar.style.opacity = '1';
            actionBar.style.pointerEvents = 'auto';
        }
    }, 300);
}); 