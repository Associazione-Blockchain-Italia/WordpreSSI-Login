<?php

namespace Inc\Services;

/**
 * The status of a credential issue request or credential verification request
 */
class CredentialStatusService {

    const REQUESTED = "Requested"; // credential request
    const OFFERED = 'Offered'; // credential created, not yet scanned
    const ISSUED = 'Issued'; // code scanned and credential added to wallet
    const DECLINED = 'Declined'; // code scanned but credential refused
    const VERIFICATION = 'Requested'; // credential verification requested
    const VERIFY = "Verify"; // qr code created for the verification
    const ACCEPTED = 'Accepted'; // qr code scanned and accepted
    const REVOKED = 'Revoked'; // credential is revoked

}
