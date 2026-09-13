<?php

namespace App\Exports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SchoolsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Pull the entire list of schools out along with their attached parent district relations
     */
    public function collection()
    {
        return School::with('district')->get();
    }

    /**
     * Set the bold text column structural headers on row 1 of the spreadsheet file
     */
    public function headings(): array
    {
        return [
            'System ID',
            'School Identity Code',
            'Educational Facility Name',
            'District Jurisdiction Title',
            'Registration Timestamp'
        ];
    }

    /**
     * Map out model field attributes to ensure clear cell-by-cell row injection formatting rules
     *
     * @param mixed $school
     */
    public function map($school): array
    {
        return [
            $school->id,
            $school->school_code,
            $school->name,
            $school->district ? $school->district->name : 'Unassigned',
            $school->created_at ? $school->created_at->format('Y-m-d H:i:s') : 'N/A'
        ];
    }
}
