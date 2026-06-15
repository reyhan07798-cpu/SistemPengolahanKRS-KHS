<?php

namespace App\Http\Controllers\Admin\Concerns;

trait HandlesAdminData
{
    use HandlesDashboardData;
    use HandlesMahasiswaData;
    use HandlesPaketMataKuliahData;
    use HandlesProdiData;
    use HandlesTahunAjaranData;
}
