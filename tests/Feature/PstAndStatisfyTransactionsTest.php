<?php

namespace Tests\Feature;

use App\Models\PstCounter;
use App\Models\PstServiceTicket;
use App\Models\PstVisitor;
use App\Models\PstVisitorLog;
use App\Models\StatisfyAiAnalysis;
use App\Models\StatisfySurvey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PstAndStatisfyTransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_visitor_and_log_kunjungan(): void
    {
        $visitor = PstVisitor::factory()->create([
            'phone_number' => '081234567890',
            'name' => 'Ahmad Fauzi',
            'email' => 'fauzi@example.com',
            'institution' => 'Universitas Diponegoro',
        ]);

        $log = PstVisitorLog::factory()->create([
            'visitor_id' => $visitor->id,
            'purpose' => 'Penelitian Skripsi Data Inflasi',
        ]);

        $this->assertDatabaseHas('pst_visitors', [
            'phone_number' => '081234567890',
            'name' => 'Ahmad Fauzi',
        ]);

        $this->assertDatabaseHas('pst_visitor_logs', [
            'visitor_id' => $visitor->id,
            'purpose' => 'Penelitian Skripsi Data Inflasi',
        ]);

        $this->assertEquals($visitor->id, $log->visitor->id);
        $this->assertCount(1, $visitor->visitorLogs);
    }

    public function test_can_create_service_ticket_with_qr_token_and_relations(): void
    {
        $visitor = PstVisitor::factory()->create();
        $operator = User::factory()->create();
        $counter = PstCounter::factory()->create([
            'active_operator_id' => $operator->id,
        ]);

        $ticket = PstServiceTicket::factory()->create([
            'ticket_number' => 'A-001',
            'queue_code' => 'A',
            'queue_number' => 1,
            'qr_token' => 'sample-unique-qr-token-12345',
            'service_type' => 'konsultasi',
            'visitor_id' => $visitor->id,
            'visitor_name' => $visitor->name,
            'counter_id' => $counter->id,
            'served_by_user_id' => $operator->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('pst_service_tickets', [
            'ticket_number' => 'A-001',
            'qr_token' => 'sample-unique-qr-token-12345',
            'visitor_id' => $visitor->id,
            'counter_id' => $counter->id,
            'served_by_user_id' => $operator->id,
        ]);

        $this->assertEquals($visitor->id, $ticket->visitor->id);
        $this->assertEquals($counter->id, $ticket->counter->id);
        $this->assertEquals($operator->id, $ticket->servedBy->id);
        $this->assertCount(1, $operator->servedTickets);
        $this->assertCount(1, $counter->serviceTickets);
    }

    public function test_can_submit_statisfy_survey_and_bind_to_ticket(): void
    {
        $operator = User::factory()->create();
        $counter = PstCounter::factory()->create(['active_operator_id' => $operator->id]);
        $ticket = PstServiceTicket::factory()->create([
            'counter_id' => $counter->id,
            'served_by_user_id' => $operator->id,
        ]);

        $survey = StatisfySurvey::factory()->create([
            'service_ticket_id' => $ticket->id,
            'rated_user_id' => $operator->id,
            'counter_id' => $counter->id,
            'rating_score' => 5,
            'feedback_comment' => 'Pelayanan cepat dan informatif',
        ]);

        $this->assertDatabaseHas('statisfy_surveys', [
            'service_ticket_id' => $ticket->id,
            'rated_user_id' => $operator->id,
            'counter_id' => $counter->id,
            'rating_score' => 5,
        ]);

        $this->assertEquals($survey->id, $ticket->survey->id);
        $this->assertEquals($ticket->id, $survey->serviceTicket->id);
        $this->assertCount(1, $operator->receivedSurveys);
    }

    public function test_can_store_and_retrieve_statisfy_ai_analysis(): void
    {
        $aiAnalysis = StatisfyAiAnalysis::factory()->create([
            'mode' => 'bulanan',
            'period_month' => 10,
            'period_year' => 2026,
            'summary_sentiment' => 'positif',
            'total_reviews_analyzed' => 120,
        ]);

        $this->assertDatabaseHas('statisfy_ai_analyses', [
            'mode' => 'bulanan',
            'period_month' => 10,
            'period_year' => 2026,
            'summary_sentiment' => 'positif',
        ]);

        $this->assertEquals(120, $aiAnalysis->total_reviews_analyzed);
    }
}
