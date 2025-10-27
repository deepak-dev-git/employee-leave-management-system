<?php

namespace App\Exports;

use App\Models\Leave;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeavesExport implements FromView, ShouldAutoSize
{
    protected $leaves;

    public function __construct($leaves)
    {
        $this->leaves = $leaves;
    }

    public function view(): View
    {
        return view('exports.leaves', [
            'leaves' => $this->leaves
        ]);
    }
}
