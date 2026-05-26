
<div class="nb-bento" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
    <?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['stats' => [], 'config' => []]));

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

foreach (array_filter((['stats' => [], 'config' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
    
    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $conf = $config[$key] ?? [];
            $colorClass = $conf['color'] ?? 'nb-stat--info';
            $icon = $conf['icon'] ?? 'info';
            $label = $conf['label'] ?? ucwords(str_replace('_', ' ', $key));
        ?>
        <div class="<?php echo e($colorClass); ?> nb-stat nb-stat--ribbon">
            <div class="flex items-center gap-3">
                <div class="nb-stat-icon">
                    <span class="material-symbols-outlined filled"><?php echo e($icon); ?></span>
                </div>
                <p class="nb-stat-label"><?php echo e($label); ?></p>
            </div>
            <div class="nb-stat-value"><?php echo e($value); ?></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php /**PATH C:\Users\LENOVO T14\Documents\SistemPengolahanKRS-KHS\resources\views/components/stat-bento.blade.php ENDPATH**/ ?>