@include('client.layouts.partials.head')

<body style="background-color:#fff; gap: 16px">
    @include('client.layouts.partials.header')

    {{-- Banner (nằm ngoài container) --}}
    @hasSection('banner')
        @yield('banner')
    @endif

    {{-- Sidebar + Content trong container --}}
      @hasSection('sidebar')
            @yield('sidebar')
        @endif

    <div class="container">

        @yield('content')

    </div>

    <!-- Modal yêu cầu đăng nhập -->
    <div id="loginRequiredModal" class="login-modal" style="display: none;">
        <div class="login-modal-content">
            <button class="modal-close" onclick="closeLoginModal()">×</button>

            <h4 class="modal-title">Smember</h4>
            <div class="my-3">
                <img src="{{ asset('client/img/1920X422/e4cadae387eefc6719eee8f293b91d63-removebg-preview.png') }}"
                    alt="Image Description" class="img-fluid mx-auto d-block" style="max-width: 120px;">
            </div>

            <p class="modal-text">Vui lòng đăng nhập tài khoản Smember để xem ưu đãi và thanh toán dễ dàng hơn.</p>
            <div class="modal-buttons">
                <a id="signupButton" href="javascript:;" class=" btn-danger-gradient">Đăng ký</a>

                <a id="loginButton" href="javascript:;" class="btn-danger-gradient">Đăng nhập</a>

            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 z-index-9999"
            role="alert" style="min-width: 300px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('message'))
        <div class="alert alert-info alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3 z-index-9999"
            role="alert" style="min-width: 300px;">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    @hasSection('footer')
        @yield('footer')
    @endif
    {{-- @include('client.layouts.partials.footer') --}}
    <!-- ========== END FOOTER ========== -->

    <!-- ========== SECONDARY CONTENTS ========== -->
    <!-- Account Sidebar Navigation -->
    <aside id="sidebarContent" class="u-sidebar u-sidebar__lg" aria-labelledby="sidebarNavToggler">
        <div class="u-sidebar__scroller">
            <div class="u-sidebar__container">
                <div class="js-scrollbar u-header-sidebar__footer-offset pb-3">
                    <!-- Toggle Button -->
                    <div class="d-flex align-items-center pt-4 px-7">
                        <button type="button" class="close ml-auto" aria-controls="sidebarContent" aria-haspopup="true"
                            aria-expanded="false" data-unfold-event="click" data-unfold-hide-on-scroll="false"
                            data-unfold-target="#sidebarContent" data-unfold-type="css-animation"
                            data-unfold-animation-in="fadeInRight" data-unfold-animation-out="fadeOutRight"
                            data-unfold-duration="500">
                            <i class="ec ec-close-remove"></i>
                        </button>
                    </div>
                    <!-- End Toggle Button -->

                    <!-- Content -->
                    <div class="js-scrollbar u-sidebar__body">
                        <div class="u-sidebar__content u-header-sidebar__content">
                            <!-- Login -->
                            <form method="POST" action="<?php echo e(route('login')); ?>" class="js-validate">
                                <?php echo csrf_field(); ?>
                                <div id="login" data-target-group="idForm">
                                    <!-- Title -->
                                    <header class="text-center mb-7">
                                        <h2 class="h4 mb-0">Welcome Back!</h2>
                                        <p>Login to manage your account.</p>
                                    </header>
                                    <!-- End Title -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <div class="js-form-message js-focus-state">
                                            <div class="form-group-email mb-2">
                                                <div class="input-icon">
                                                    <label class="sr-only" for="signinEmail">Email</label>
                                                    <i class="fas fa-envelope"></i>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Email" value="<?php echo e(old('email')); ?>">
                                                </div>
                                                <div>
                                                    <?php $__errorArgs = ['email'];
                                                            $__bag = $errors->getBag($__errorArgs[1] ?? 'default');
                                                            if ($__bag->has($__errorArgs[0])) :
                                                            if (isset($message)) { $__messageOriginal = $message; }
                                                            $message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="text-danger"><?php echo e($message); ?></span>
                                                    <?php unset($message);
                                                            if (isset($__messageOriginal)) { $message = $__messageOriginal; }
                                                            endif;
                                                            unset($__errorArgs, $__bag);
                                                        ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Group -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <div class="js-form-message js-focus-state">
                                            <div class="form-group-password mb-2">
                                                <div class="input-icon">
                                                    <div class="custom-input-wrapper">
                                                        <label class="sr-only" for="passwordInput">Password</label>

                                                        <!-- Icon ổ khóa bên trái -->
                                                        <i class="fas fa-lock lock-icon"></i>

                                                        <!-- Input mật khẩu -->
                                                        <input type="password" class="form-control custom-input"
                                                            name="password" placeholder="Password" id="passwordInput">

                                                        <button type="button" class="toggle-btn" id="togglePassword">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <?php $__errorArgs = ['password'];
                                                        $__bag = $errors->getBag($__errorArgs[1] ?? 'default');
                                                        if ($__bag->has($__errorArgs[0])) :
                                                        if (isset($message)) { $__messageOriginal = $message; }
                                                        $message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
                                                        if (isset($__messageOriginal)) { $message = $__messageOriginal; }
                                                        endif;
                                                        unset($__errorArgs, $__bag);
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Group -->

                                    <div class="d-flex justify-content-end mb-4">
                                        <a class="js-animation-link small link-muted" href="javascript:;"
                                            data-target="#forgotPassword" data-link-group="idForm"
                                            data-animation-in="slideInUp">Forgot Password?</a>
                                    </div>

                                    <div class="mb-2">
                                        <button type="submit"
                                            class="btn btn-block btn-sm btn-primary transition-3d-hover">Login</button>
                                    </div>

                                    <div class="text-center mb-4">
                                        <span class="small text-muted">Do not have an account?</span>
                                        <a class="js-animation-link small text-dark" href="javascript:;"
                                            data-target="#signup" data-link-group="idForm"
                                            data-animation-in="slideInUp">Signup
                                        </a>
                                    </div>

                                    <div class="text-center">
                                        <span class="u-divider u-divider--xs u-divider--text mb-4">OR</span>
                                    </div>

                                    <!-- Login Buttons -->
                                    <div class="d-flex">
                                        <a class="btn btn-block btn-sm btn-soft-facebook transition-3d-hover mr-1"
                                            href="#">
                                            <span class="fab fa-facebook-square mr-1"></span>
                                            Facebook
                                        </a>
                                        <a class="btn btn-block btn-sm btn-soft-google transition-3d-hover ml-1 mt-0"
                                            href="#">
                                            <span class="fab fa-google mr-1"></span>
                                            Google
                                        </a>
                                    </div>
                                    <!-- End Login Buttons -->
                                </div>
                            </form>
                            <!--End Login -->

                            <!-- Signup -->
                            <form method="POST" action="<?php echo e(route('signup')); ?>" class="js-validate">
                                <?php echo csrf_field(); ?>
                                <div id="signup" style="display: none; opacity: 0;" data-target-group="idForm">
                                    <!-- Title -->
                                    <header class="text-center mb-7">
                                        <h2 class="h4 mb-0">Welcome to Electro.</h2>
                                        <p>Fill out the form to get started.</p>
                                    </header>
                                    <!-- End Title -->
                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <div class="js-form-message js-focus-state">
                                            <div class="form-group-email mb-2">
                                                <div class="input-icon">
                                                    <label class="sr-only" for="signinName">Name</label>
                                                    <i class="fas fa-user"></i>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="Your Name" value="<?php echo e(old('name')); ?>">
                                                </div>
                                                <?php $__errorArgs = ['email'];
                                                        $__bag = $errors->getBag($__errorArgs[1] ?? 'default');
                                                        if ($__bag->has($__errorArgs[0])) :
                                                        if (isset($message)) { $__messageOriginal = $message; }
                                                        $message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
                                                        if (isset($__messageOriginal)) { $message = $__messageOriginal; }
                                                        endif;
                                                        unset($__errorArgs, $__bag);
                                                        ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Input -->


                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <div class="js-form-message js-focus-state">
                                            <div class="form-group-email mb-2">
                                                <div class="input-icon">
                                                    <label class="sr-only" for="signinEmail">Email</label>
                                                    <i class="fas fa-envelope"></i>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Email" value="<?php echo e(old('email')); ?>">
                                                </div>
                                                <?php $__errorArgs = ['email'];
                                                        $__bag = $errors->getBag($__errorArgs[1] ?? 'default');
                                                        if ($__bag->has($__errorArgs[0])) :
                                                        if (isset($message)) { $__messageOriginal = $message; }
                                                        $message = $__bag->first($__errorArgs[0]);
                                                    ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
                                                        if (isset($__messageOriginal)) { $message = $__messageOriginal; }
                                                        endif;
                                                        unset($__errorArgs, $__bag);
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Input -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <div class="js-form-message js-focus-state">
                                            <div class="form-group-password mb-2">
                                                <div class="input-icon">
                                                    <div class="custom-input-wrapper">
                                                        <label class="sr-only" for="passwordInput">Password</label>

                                                        <!-- Icon ổ khóa bên trái -->
                                                        <i class="fas fa-lock lock-icon"></i>

                                                        <!-- Input mật khẩu -->
                                                        <input type="password" class="form-control custom-input"
                                                            name="password" placeholder="Password"
                                                            id="passwordInput">

                                                        <button type="button" class="toggle-btn"
                                                            id="togglePassword">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <?php $__errorArgs = ['password'];
                                                        $__bag = $errors->getBag($__errorArgs[1] ?? 'default');
                                                        if ($__bag->has($__errorArgs[0])) :
                                                        if (isset($message)) { $__messageOriginal = $message; }
                                                        $message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
                                                        if (isset($__messageOriginal)) { $message = $__messageOriginal; }
                                                        endif;
                                                        unset($__errorArgs, $__bag);
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Input -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <div class="js-form-message js-focus-state">
                                            <div class="form-group-password mb-2">
                                                <div class="input-icon">
                                                    <div class="custom-input-wrapper">
                                                        <label class="sr-only" for="signupConfirmPassword">Confirm
                                                            Password</label>

                                                        <!-- Icon ổ khóa bên trái -->
                                                        <i class="fas fa-lock lock-icon"></i>

                                                        <!-- Input mật khẩu -->
                                                        <input type="password" class="form-control custom-input"
                                                            name="password_confirmation" placeholder="Password"
                                                            id="passwordInput">

                                                        <button type="button" class="toggle-btn"
                                                            id="togglePassword">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <?php $__errorArgs = ['password_confirmation'];
                                                        $__bag = $errors->getBag($__errorArgs[1] ?? 'default');
                                                        if ($__bag->has($__errorArgs[0])) :
                                                        if (isset($message)) { $__messageOriginal = $message; }
                                                        $message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
                                                        if (isset($__messageOriginal)) { $message = $__messageOriginal; }
                                                        endif;
                                                        unset($__errorArgs, $__bag);
                                                    ?>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- End Input -->

                                    <div class="mb-2">
                                        <button type="submit"
                                            class="btn btn-block btn-sm btn-primary transition-3d-hover">Get
                                            Started</button>
                                    </div>

                                    <div class="text-center mb-4">
                                        <span class="small text-muted">Already have an account?</span>
                                        <a class="js-animation-link small text-dark" href="javascript:;"
                                            data-target="#login" data-link-group="idForm"
                                            data-animation-in="slideInUp">Login
                                        </a>
                                    </div>

                                    <div class="text-center">
                                        <span class="u-divider u-divider--xs u-divider--text mb-4">OR</span>
                                    </div>

                                    <!-- Login Buttons -->
                                    <div class="d-flex">
                                        <a class="btn btn-block btn-sm btn-soft-facebook transition-3d-hover mr-1"
                                            href="#">
                                            <span class="fab fa-facebook-square mr-1"></span>
                                            Facebook
                                        </a>
                                        <a class="btn btn-block btn-sm btn-soft-google transition-3d-hover ml-1 mt-0"
                                            href="#">
                                            <span class="fab fa-google mr-1"></span>
                                            Google
                                        </a>
                                    </div>
                                    <!-- End Login Buttons -->
                                </div>
                                <!-- End Signup -->
                            </form>
                            <!--End Signup -->

                            <!-- Forgot Password -->
                            <form class="js-validate">
                                <div id="forgotPassword" style="display: none; opacity: 0;"
                                    data-target-group="idForm">
                                    <!-- Title -->
                                    <header class="text-center mb-7">
                                        <h2 class="h4 mb-0">Recover Password.</h2>
                                        <p>Enter your email address and an email with instructions will be sent to
                                            you.</p>
                                    </header>
                                    <!-- End Title -->

                                    <!-- Form Group -->
                                    <div class="form-group">
                                        <div class="js-form-message js-focus-state">
                                            <div class="form-group-email mb-2">
                                                <div class="input-icon">
                                                    <label class="sr-only" for="signinEmail">Email</label>
                                                    <i class="fas fa-envelope"></i>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Email" value="<?php echo e(old('email')); ?>">
                                                </div>
                                                <?php $__errorArgs = ['email'];
                                                        $__bag = $errors->getBag($__errorArgs[1] ?? 'default');
                                                        if ($__bag->has($__errorArgs[0])) :
                                                        if (isset($message)) { $__messageOriginal = $message; }
                                                        $message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
                                                        if (isset($__messageOriginal)) { $message = $__messageOriginal; }
                                                        endif;
                                                        unset($__errorArgs, $__bag);
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Group -->

                                    <div class="mb-2">
                                        <button type="submit"
                                            class="btn btn-block btn-sm btn-primary transition-3d-hover">Recover
                                            Password</button>
                                    </div>

                                    <div class="text-center mb-4">
                                        <span class="small text-muted">Remember your password?</span>
                                        <a class="js-animation-link small" href="javascript:;" data-target="#login"
                                            data-link-group="idForm" data-animation-in="slideInUp">Login
                                        </a>
                                    </div>
                                </div>
                                <!-- End Forgot Password -->
                            </form>
                        </div>
                    </div>
                    <!-- End Content -->
                </div>
            </div>
        </div>
    </aside>
    <!-- End Account Sidebar Navigation -->
    <!-- ========== END SECONDARY CONTENTS ========== -->

    <!-- Go to Top -->
    <a class="js-go-to u-go-to" href="#" data-position='{"bottom": 15, "right": 15 }' data-type="fixed"
        data-offset-top="400" data-compensation="#header" data-show-effect="slideInUp"
        data-hide-effect="slideOutDown">
        <span class="fas fa-arrow-up u-go-to__inner"></span>
    </a>
    <!-- End Go to Top -->
    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    @include('client.layouts.partials.script')
</body>


</html>
