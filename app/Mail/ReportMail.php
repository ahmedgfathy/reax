<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $reportData;
    public $customSubject;
    public $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Report $report, array $reportData, ?string $subject, ?string $message)
    {
        $this->report = $report;
        $this->reportData = $reportData;
        $this->customSubject = $subject;
        $this->customMessage = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->customSubject ?? 'Report: ' . $this->report->name)
                    ->markdown('emails.reports.share')
                    ->with([
                        'reportName' => $this->report->name,
                        'message' => $this->customMessage,
                        'reportData' => $this->reportData,
                    ]);
    }
}
