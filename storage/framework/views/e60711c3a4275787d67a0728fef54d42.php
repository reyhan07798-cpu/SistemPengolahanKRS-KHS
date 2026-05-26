
<div class="nb-page-header">
    <div>
        <?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
            'eyebrow' => '',
            'title' => 'Selamat Datang',
            'description' => ''
        ]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
            'eyebrow' => '',
            'title' => 'Selamat Datang',
            'description' => ''
        ]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
        
        <span class="nb-eyebrow"><?php echo e($eyebrow); ?></span>
        <h1 class="mt-2"><?php echo e($title); ?></h1>
        <p><?php echo e($description); ?></p>
    </div>
    <div class="flex gap-3 flex-wrap">
        <?php $__currentLoopData = $buttons ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $button): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($button['route']); ?>" class="nb-btn <?php echo e($button['variant'] ?? 'nb-btn-primary'); ?>">
                <?php if(isset($button['icon'])): ?>
                    <span class="material-symbols-outlined" style="font-size:20px;"><?php echo e($button['icon']); ?></span>
                <?php endif; ?>
                <?php echo e($button['label']); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<?php /**PATH D:\laravel\SistemPengolahanKRS-KHS1\resources\views/components/hero-header-dosen.blade.php ENDPATH**/ ?>