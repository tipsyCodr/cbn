<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TopUsersExport implements FromCollection, WithHeadings, WithStyles
{
    private ?Carbon $from;
    private ?Carbon $until;

    public function __construct(?array $dates = null)
    {
        if ($dates) {
            [$this->from, $this->until] = $dates;
        } else {
            $this->from = null;
            $this->until = null;
        }
    }

    public function collection()
    {
        $users = User::where('role', 'user')->get();

        $data = $users->map(function (User $user) {
            return [
                $user->name,
                $this->getTotalRequests($user),
                $this->getTotalTYFCB($user),
                $this->formatCurrency($this->getTotalBusiness($user)),
                $this->formatCurrency($this->getRevenueReceived($user)),
                $this->getTotalReferrals($user),
                $this->getTotalOTO($user),
            ];
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Member',
            'Total Requests',
            'Total TYFCB',
            'Revenue Received',
            'Business Given',
            'Total Referrals',
            'Total One To One',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    private function formatCurrency(float $number): string
    {
        return '₹' . number_format($number, 2);
    }

    private function getTotalRequests(User $record): int
    {
        return $this->getTotalTYFCB($record) +
            $this->getTotalReferrals($record) +
            $this->getTotalOTO($record);
    }

    private function getTotalTYFCB(User $record): int
    {
        $query = DB::table('tyfcb_requests')
            ->where('from', $record->id)
            ->where('active', 1);

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }
        if ($this->until) {
            $query->where('created_at', '<=', $this->until);
        }

        return $query->count();
    }

    private function getTotalBusiness(User $record): float
    {
        $query = DB::table('tyfcb_requests')
            ->where('from', $record->id)
            ->where('active', 1);

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }
        if ($this->until) {
            $query->where('created_at', '<=', $this->until);
        }

        return (float) $query->sum('amount');
    }

    private function getRevenueReceived(User $record): float
    {
        $query = DB::table('tyfcb_requests')
            ->where('to', $record->id)
            ->where('active', 1);

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }
        if ($this->until) {
            $query->where('created_at', '<=', $this->until);
        }

        return (float) $query->sum('amount');
    }

    private function getTotalReferrals(User $record): int
    {
        $query = DB::table('referrals')
            ->where('from', $record->id);

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }
        if ($this->until) {
            $query->where('created_at', '<=', $this->until);
        }

        return $query->count();
    }

    private function getTotalOTO(User $record): int
    {
        $query = DB::table('oto_requests')
            ->where('user_id', $record->id)
            ->where('active', 1);

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }
        if ($this->until) {
            $query->where('created_at', '<=', $this->until);
        }

        return $query->count();
    }
}
