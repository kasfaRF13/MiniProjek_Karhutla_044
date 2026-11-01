<?php

namespace App\Observers;

use App\Models\Report;
use Illuminate\Support\Facades\Log;

class ReportObserver
{
    /**
     * Handle the Report "created" event.
     */
    public function created(Report $report): void
    {
        // Masukkan kode ini di dalam fungsi created
        Log::info("Laporan baru berhasil dibuat dengan ID: {$report->id} oleh User ID: {$report->user_id}");
    }

    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
    {
        // Masukkan kode ini di dalam fungsi updated
        if ($report->isDirty('status')) {
            Log::info("Status laporan ID: {$report->id} berubah menjadi: {$report->status}");
        }
    }

    /**
     * Handle the Report "deleted" event.
     */
    public function deleted(Report $report): void
    {
        // Biarkan kosong atau tambahkan log jika ingin
    }

    /**
     * Handle the Report "restored" event.
     */
    public function restored(Report $report): void
    {
        //
    }

    /**
     * Handle the Report "force deleted" event.
     */
    public function forceDeleted(Report $report): void
    {
        //
    }
}