<?php

namespace App\Jobs;

use App\Models\ReportShare;
use App\Services\ReportGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReportWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportShare;

    /**
     * Create a new job instance.
     */
    public function __construct(ReportShare $reportShare)
    {
        $this->reportShare = $reportShare;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Generate the report data
        $generator = new ReportGenerator($this->reportShare->report);
        $reportData = $generator->generate();
        
        // This would integrate with a WhatsApp API service
        // For now, we'll just log the attempt
        \Log::info('WhatsApp report would be sent to: ' . $this->reportShare->recipient);
        
        // Update last sent time
        $this->reportShare->update([
            'last_sent_at' => now(),
            'next_send_at' => $this->reportShare->scheduled 
                ? $this->calculateNextSendDate($this->reportShare->frequency)
                : null
        ]);
    }
    
    private function calculateNextSendDate($frequency)
    {
        switch ($frequency) {
            case 'daily':
                return now()->addDay();
            case 'weekly':
                return now()->addWeek();
            case 'monthly':
                return now()->addMonth();
            default:
                return now()->addDay();
        }
    }
}
