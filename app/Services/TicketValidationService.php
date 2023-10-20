<?php

namespace App\Services;

/**
 * Validates if the requester ID and assigned ID are not the same.
 *
 * @param int $requesterId
 * @param int $assignedId
 * @return bool returns true if the IDs are different, false if they are the same
 */
class TicketValidationService
{
    public function validate($requesterID, $assignedId): bool
    {
        if($requesterID == $assignedId) {
            return false;
        }

        return true;
    }
}
