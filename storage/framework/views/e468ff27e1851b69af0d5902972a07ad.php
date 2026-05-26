<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'matkul' => '',
    'sks' => '',
    'nilai' => '',
    'bobot' => ''
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
    'matkul' => '',
    'sks' => '',
    'nilai' => '',
    'bobot' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $gradeBadge = match($nilai) {
        'A' => 'nb-badge-success',
        'B' => 'nb-badge-primary',
        'C' => 'nb-badge-warning',
        'D' => 'nb-badge-warning',
        default => 'nb-badge-danger',
    };
?>

<tr>
    <td class="font-medium text-ink"><?php echo e($matkul); ?></td>
    <td class="text-center"><?php echo e($sks); ?></td>
    <td class="text-center">
        <span class="nb-badge <?php echo e($gradeBadge); ?>"><?php echo e($nilai); ?></span>
    </td>
    <td class="text-center font-bold text-primary"><?php echo e($bobot); ?></td>
</tr>
<?php /**PATH D:\laravel\SistemPengolahanKRS-KHS\resources\views/components/grade-row.blade.php ENDPATH**/ ?>