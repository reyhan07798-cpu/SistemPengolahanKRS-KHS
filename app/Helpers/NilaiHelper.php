<?php

if (!function_exists('getNilaiColor')) {
    function getNilaiColor($nilai)
    {
        $colors = [
            'A' => '#22c55e',   // green
            'A-' => '#84cc16',  // lime
            'B+' => '#eab308',  // yellow
            'B' => '#f97316',   // orange
            'B-' => '#ef4444',  // red
            'C+' => '#dc2626',  // dark-red
            'C' => '#7f1d1d',   // very-dark-red
            'D' => '#4b5563',   // gray
            'E' => '#1f2937'    // dark-gray
        ];
        return $colors[$nilai] ?? '#666';
    }
}
