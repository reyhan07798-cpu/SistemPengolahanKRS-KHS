<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$student = DB::table('mahasiswa')->where('nim', '4352501031')->first();
echo "STUDENT=" . json_encode($student, JSON_UNESCAPED_UNICODE) . "\n";

if ($student) {
    $sem = DB::table('mahasiswa_semester')->where('mahasiswa_id', $student->id)->get();
    echo "MAHASISWA_SEMESTER=" . json_encode($sem, JSON_UNESCAPED_UNICODE) . "\n";

    $krs = DB::table('krs_mahasiswa')->where('mahasiswa_id', $student->id)->orderByDesc('id')->get();
    echo "KRS=" . json_encode($krs, JSON_UNESCAPED_UNICODE) . "\n";

    $krsIds = [];
    foreach ($krs as $row) {
        $krsIds[] = $row->id;
    }

    if (count($krsIds) > 0) {
        $details = DB::table('krs_detail')->whereIn('krs_mahasiswa_id', $krsIds)->get();
        echo "DETAILS=" . json_encode($details, JSON_UNESCAPED_UNICODE) . "\n";

        $mkIds = [];
        foreach ($details as $d) {
            if (! in_array($d->mata_kuliah_id, $mkIds)) {
                $mkIds[] = $d->mata_kuliah_id;
            }
        }

        if (count($mkIds) > 0) {
            $mks = DB::table('mata_kuliah')->whereIn('id', $mkIds)->get();
            echo "MATA_KULIAH=" . json_encode($mks, JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
}
