 <!-- JS Global Compulsory -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="<?php echo e(asset('client/vendor/jquery/dist/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/jquery-migrate/dist/jquery-migrate.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/popper.js/dist/umd/popper.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/bootstrap/bootstrap.min.js')); ?>"></script>

        <!-- JS Implementing Plugins -->
        <script src="<?php echo e(asset('client/vendor/appear.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/jquery.countdown.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/hs-megamenu/src/hs.megamenu.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/svg-injector/dist/svg-injector.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/jquery-validation/dist/jquery.validate.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/fancybox/jquery.fancybox.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/typed.js/lib/typed.min.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/slick-carousel/slick/slick.js')); ?>"></script>
        <script src="<?php echo e(asset('client/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')); ?>"></script>

        <!-- JS Electro -->
        <script src="<?php echo e(asset('client/js/hs.core.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.countdown.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.header.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.hamburgers.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.unfold.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.focus-state.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.malihu-scrollbar.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.validation.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.fancybox.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.onscroll-animation.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.slick-carousel.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.show-animation.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.svg-injector.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.go-to.js')); ?>"></script>
        <script src="<?php echo e(asset('client/js/components/hs.selectpicker.js')); ?>"></script>
        <!-- JS Plugins Init. -->
        <script>
            $(window).on('load', function() {
                // initialization of HSMegaMenu component
                $('.js-mega-menu').HSMegaMenu({
                    event: 'hover',
                    direction: 'horizontal',
                    pageContainer: $('.container'),
                    breakpoint: 767.98,
                    hideTimeOut: 0
                });

                // initialization of svg injector module
                $.HSCore.components.HSSVGIngector.init('.js-svg-injector');
            });



            $(document).on('ready', function() {
                // initialization of header
                $.HSCore.components.HSHeader.init($('#header'));

                // initialization of animation
                $.HSCore.components.HSOnScrollAnimation.init('[data-animation]');

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                    afterOpen: function() {
                        $(this).find('input[type="search"]').focus();
                    }
                });

                // initialization of popups
                $.HSCore.components.HSFancyBox.init('.js-fancybox');

                // initialization of countdowns
                var countdowns = $.HSCore.components.HSCountdown.init('.js-countdown', {
                    yearsElSelector: '.js-cd-years',
                    monthsElSelector: '.js-cd-months',
                    daysElSelector: '.js-cd-days',
                    hoursElSelector: '.js-cd-hours',
                    minutesElSelector: '.js-cd-minutes',
                    secondsElSelector: '.js-cd-seconds'
                });

                // initialization of malihu scrollbar
                $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

                // initialization of forms
                $.HSCore.components.HSFocusState.init();

                // initialization of form validation
                $.HSCore.components.HSValidation.init('.js-validate', {
                    rules: {
                        confirmPassword: {
                            equalTo: '#signupPassword'
                        }
                    }
                });

                // initialization of show animations
                $.HSCore.components.HSShowAnimation.init('.js-animation-link');

                // initialization of fancybox
                $.HSCore.components.HSFancyBox.init('.js-fancybox');

                // initialization of slick carousel
                $.HSCore.components.HSSlickCarousel.init('.js-slick-carousel');

                // initialization of go to
                $.HSCore.components.HSGoTo.init('.js-go-to');

                // initialization of hamburgers
                $.HSCore.components.HSHamburgers.init('#hamburgerTrigger');

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
                    beforeClose: function() {
                        $('#hamburgerTrigger').removeClass('is-active');
                    },
                    afterClose: function() {
                        $('#headerSidebarList .collapse.show').collapse('hide');
                    }
                });

                $('#headerSidebarList [data-toggle="collapse"]').on('click', function(e) {
                    e.preventDefault();

                    var target = $(this).data('target');

                    if ($(this).attr('aria-expanded') === "true") {
                        $(target).collapse('hide');
                    } else {
                        $(target).collapse('show');
                    }
                });

                // initialization of unfold component
                $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

                // initialization of select picker
                $.HSCore.components.HSSelectPicker.init('.js-select');
            });
        </script>



<script>


