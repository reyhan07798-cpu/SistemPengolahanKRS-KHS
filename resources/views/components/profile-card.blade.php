<div class="nb-card">
    <div class="flex items-center gap-4 mb-6 pb-6" style="border-bottom: 4px solid var(--color-ink);">
        <div class="nb-avatar">
            <span class="material-symbols-outlined filled" style="font-size:32px;">person</span>
        </div>
        <div class="min-w-0">
            <h3 class="nb-h3">{{ $nama }}</h3>
            <p class="nb-eyebrow mt-1">{{ $nim }}</p>
        </div>
    </div>

    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <dt class="nb-label">Program Studi</dt>
            <dd class="text-base font-medium text-ink">{{ $prodi }}</dd>
        </div>
        <div>
            <dt class="nb-label">Email</dt>
            <dd class="text-base font-medium text-ink break-all">{{ $email }}</dd>
        </div>
        <div>
            <dt class="nb-label">No. HP</dt>
            <dd class="text-base font-medium text-ink">{{ $hp }}</dd>
        </div>
        <div>
            <dt class="nb-label">NIM</dt>
            <dd class="text-base font-medium text-ink">{{ $nim }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="nb-label">Alamat</dt>
            <dd class="text-base font-medium text-ink">{{ $alamat }}</dd>
        </div>
    </dl>
</div>
