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

    // Lưu lựa chọn
    selectedOptions[attrId] = optId;

    // Reset style cho các option cùng thuộc tính
    document.querySelectorAll(`.option-box[data-attribute="${attrId}"]`)
        .forEach(box => box.classList.remove('border-primary', 'selected'));

    el.classList.add('border-primary', 'selected');

    // Tìm biến thể khớp VÀ còn hàng
    let matchedVariant = null;
    for (const variant of variantCombinations) {
        const isMatch = Object.entries(selectedOptions).every(
            ([aId, oId]) => String(variant.options[aId]) === String(oId)
        );
        if (isMatch &&
            Object.keys(variant.options).length === Object.keys(selectedOptions).length &&
            variant.stock_quantity > 0
        ) {
            matchedVariant = variant;
            break;
        }
    }

    if (matchedVariant) {
        selectedVariantId = matchedVariant.id;
        selectedPrice = parseFloat(matchedVariant.price);
        document.getElementById('selected-variant-id').value = selectedVariantId;
        document.getElementById('currentPrice').textContent =
            selectedPrice.toLocaleString('vi-VN') + ' ₫';
    } else {
        selectedVariantId = null;
        document.getElementById('selected-variant-id').value = '';
    }

    // Auto chọn nếu chỉ còn 1 biến thể khả dụng
    const possibleVariants = variantCombinations.filter(v =>
        Object.entries(selectedOptions).every(([sAttrId, sOptId]) =>
            String(v.options[sAttrId]) === String(sOptId)
        ) &&
        v.stock_quantity > 0
    );

    if (possibleVariants.length === 1) {
        const autoVariant = possibleVariants[0];
        for (const [autoAttrId, autoOptId] of Object.entries(autoVariant.options)) {
            if (!selectedOptions[autoAttrId]) {
                selectedOptions[autoAttrId] = parseInt(autoOptId);
                const autoEl = document.querySelector(
                    `.option-box[data-attribute="${autoAttrId}"][data-option="${autoOptId}"]`
                );
                if (autoEl) autoEl.classList.add('border-primary', 'selected');
            }
        }
        selectedVariantId = autoVariant.id;
        selectedPrice = parseFloat(autoVariant.price);
        document.getElementById('selected-variant-id').value = selectedVariantId;
        document.getElementById('currentPrice').textContent =
            selectedPrice.toLocaleString('vi-VN') + ' ₫';
    }

    updateAvailableOptions();
    checkVariantSelection();
    updateSelectedOptionsDisplay();
}

function updateAvailableOptions() {
    // Tìm ID thuộc tính "Màu sắc" (nếu có)
    let colorAttributeId = null;
    for (const [attrId, attribute] of Object.entries(attributeOptionsWithPrices)) {
        if (attribute.name && attribute.name.trim().toLowerCase() === 'màu sắc') {
            colorAttributeId = attrId;
            break;
        }
    }

    const attributes = Object.keys(attributeOptionsWithPrices);

    for (const attrId of attributes) {
        document.querySelectorAll(`.option-box[data-attribute="${attrId}"]`).forEach(optionEl => {
            const currentOptId = parseInt(optionEl.dataset.option);

            let shouldBeDisabled = false;
            if (selectedOptions[attrId] !== undefined && selectedOptions[attrId] !== currentOptId) {
                shouldBeDisabled = true;
            }

            let isValidByCompatibility = false;
            const isColorAttribute = (colorAttributeId !== null && attrId == colorAttributeId);

            if (isColorAttribute) {
                // Chỉ disable màu nếu TẤT CẢ biến thể cùng màu đều hết hàng
                const allVariantsOfColor = variantCombinations.filter(variant =>
                    variant.options &&
                    String(variant.options[attrId]) === String(currentOptId)
                );
                isValidByCompatibility = allVariantsOfColor.some(variant => variant.stock_quantity > 0);
            } else {
                // Các thuộc tính khác: disable nếu không còn biến thể phù hợp + còn hàng
                const relevantSelectedOptions = {};
                for (const key in selectedOptions) {
                    if (parseInt(key) !== parseInt(attrId)) {
                        relevantSelectedOptions[key] = selectedOptions[key];
                    }
                }

                const filteredVariants = variantCombinations.filter(variant =>
                    Object.entries(relevantSelectedOptions).every(([sAttrId, sOptId]) =>
                        String(variant.options[sAttrId]) === String(sOptId)
                    )
                );

                isValidByCompatibility = filteredVariants.some(variant =>
                    String(variant.options[attrId]) === String(currentOptId) &&
                    variant.stock_quantity > 0
                );
            }

            // Apply trạng thái
            if (shouldBeDisabled || !isValidByCompatibility) {
                optionEl.classList.add('disabled-option');
                optionEl.style.pointerEvents = 'none';
                optionEl.style.opacity = 0.3;
            } else {
                optionEl.classList.remove('disabled-option');
                optionEl.style.pointerEvents = 'auto';
                optionEl.style.opacity = 1;
            }
        });
    }
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

    // Chọn icon dựa trên type
    let icon = '';
    if (type === 'success') {
        icon = '✅';
    } else if (type === 'error') {
        icon = '❌';
    } else if (type === 'warning') {
        icon = '⚠️';
    } else {
        icon = 'ℹ️';
    }

    const toastHtml = `
    <div id="${toastId}" class="toast-container position-fixed" style="top: 80px; right: 0; z-index: 1055;">
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 fade show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <span style="font-size: 16px; margin-right: 8px;">${icon}</span>${message}
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

async function buyNow() {
    const variantId = selectedVariantId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!variantId || Object.keys(selectedOptions).length < totalAttributes) {
        showToast('Cảnh báo', 'Vui lòng chọn đầy đủ các phiên bản sản phẩm!', 'error');
        return;
    }

    try {
        const response = await fetch(window.cartBuyNowUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                variant_id: variantId,
                quantity: 1
            })
        });

        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const data = await response.json();

            if (data.status === 'success') {
                // Cập nhật số lượng giỏ hàng trong header
                if (document.getElementById('cart-count')) {
                    document.getElementById('cart-count').textContent = data.total_quantity;
                }
                if (document.getElementById('cart-total')) {
                    document.getElementById('cart-total').textContent = data.total_amount;
                }

                showToast('Thành công', data.message, 'success');

                // Chuyển hướng sau 400ms
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 400);
            } else {
                showToast('Lỗi', data.message || 'Mua ngay thất bại!', 'error');
            }
        } else {
            showToast('Lỗi', 'Phản hồi không hợp lệ từ máy chủ!', 'error');
        }
    } catch (error) {
        showToast('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại!', 'error');
    }
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
let isPlacingOrder = false;