document.addEventListener('DOMContentLoaded', function () {
    const cartButton = document.getElementById('cart-button');
    const loginRequiredModal = document.getElementById('loginRequiredModal');
    const loginButton = document.getElementById('loginButton');
    const signupButton = document.getElementById('signupButton');
    const signupForm = document.getElementById('signup');
    const loginForm = document.getElementById('login');
    const sidebarToggler = document.getElementById('sidebarNavToggler');

    function closeLoginModal() {
        if (loginRequiredModal) {
            loginRequiredModal.style.display = 'none';
        }
    }

    function openLoginSidebar() {
        if (sidebarToggler) {
            sidebarToggler.click(); // Mở sidebar đăng nhập
        }
    }

    function showSignupForm() {
        if (signupForm) {
            signupForm.style.display = 'block';
            signupForm.style.opacity = '1';
        }
        if (loginForm) {
            loginForm.style.display = 'none';
            loginForm.style.opacity = '0';
        }
    }

    function showLoginForm() {
        if (loginForm) {
            loginForm.style.display = 'block';
            loginForm.style.opacity = '1';
        }
        if (signupForm) {
            signupForm.style.display = 'none';
            signupForm.style.opacity = '0';
        }
    }

    // Nút giỏ hàng
    if (cartButton) {
        cartButton.addEventListener('click', function (e) {
            e.preventDefault();

            fetch("{{ route('cart.index') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.status === 401) {
                    if (loginRequiredModal) {
                        loginRequiredModal.style.display = 'flex';
                    }
                } else {
                    window.location.href = "{{ route('cart.index') }}";
                }
            })
            .catch(error => {
                console.error('Lỗi fetch:', error);
            });
        });
    }

    // ✅ Nút Đăng nhập trong modal → HIỂN THỊ form đăng nhập
    if (loginButton) {
        loginButton.addEventListener('click', function () {
            closeLoginModal();
            setTimeout(() => {
                showLoginForm();       // ✅ dùng showLoginForm() chứ không phải showSignupForm()
                openLoginSidebar();
            }, 200);
        });
    }

    // ✅ Nút Đăng ký trong modal → HIỂN THỊ form đăng ký
    if (signupButton) {
        signupButton.addEventListener('click', function () {
            closeLoginModal();
            setTimeout(() => {
                showSignupForm();
                openLoginSidebar();
            }, 200);
        });
    }
});

</script>
<script>
    function closeLoginModal() {
        const modal = document.getElementById('loginRequiredModal');
        modal.style.display = 'none';
    }

    function openLoginModal() {
        const modal = document.getElementById('loginRequiredModal');
        modal.style.display = 'flex'; // bạn đang dùng flex trong CSS
    }

    // Có thể gọi openLoginModal() khi người dùng nhấn nút "Thêm vào giỏ"
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('hide');
            }, 3000);
        }
    });
