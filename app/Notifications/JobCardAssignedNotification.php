<?php

namespace App\Notifications;

use App\Models\JobCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobCardAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public JobCard $jobCard) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $job = $this->jobCard;
        $job->loadMissing(['customer', 'vehicle', 'branch', 'mechanic']);
        $vehicle = $job->vehicle;
        $customer = $job->customer;
        $mechanicName = $job->mechanic?->name ?? 'Technician';
        $priorityLabel = strtoupper($job->priority ?: 'NORMAL');

        $mail = (new MailMessage)
            ->subject("Zoom EV: New Work Order Assigned — Job #{$job->job_number} ({$vehicle?->plate_number})")
            ->greeting("Hello {$mechanicName},")
            ->line("You have been assigned as the lead mechanic for a new work order at Zoom EV.")
            ->line("**Work Order Reference:** #{$job->job_number}")
            ->line("**Priority Level:** {$priorityLabel}")
            ->line("**Vehicle Details:**")
            ->line("• **Plate:** {$vehicle?->plate_number}")
            ->line("• **Model:** {$vehicle?->year} {$vehicle?->make} {$vehicle?->model}")
            ->line("• **Mileage at Check-in:** " . ($job->mileage_at_checkin ? number_format($job->mileage_at_checkin) . ' km' : 'Recorded at intake'))
            ->line("• **Customer:** {$customer?->full_name} ({$customer?->phone})")
            ->line("**Work Description / Symptoms:**")
            ->line($job->customer_complaint ?: 'Scheduled diagnostic maintenance & routine mechanical inspection.');

        if ($job->estimated_completion_at) {
            $mail->line("**Estimated Completion:** " . $job->estimated_completion_at->format('M d, Y h:i A'));
        }

        return $mail
            ->action('Open Work Order in Workshop Portal', url("/admin/job-cards/{$job->id}"))
            ->line('Please log in to your workshop terminal to record parts used, labor steps, and inspection findings.');
    }
}
