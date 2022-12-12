WordPreSSI Plugin Interoperability

V1.0 – 14/1/2022

# Introduction

This document is a reference for the implementation of all “Identity providers” of the WordPreSSI Login plugin.
The capabilities of every service are explained, together with the configuratin options.

## Trinsic

This module features compatibility with the Credentials API by Trinsic (https://trinsic.id), which enables to spin up enterprise agents hosted on their servers but controlled by the WordPress administrator. These enterprise agents are capable of managing complex workflows related to DIDs (contacts/Connections) and VCs (credential issuance, Revocation, Verification).

Capabilities


|Setup|Needed, using Trinsic Studio|
| :- | :- |
|Issue Credentials|Supported|
|Verify Credentials|Supported (Mandatory)|
|DIDComm Messaging|Supported|
|Revoke Credentials |Supported|
|Create connection invitation|Supported|
|Communication with WordPress server |Webhook, polling\*|

Parameters

- Service endpoint – the base URL of Trinsic SDK e.g. https://api.trinsic.id/
- API KEY of the Trinsic Studio Organization of the WordPress Identity
- Definition ID, e.g.: Xw9jQyfGdYzCbiRvXpWYrt:3:CL:153208:default 
- Verification ID, e.g.: 181eaa56-c177-496d-bef4-08d875d3a1ce
- Credential Mapping (claim for indicating the unique user Identifier and the Role)


## Evernym

This module features compatibility with the Verity SDK by Evernym (https://www.evernym.com/verity/), which exposes a REST API to their credentials exchange platform, which can be used with the Connect.me wallet. Is is built as an Aries compatible enterprise agent, capable of running protocols for connecting, issuing Verifiable Credentials and requesting and Zero Knowledge Proofs to and from individuals. The system is currently compaible with Hyperledger Indy ledgers.

The module requires creation of a new table on the database (script provided) and the manual setup of the webhook (can be found in setup.php script).

Capabilities


|Setup|Needed, using API|
| :- | :- |
|Issue Credentials|Supported|
|Verify Credentials|Supported (Mandatory)|
|DIDComm Messaging|Supported|
|Create connection invitation |Supported|
|Revoke Credentials |Not Supported|
|Communication with WordPress server |Webhook only|

Parameters

- Service endpoint – the base URL of Verity SDK e.g. <https://vas.pps.evernym.com/api>
- Domain DID (step 1 of setup phase), e.g. A4js9EAQVwX6Q3CPXxjQCX
- WebHook URL (if using ngrok): e.g. https://e79b-84-221-61-229.ngrok.io 
- API KEY (step 2 of setup phase), e.g. xxxxxxx		
- Credential ID on the Indy Blockchain
- Credential Mapping (claim for indicating the unique user Identifier and the Role)


## EASSI

This module features compatibility with the EASSI Library by TNO (https://ssi-lab.nl/docs/about), which exposes an API which in turn supports many services: Jolocom, IRMA, Esatus Wallet (with Hyperledger Indy Ledgers), Datakeeper and Trinsic. Credentials issuance and verification are supported, but some capabilities are missing: creating connections via shortcodes and credentials revocation.

Capabilities


|Setup|Needed, using site|
| :- | :- |
|Issue Credentials|Supported|
|Verify Credentials|Supported (Mandatory)|
|DIDComm Messaging|Not Supported|
|Create connection invitation |Not Supported|
|Revoke Credentials |Not Supported|
|Communication with client |Redirect URL with token|

High level description – Architecture

The EASSI library (https://ssi-lab.nl/) has been created by TNO (http://tno.nl) for easy issuing and verification of SSI credentials.

Parameters

- Service endpoint – the base URL of the EASSI installation e.g. http://service.ssi-lab.nl
- Application ID (step 1 of setup phase), e.g. 18 for WordPreSSI Login
- Shared Credential (step 1 of setup phase), is the shared key user to verify the signature using the HS256 algorithm
- Name of Credential (step 2 of setup phase), e.g. LoginCredential

Setup phase

Step 1 – create application ID: <https://service.ssi-lab.nl/utils/organizations>

Step 2 – create credential type: <https://service.ssi-lab.nl/utils/credential-types>


## ACA-Py

This particular Provider implements a simple PHP ACA-Py controller to connect our plugin to the administrative interface of an ACA-Py framework installed on a remote server. Our goal was to give our plugin a greater degree of freedom to provide an autonomous service of issuance and verification of credentials.

For security reasons, we chose to interact with a wallet only using a cloud agent API or an ACA-Py setup on private remote servers to keep important information such as the private key separated from the WordPress server tipically hosted on a shared web server.

Capabilities


|Setup|Needed, using API|
| :- | :- |
|Issue Credentials|Supported|
|Verify Credentials|Supported (Mandatory)|
|DIDComm Messaging|Supported|
|Create connection invitation |Supported|
|Revoke Credentials |Supported|
|Communication with WordPress server |Webhook and status calls|

Parameters

- ACA-Py Admin Endpoint – the base URL of ACA-Py API endpoint
- ACA-Py Admin API Key, for security reasons the ACA-Py installation must be configured with authentication
- WebHook URL (currently not used, the module uses get status calls) 
- Credential Definition ID on the Indy Blockchain
- Credential Mapping (claim for indicating the unique user Identifier and the Role)

Used ACA-Py startup options:

PORTS="10000:10000 10001:10001" ./scripts/run\_docker start 
`	`--label ACAPy-WordpreSSI
`	`--inbound-transport https 0.0.0.0 10000

`	`--outbound-transport https

`	`--admin 0.0.0.0 10001 

`	`--admin-api-key \*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*

`	`--endpoint https://example.com:10000/ 

`	`--genesis-url https://raw.githubusercontent.com/sovrin-foundation/sovrin/master/sovrin/pool\_transactions\_builder\_genesis 

`	`--wallet-type indy 

`	`--wallet-name WordpreSSIWallet

`	`--wallet-key \*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*

`	`--public-invites 

`	`--auto-accept-invites 

`	`--auto-accept-requests 

`	`--auto-respond-credential-proposal 

`	`--auto-respond-credential-offer 

`	`--auto-respond-credential-request

`	`--auto-verify-presentation

`	`--auto-store-credential 

`	`--auto-ping-connection 

`	`--monitor-ping  

`	`--seed \*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\*\* 

`	`--debug-credentials

`	`--debug-connections 

`	`--debug-presentations 

`	`--log-level DEBUG 

`	`--log-file logfile
