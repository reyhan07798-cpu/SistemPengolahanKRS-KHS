<?php $__env->startSection('title', 'Profil Dosen Wali'); ?>
<?php $__env->startSection('page_title', 'Profil'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal1c9954a2400961bc1b3a7c53236b4676 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1c9954a2400961bc1b3a7c53236b4676 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.profile-dosen','data' => ['dosen' => $dosen,'routeUpdate' => ''.e(route('pages.dosen_wali.profil.update')).'','routePassword' => ''.e(route('pages.dosen_wali.profil.password')).'','role' => 'Dosen Wali','icon' => 'badge','idField' => ''.e($dosen['nip']).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('profile-dosen'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dosen' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dosen),'route-update' => ''.e(route('pages.dosen_wali.profil.update')).'','route-password' => ''.e(route('pages.dosen_wali.profil.password')).'','role' => 'Dosen Wali','icon' => 'badge','id-field' => ''.e($dosen['nip']).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1c9954a2400961bc1b3a7c53236b4676)): ?>
<?php $attributes = $__attributesOriginal1c9954a2400961bc1b3a7c53236b4676; ?>
<?php unset($__attributesOriginal1c9954a2400961bc1b3a7c53236b4676); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1c9954a2400961bc1b3a7c53236b4676)): ?>
<?php $component = $__componentOriginal1c9954a2400961bc1b3a7c53236b4676; ?>
<?php unset($__componentOriginal1c9954a2400961bc1b3a7c53236b4676); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dosen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/pages/dosen_wali/profil.blade.php ENDPATH**/ ?>