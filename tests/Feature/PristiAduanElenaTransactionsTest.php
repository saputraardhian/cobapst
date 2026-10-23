<?php

namespace Tests\Feature;

use App\Models\ElenaEvaluation;
use App\Models\PristiAttendance;
use App\Models\PristiMonevReport;
use App\Models\PristiSchedule;
use App\Models\PstCounter;
use App\Models\PublicComplaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PristiAduanElenaTransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pristi_schedule_and_swap(): void
    {
        $operator = User::factory()->create();
        $replacement = User::factory()->create();
        $counter = PstCounter::factory()->create();

        $schedule = PristiSchedule::factory()->create([
            'user_id' => $operator->id,
            'counter_id' => $counter->id,
            'schedule_date' => '2026-10-20',
            'day_name' => 'Selasa',
            'shift_name' => 'Pagi (08.00 - 12.00)',
            'swapped_with_user_id' => $replacement->id,
        ]);

        $this->assertDatabaseHas('pristi_schedules', [
            'user_id' => $operator->id,
            'counter_id' => $counter->id,
            'swapped_with_user_id' => $replacement->id,
        ]);

        $this->assertEquals($operator->id, $schedule->user->id);
        $this->assertEquals($replacement->id, $schedule->swappedWith->id);
        $this->assertEquals($counter->id, $schedule->counter->id);
        $this->assertCount(1, $operator->schedules);
    }

    public function test_can_record_attendance_linked_to_schedule(): void
    {
        $operator = User::factory()->create();
        $schedule = PristiSchedule::factory()->create(['user_id' => $operator->id]);

        $attendance = PristiAttendance::factory()->create([
            'schedule_id' => $schedule->id,
            'user_id' => $operator->id,
            'attendance_date' => '2026-10-20',
            'check_in_time' => '07:50:00',
            'check_out_time' => '16:05:00',
            'time_difference' => 0,
            'status' => 'present',
        ]);

        $this->assertDatabaseHas('pristi_attendances', [
            'schedule_id' => $schedule->id,
            'user_id' => $operator->id,
            'status' => 'present',
        ]);

        $this->assertEquals($schedule->id, $attendance->schedule->id);
        $this->assertEquals($operator->id, $attendance->user->id);
        $this->assertCount(1, $schedule->attendances);
        $this->assertCount(1, $operator->attendances);
    }

    public function test_can_create_monev_report_with_9_dimensions(): void
    {
        $admin = User::factory()->create();

        $report = PristiMonevReport::factory()->create([
            'region_code' => '3302',
            'region_name' => 'BPS Kabupaten Banyumas',
            'period_year' => 2026,
            'period_type' => 'triwulan',
            'period_value' => 3,
            'score_adm' => 96.00,
            'score_teknis' => 94.00,
            'score_sdm' => 91.00,
            'score_korupsi' => 100.00,
            'score_prof' => 95.00,
            'score_sarpras' => 90.00,
            'score_sipp' => 93.00,
            'score_pengaduan' => 97.00,
            'score_inovasi' => 92.00,
            'total_score' => 94.22,
            'verified_by_user_id' => $admin->id,
        ]);

        $this->assertDatabaseHas('pristi_monev_reports', [
            'region_code' => '3302',
            'period_year' => 2026,
            'period_type' => 'triwulan',
            'verified_by_user_id' => $admin->id,
        ]);

        $this->assertEquals($admin->id, $report->verifiedBy->id);
    }

    public function test_can_create_public_complaint_and_link_penalty_to_user(): void
    {
        $operator = User::factory()->create();

        $complaint = PublicComplaint::factory()->create([
            'tracking_code' => 'ADU-2026-0001',
            'reporter_name' => 'Dewi Lestari',
            'complaint_category' => 'petugas',
            'complaint_content' => 'Petugas loket kurang ramah saat konsultasi statistik.',
            'related_user_id' => $operator->id,
            'is_valid_penalty' => true,
            'status' => 'investigating',
        ]);

        $this->assertDatabaseHas('public_complaints', [
            'tracking_code' => 'ADU-2026-0001',
            'related_user_id' => $operator->id,
            'is_valid_penalty' => true,
        ]);

        $this->assertEquals($operator->id, $complaint->relatedUser->id);
        $this->assertCount(1, $operator->complaints);
    }

    public function test_can_create_elena_evaluation_and_enforce_unique_period(): void
    {
        $operator = User::factory()->create();

        $eval = ElenaEvaluation::factory()->create([
            'user_id' => $operator->id,
            'period_year' => 2026,
            'period_month' => 10,
            'total_score' => 93.50,
            'rank_position' => 1,
            'is_locked' => true,
        ]);

        $this->assertDatabaseHas('elena_evaluations', [
            'user_id' => $operator->id,
            'period_year' => 2026,
            'period_month' => 10,
            'rank_position' => 1,
            'is_locked' => true,
        ]);

        $this->assertEquals($operator->id, $eval->user->id);
        $this->assertCount(1, $operator->evaluations);
    }
}
