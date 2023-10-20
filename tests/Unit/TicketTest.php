<?php

namespace Tests\Unit;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Test the behavior of attempting to create a new ticket where both the user_requester_id and user_assigned_id have equal values
     *
     * @return void
     */
    public function testCannotCreatTicketWithEqualsIds(): void
    {
        $userRequesterId = 'dabfb24a-e50e-423c-bf16-9e17f1006437';
        $userAssignedId = 'dabfb24a-e50e-423c-bf16-9e17f1006438';

        $response = $this->post('/api/tickets', [
            'user_requester_id' => $userRequesterId,
            'user_assigned_id' => $userAssignedId,
            'category' => 'Technical Support',
            'description' => 'I am experiencing difficulties when trying to connect to the company network',
            'priority' => 'low'
        ]);

        $response->assertStatus(500);
    }

    /**
     * This test validates the behavior of creating a new ticket with missing required fields.
     *
     * @return void
     */
    public function testCannotCreateTicketWithMissingRequiredFields(): void
    {
        $response = $this->post('/api/tickets', []);

        $response->assertStatus(422);
    }

}
