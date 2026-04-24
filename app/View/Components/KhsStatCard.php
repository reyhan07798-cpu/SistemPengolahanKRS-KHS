<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class KhsStatCard extends Component
{
    public string $title;
    public string|int $value;
    public string $accent;
    public string $textColor;
    public string $icon;

    public function __construct(string $title, string|int $value, string $accent = 'bg-slate-100', string $textColor = 'text-slate-900', string $icon = '')
    {
        $this->title = $title;
        $this->value = $value;
        $this->accent = $accent;
        $this->textColor = $textColor;
        $this->icon = $icon;
    }

    public function render(): View|Closure|string
    {
        return view('components.khs-stat-card');
    }
}
