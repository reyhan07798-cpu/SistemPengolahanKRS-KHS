<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'kode' => '',
    'matkul' => '',
    'sks' => '',
    'status' => ''
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
    'kode' => '',
    'matkul' => '',
    'sks' => '',
    'status' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $statusBadge = match($status) {
        'Disetujui' => 'nb-badge-success',
        'Menunggu' => 'nb-badge-warning',
        'Ditolak' => 'nb-badge-danger',
        default => 'nb-badge-stable',
    };
?>

<tr>
    <td class="font-bold text-primary" style="font-family: var(--font-heading);"><?php echo e($kode); ?></td>
    <td class="font-medium text-ink"><?php echo e($matkul); ?></td>
    <td class="text-center"><?php echo e($sks); ?></td>
    <td class="text-center">
        <span class="nb-badge <?php echo e($statusBadge); ?>"><?php echo e($status); ?></span>
    </td>
</tr>
<?php /**PATH D:\laravel\SistemPengolahanKRS-KHS\resources\views/components/krs-table-row.blade.php ENDPATH**/ ?>