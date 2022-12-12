<?php

namespace Inc\Providers\Trinsic\Controllers\Impl\CredentialStatuses;

use Inc\Services\CredentialStatusService;

class CredentialStatusMapper
{
    public static function getCredentialStatus(?string $trinsicStatus): ?string
    {
        $result = null;
        switch ($trinsicStatus) {
            case "Offered":
                $result = CredentialStatusService::OFFERED;
                break;
            case "Requested":
                $result = CredentialStatusService::REQUESTED;
                break;
            case "Issued":
                $result = CredentialStatusService::ISSUED;
                break;
            case "Accepted":
                $result = CredentialStatusService::ACCEPTED;
                break;
            default:
                $result = null;
                break;
        }
        return $result;
    }
}