</script>
<script>
    function deleteCartItem(key) {
        if (!confirm('Bạn có chắc muốn xoá sản phẩm này?')) return;

        fetch(`/cart/remove/${key}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Lỗi xoá sản phẩm.');
            return response.json();
        })
        .then(data => {
            location.reload(); // hoặc remove row DOM bằng JS nếu thích
        })
        .catch(error => {
            alert('Không thể xoá sản phẩm. Vui lòng thử lại.');
            console.error(error);
        });
    }
</script>



{{-- JS xử lý xoá từng sản phẩm bằng AJAX với SweetAlert2 và fadeOut --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-item')) {
                const button = e.target;
                const row = button.closest('tr');
                const variantId = row.dataset.variantId;

                Swal.fire({
                    title: 'Bạn chắc chắn muốn xoá?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const res = await fetch(`/cart/remove/${variantId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const data = await res.json();

                            if (data.success) {
                                row.remove();

                                // ✅ Cập nhật lại tổng tiền nếu có trả về
                                if (data.cart_total) {
                                    const totalInTable = document.getElementById('cart-total-in-table');
                                    if (totalInTable) {
                                        totalInTable.innerText = data.cart_total;
                                    }
                                }

                                // ✅ Cập nhật icon giỏ hàng ở header
                                if (typeof updateCartIcon === 'function') {
                                    updateCartIcon();
                                }

                                // Nếu không còn sản phẩm nào
                                const tbody = document.getElementById('cart-body');
                                if (!tbody.querySelector('tr[data-variant-id]')) {
                                    tbody.innerHTML =
                                        `<tr><td colspan="6"><div class="alert alert-info mb-0">Giỏ hàng của bạn đang trống.</div></td></tr>`;
                                }
                            }

                        } catch (err) {
                            console.error(err);
                            Swal.fire('Lỗi', 'Không thể xoá sản phẩm', 'error');
                        }
                    }
                });
            }
        });


        // Xoá toàn bộ giỏ hàng
        async function clearCart() {
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Tất cả sản phẩm trong giỏ hàng sẽ bị xoá!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xoá toàn bộ',
                cancelButtonText: 'Huỷ'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch('{{ route('cart.clearAjax') }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await res.json();
                        if (data.success) {
                            const tbody = document.getElementById('cart-body');
                            const tfoot = document.querySelector('tfoot');

                            if (tbody) {
                                tbody.style.transition = 'opacity 0.5s ease-out';
                                tbody.style.opacity = 0;
                            }
                            if (tfoot) {
                                tfoot.style.transition = 'opacity 0.5s ease-out';
                                tfoot.style.opacity = 0;
                            }

                            setTimeout(() => {
                                if (tbody) {
                                    tbody.innerHTML =
                                        `<tr><td colspan="6"><div class="alert alert-info mb-0">Giỏ hàng của bạn đang trống.</div></td></tr>`;
                                    tbody.style.opacity = 1;
                                }
                                if (tfoot) tfoot.remove();

                                // ✅ Cập nhật icon giỏ hàng trên header
                                updateCartIcon();

                                Swal.fire('Đã xoá!', 'Giỏ hàng đã được xoá.', 'success');
                            }, 500);
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire('Lỗi!', 'Không thể xoá giỏ hàng.', 'error');
                    }
                }
            });
        }
    </script>
    <script>
        const updateCartUrl = "{{ route('cart.updateItem') }}";
        const csrfToken = "{{ csrf_token() }}";

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.update-quantity').forEach(input => {
                input.addEventListener('change', async function() {
                    const variantId = this.dataset.variantId;
                    const quantity = parseInt(this.value);

                    try {
                        const res = await fetch(updateCartUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                variant_id: variantId,
                                quantity
                            })
                        });

                        const data = await res.json();

                        if (data.success) {
                            const row = this.closest('tr');
                            const itemTotalEl = row.querySelector('.item-total');
                            const cartTotalHeaderEl = document.getElementById(
                            'cart-total'); // trên header
                            const cartTotalTableEl = document.getElementById(
                                'cart-total-in-table'); // trong bảng

                            if (itemTotalEl) itemTotalEl.innerText = data.item_total;
                            if (cartTotalHeaderEl) cartTotalHeaderEl.innerText = data
                            .cart_total;
                            if (cartTotalTableEl) cartTotalTableEl.innerText = data.cart_total;

                            // Cập nhật icon giỏ hàng (số lượng, tổng tiền)
                            if (typeof updateCartIcon === 'function') {
                                updateCartIcon();
                            }
                        } else {
                            console.error('Update failed:', data);
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                    }
                });
            });
        });
    </script>

    <script>
        async function updateCartIcon() {
            try {
                const res = await fetch("{{ route('cart.summary') }}");
                const data = await res.json();

                if (data.success) {
                    // Cập nhật icon giỏ hàng
                    document.getElementById('cart-count').innerText = data.total_quantity;

                    // Cập nhật tổng tiền ở header
                    document.getElementById('cart-total').innerText = data.total_amount;

                    // 🔁 Nếu có thẻ "Tổng cộng" trong bảng giỏ hàng
                    const cartTotalElement = document.querySelector('#cart-total-in-table');
                    if (cartTotalElement) {
                        cartTotalElement.innerText = data.total_amount;
                    }
                }
            } catch (err) {
                console.error('Không thể cập nhật giỏ hàng:', err);
            }
        }


        // Tự động cập nhật khi trang load
        document.addEventListener("DOMContentLoaded", updateCartIcon);
    </script>




