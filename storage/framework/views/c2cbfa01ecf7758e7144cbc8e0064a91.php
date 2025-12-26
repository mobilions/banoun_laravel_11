
<?php $__env->startSection('title','Login'); ?>
<?php $__env->startSection('StyleContent'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('PageContent'); ?>
<div class="col-md-8 col-lg-6 col-xl-5">
<div class="card overflow-hidden">
<div class="bg-dark">
<div class="row">
<div class="col-12 text-center">
    <div class="text-dark p-4">
            <img src="<?php echo e(asset('/assets')); ?>/img/banoun.png" alt="" class="" height="45">
    </div>
</div>
</div>
</div>
<div class="card-body pt-0"> 
<div class="auth-logo">
<a href="" class="auth-logo-light">
    <div class="avatar-md profile-user-wid mb-4">
        <span class="avatar-title rounded-circle bg-light">
            <img src="<?php echo e(asset('/assets')); ?>/images/logo-light.svg" alt="" class="rounded-circle" height="34">
        </span>
    </div>
</a>

<a href="" class="auth-logo-dark">
    <div class="avatar-md profile-user-wid mb-4">
        <span class="avatar-title rounded-circle bg-light">
            <i class="fa text-dark fa-lg fa-user"></i>
        </span>
    </div>
</a>
</div>
<div class="p-2">
<form method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="email" type="text" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>
    </div>
    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert">
        <strong><?php echo e($message); ?></strong>
    </span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group auth-pass-inputgroup">
            <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password" aria-describedby="password-addon">
            <button class="btn btn-light " type="button" id="password-addon" aria-label="Toggle password visibility"><i class="mdi mdi-eye-outline"></i></button>
        </div>
    </div>
    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <span class="invalid-feedback" role="alert">
        <strong><?php echo e($message); ?></strong>
    </span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="remember-check" name="remember" value="1" <?php echo e(old('remember') ? 'checked' : ''); ?>>
        <label class="form-check-label" for="remember-check">
            Remember me
        </label>
    </div>
    
    <div class="mt-3 d-grid">
        <button class="btn btn-dark waves-effect waves-light" type="submit">Log In</button>
    </div>
</form>
</div>

</div>
</div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('ScriptContent'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('password-addon');
    const passwordInput = document.getElementById('password');
    const icon = toggleBtn ? toggleBtn.querySelector('i') : null;

    if (!toggleBtn || !passwordInput || !icon) {
        return;
    }

    toggleBtn.addEventListener('click', function () {
        const isHidden = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isHidden ? 'text' : 'password');

        icon.classList.toggle('mdi-eye-outline', !isHidden);
        icon.classList.toggle('mdi-eye-off-outline', isHidden);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.plain', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\all_project\banoun_laravel_11\resources\views/auth/login.blade.php ENDPATH**/ ?>