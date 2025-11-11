<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $company_name
 * @property string|null $address
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $pic_name
 * @property string|null $pic_phone
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location> $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaintenanceReport> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaintenanceSchedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UnitAc> $units
 * @property-read int|null $units_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client search(?string $term)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePicPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperClient {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property int $user_id
 * @property int|null $schedule_id
 * @property string $subject
 * @property string $message
 * @property string $priority
 * @property string $status
 * @property array<array-key, mixed>|null $attachments
 * @property \Illuminate\Support\Carbon|null $responded_at
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\MaintenanceSchedule|null $schedule
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereRespondedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Complaint whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperComplaint {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $report_id
 * @property int $client_user_id
 * @property int $rating
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MaintenanceReport $report
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereClientUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFeedback {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UnitAc> $unitAcs
 * @property-read int|null $unit_acs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperLocation {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $schedule_id
 * @property int $technician_id
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property string|null $start_photo_path
 * @property string|null $end_photo_path
 * @property string|null $receipt_path
 * @property int $units_serviced
 * @property string|null $notes
 * @property array<array-key, mixed>|null $photos
 * @property string|null $invoice_number
 * @property string $status
 * @property int|null $verified_by_admin_id
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Feedback|null $feedback
 * @property-read \App\Models\MaintenanceSchedule $schedule
 * @property-read \App\Models\User $technician
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereEndPhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereReceiptPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereStartPhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereTechnicianId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereUnitsServiced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceReport whereVerifiedByAdminId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaintenanceReport {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property int $location_id
 * @property \Illuminate\Support\Carbon $scheduled_at
 * @property int|null $assigned_user_id
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $reminder_sent_at
 * @property string|null $client_response
 * @property \Illuminate\Support\Carbon|null $client_response_at
 * @property \Illuminate\Support\Carbon|null $client_requested_date
 * @property string|null $client_response_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read string $client_response_label
 * @property-read bool $has_pending_reschedule
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\MaintenanceReport|null $report
 * @property-read \App\Models\User|null $technician
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UnitAc> $units
 * @property-read int|null $units_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereAssignedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereClientRequestedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereClientResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereClientResponseAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereClientResponseNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereReminderSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaintenanceSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMaintenanceSchedule {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $client_id
 * @property int $created_by
 * @property int|null $location_id
 * @property array<array-key, mixed>|null $unit_ids
 * @property \Illuminate\Support\Carbon|null $requested_at
 * @property \Illuminate\Support\Carbon|null $preferred_date
 * @property string|null $note
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Location|null $location
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest wherePreferredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereRequestedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereUnitIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperServiceRequest {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $location_id
 * @property string|null $brand
 * @property string|null $model
 * @property string|null $serial_number
 * @property string|null $type
 * @property int|null $capacity_btu
 * @property \Illuminate\Support\Carbon|null $install_date
 * @property \Illuminate\Support\Carbon|null $last_maintenance_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Location $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MaintenanceSchedule> $schedules
 * @property-read int|null $schedules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereCapacityBtu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereInstallDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereLastMaintenanceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UnitAc whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUnitAc {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $role
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @property-read \App\Models\Client|null $clientProfile
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

