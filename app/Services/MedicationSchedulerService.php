<?php

namespace App\Services;

use App\Models\PatientMedication;
use App\Models\MedicationAdministration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MedicationSchedulerService
{
    /**
     * Map frequency codes to daily standard time slots.
     */
    public static function getFrequencyTimeSlots(string $frequency): array
    {
        $f = strtoupper(trim($frequency));

        switch ($f) {
            case 'OD':
            case 'ONCE DAILY':
            case '1-0-0':
            case '0-1-0':
            case '0-0-1':
                return ['09:00'];

            case 'BD':
            case 'BID':
            case 'TWICE DAILY':
            case '1-0-1':
            case '0-1-1':
            case '1-1-0':
                return ['09:00', '21:00'];

            case 'TDS':
            case 'TID':
            case 'THRICE DAILY':
            case 'THREE TIMES DAILY':
            case '1-1-1':
                return ['08:00', '14:00', '20:00'];

            case 'QID':
            case 'FOUR TIMES DAILY':
            case '1-1-1-1':
                return ['06:00', '12:00', '18:00', '22:00'];

            case 'Q4H':
            case 'EVERY 4 HOURS':
                return ['02:00', '06:00', '10:00', '14:00', '18:00', '22:00'];

            case 'Q6H':
            case 'EVERY 6 HOURS':
                return ['00:00', '06:00', '12:00', '18:00'];

            case 'Q8H':
            case 'EVERY 8 HOURS':
                return ['06:00', '14:00', '22:00'];

            case 'HS':
            case 'BEDTIME':
            case 'AT BEDTIME':
                return ['21:00'];

            case 'STAT':
                return ['STAT'];

            case 'SOS':
            case 'PRN':
            case 'AS NEEDED':
                return []; // SOS doses are recorded on demand

            default:
                // If contains BD, TDS, etc. inside text
                if (str_contains($f, 'BD') || str_contains($f, 'BID')) return ['09:00', '21:00'];
                if (str_contains($f, 'TDS') || str_contains($f, 'TID')) return ['08:00', '14:00', '20:00'];
                if (str_contains($f, 'QID')) return ['06:00', '12:00', '18:00', '22:00'];
                if (str_contains($f, 'Q4H')) return ['02:00', '06:00', '10:00', '14:00', '18:00', '22:00'];
                if (str_contains($f, 'Q6H')) return ['00:00', '06:00', '12:00', '18:00'];
                if (str_contains($f, 'Q8H')) return ['06:00', '14:00', '22:00'];
                if (str_contains($f, 'STAT')) return ['STAT'];
                if (str_contains($f, 'SOS') || str_contains($f, 'PRN')) return [];
                return ['09:00']; // default once daily
        }
    }

    /**
     * Determine hospital nursing shift based on 24-hour time string (H:i).
     */
    public static function getShift(string $timeStr): string
    {
        $hour = (int) explode(':', $timeStr)[0];

        if ($hour >= 8 && $hour < 14) {
            return 'Morning';
        } elseif ($hour >= 14 && $hour < 20) {
            return 'Evening';
        } else {
            return 'Night';
        }
    }

    /**
     * Parse duration string to number of days.
     */
    public static function parseDurationDays(?string $duration): int
    {
        if (empty($duration)) return 7;

        $d = strtolower(trim($duration));
        if (str_contains($d, 'ongoing') || str_contains($d, 'continue')) {
            return 7; // generate for 7 days ahead
        }

        preg_match('/\d+/', $duration, $matches);
        if (!empty($matches[0])) {
            $num = (int) $matches[0];
            if (str_contains($d, 'month')) return min($num * 30, 30);
            if (str_contains($d, 'week')) return min($num * 7, 30);
            return max(1, min($num, 30)); // 1 to 30 days
        }

        return 5;
    }

    /**
     * Generate scheduled doses for an active medication order.
     */
    public static function generateDoses(PatientMedication $medication): int
    {
        $timeSlots = self::getFrequencyTimeSlots($medication->frequency ?? 'OD');
        $startDate = $medication->start_date ? Carbon::parse($medication->start_date) : now();

        // STAT dose: single immediate dose
        if (!empty($timeSlots) && $timeSlots[0] === 'STAT') {
            MedicationAdministration::create([
                'patient_medication_id' => $medication->id,
                'ipd_patient_id'        => $medication->ipd_patient_id,
                'patient_id'            => $medication->patient_id,
                'scheduled_at'          => $startDate,
                'shift'                 => self::getShift($startDate->format('H:i')),
                'status'                => 'scheduled',
                'route_administered'    => $medication->route,
                'given_quantity'        => $medication->dose,
            ]);

            $medication->update(['end_date' => $startDate]);
            return 1;
        }

        // SOS: no advance generation
        if (empty($timeSlots)) {
            return 0;
        }

        $days = self::parseDurationDays($medication->duration);
        $count = 0;
        $lastScheduled = null;

        DB::beginTransaction();
        try {
            for ($day = 0; $day < $days; $day++) {
                $currentDay = $startDate->copy()->addDays($day);

                foreach ($timeSlots as $slot) {
                    $parts = explode(':', $slot);
                    $scheduledAt = $currentDay->copy()->setTime((int)$parts[0], (int)$parts[1], 0);

                    // Skip dose if it's day 0 and scheduled time already passed more than 1 hour ago
                    if ($day === 0 && $scheduledAt->lt($startDate->copy()->subHour())) {
                        continue;
                    }

                    // Check if already exists to prevent duplicate generation
                    $exists = MedicationAdministration::where('patient_medication_id', $medication->id)
                        ->where('scheduled_at', $scheduledAt)
                        ->exists();

                    if (!$exists) {
                        MedicationAdministration::create([
                            'patient_medication_id' => $medication->id,
                            'ipd_patient_id'        => $medication->ipd_patient_id,
                            'patient_id'            => $medication->patient_id,
                            'scheduled_at'          => $scheduledAt,
                            'shift'                 => self::getShift($slot),
                            'status'                => 'scheduled',
                            'route_administered'    => $medication->route,
                            'given_quantity'        => $medication->dose,
                        ]);
                        $count++;
                        $lastScheduled = $scheduledAt;
                    }
                }
            }

            if ($lastScheduled && !$medication->end_date) {
                $medication->update(['end_date' => $lastScheduled]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $count;
    }

    /**
     * Administer a scheduled dose (Given, Not Given, Held, Refused).
     */
    public static function administerDose(int $doseId, array $data, $user): MedicationAdministration
    {
        $dose = MedicationAdministration::with('medication')->findOrFail($doseId);
        $status = $data['status']; // given, not_given, held, refused

        $actualTime = null;
        if ($status === 'given') {
            $actualTime = !empty($data['actual_administered_at'])
                ? Carbon::parse($data['actual_administered_at'])
                : now();
        } else {
            $actualTime = now();
        }

        $doseQuantity = !empty($data['given_quantity'])
            ? trim($data['given_quantity'])
            : ($dose->medication->dose ?? null);

        $route = !empty($data['route_administered'])
            ? trim($data['route_administered'])
            : ($dose->medication->route ?? null);

        $staffId = 'EMP-' . $user->id;
        $staffName = $user->name;

        $dose->update([
            'status'                  => $status,
            'actual_administered_at'  => $actualTime,
            'given_quantity'          => $doseQuantity,
            'route_administered'      => $route,
            'administered_by_user_id' => $user->id,
            'staff_id'                => $staffId,
            'staff_name'              => $staffName,
            'recorded_at'             => now(), // immutable server timestamp
            'remarks'                 => !empty($data['remarks']) ? trim($data['remarks']) : null,
        ]);

        return $dose;
    }

    /**
     * Record an on-demand SOS / PRN dose.
     */
    public static function recordSosDose(PatientMedication $medication, array $data, $user): MedicationAdministration
    {
        $actualTime = !empty($data['actual_administered_at'])
            ? Carbon::parse($data['actual_administered_at'])
            : now();

        $status = $data['status'] ?? 'given';
        $doseQuantity = !empty($data['given_quantity'])
            ? trim($data['given_quantity'])
            : ($medication->dose ?? null);

        $route = !empty($data['route_administered'])
            ? trim($data['route_administered'])
            : ($medication->route ?? null);

        return MedicationAdministration::create([
            'patient_medication_id'   => $medication->id,
            'ipd_patient_id'          => $medication->ipd_patient_id,
            'patient_id'              => $medication->patient_id,
            'scheduled_at'            => $actualTime,
            'shift'                   => self::getShift($actualTime->format('H:i')),
            'status'                  => $status,
            'actual_administered_at'  => $actualTime,
            'given_quantity'          => $doseQuantity,
            'route_administered'      => $route,
            'administered_by_user_id' => $user->id,
            'staff_id'                => 'EMP-' . $user->id,
            'staff_name'              => $user->name,
            'recorded_at'             => now(),
            'remarks'                 => $data['remarks'] ?? 'SOS/PRN dose administered',
        ]);
    }

    /**
     * Discontinue medication order and remove or cancel future pending doses.
     */
    public static function discontinue(PatientMedication $medication, string $reason, $user): void
    {
        DB::beginTransaction();
        try {
            $medication->update([
                'status'             => 'discontinued',
                'discontinued_at'    => now(),
                'discontinued_by'    => $user->id,
                'discontinue_reason' => $reason,
            ]);

            // Remove future scheduled doses that haven't been given or documented yet
            MedicationAdministration::where('patient_medication_id', $medication->id)
                ->where('status', 'scheduled')
                ->where('scheduled_at', '>=', now()->subMinutes(30))
                ->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
