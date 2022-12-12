# WordPreSSI Login Feasability Report

## Introduction

The WordPreSSI Login Plugin enables the possibility of using Self Sovereign Identity by logging into WordPress sites using Verifiable Credentials. The plugin can also act as an issuer of credentials, for the services that support it.

We refer to "services" because as of today there is not a single interoperable implementation of the Self Sovereign Identity features, so we created an abstraction layer and a plugin architecture with predefined interfaces, in order to support the different services which have been developed. 
Some of them have been already implemented, as will be discussed below. The technical documentation explains how to create new "interoperability plugins".

## Deliveries

WordPreSSI project has been completed in its core functionality, together with 4 "interoperability plugins". 
The final delivery is composed of the following artifacts: 

### WordPreSSI Login plugin core

- Technical Documentation describing the plugin
- Technical Documentation for interoperability plugins, describing the current implementations and how to extend the plugin to support new services 
- Pipeline for CI/CD 

The plugin needs at least a working Self Sovereign Identity service to work. Several services are supported by means of "interoperability plugins". 

### Pipeline for CI/CD

A pipeline for CI/CD has been implemented, which enables functional testing on several Docker containers with different versions of WordPress and PHP. The pipeline includes also SonarQube for code quality control.

### WordPreSSI Login interoperability plugin: Trinsic

This module features compatibility with the Credentials API by Trinsic (https://trinsic.id), which enables to spin up enterprise agents hosted on their servers but controlled by the WordPress administrator. These enterprise agents are capable of managing complex workflows related to DIDs (contacts/Connections) and VCs (credential issuance, Revocation, Verification).

### WordPreSSI Login interoperability plugin: Evernym Verity SDK

This module features compatibility with the Verity SDK by Evernym (https://www.evernym.com/verity/), which exposes a REST API to their credentials exchange platform, which can be used with the Connect.me wallet. Is is built as an Aries compatible enterprise agent, capable of running protocols for connecting, issuing Verifiable Credentials and requesting and Zero Knowledge Proofs to and from individuals. The system is currently compaible with Hyperledger Indy ledgers.

### WordPreSSI Login interoperability plugin: EASSI

This module features compatibility with the EASSI Library by TNO (https://ssi-lab.nl/docs/about), which exposes an API which in turn supports many services, currently: Jolocom, IRMA, Esatus Wallet (with Hyperledger Indy Ledgers), Datakeeper and Trinsic. Credentials issuance and verification are supported, but some capabilities are not available: creating connections via shortcodes and credentials revocation.

### WordPreSSI Login interoperability plugin: ACA-py

A generic interface to support any ACA-Py instance using the REST API has been studied and an example implementation has been developed.
This plugin is not enabled by default, please refer to the "Provider implementation v1.0" document for info on ebabling it.
This particular Provider implements a simple PHP ACA-Py controller to connect our plugin to the administrative interface of an ACA-Py framework installed on a remote server. Our goal was to give our plugin a greater degree of freedom to provide an autonomous service of issuance and verification of credentials. For security reasons, we chose to interact with a wallet only using a cloud agent API or an ACA-Py setup on private remote servers to keep important information such as the private key separated from the WordPress server, which is tipically hosted on a shared web server.

### WordPreSSI Login interoperability plugin: walt.id

In december 2021 we found the Walt.id library (https://walt.id) which exposes a REST API which enables the communication with EBSI and ESSIF, and we believe that to fulfill the original intent of the call, we should try to complete the project by supporting also this service: for timing reasons, the work is still currently in progress.


## Technical accomplishements

The project started wih a POC made using the Trinsic API, and during the ESSIF-lab project we have studied and implemented the required features on many different services, and we also have been able to add some nice-to-have functionalities, such as shortcodes to create connections to the site, as described in the book "Self-sovereign Identity", by Alex Preukschat, Drummond Reed and others.

We also have been able to study and implement the compatibility with different Self Sovereign Identity Services, creating a standard interface for extending the functionality of the plugin.

This is a summary of the features provided by WordPreSSI Login:

|Features|Status|
|---|:---:|
|Sign up with issuance of credentials|:heavy_check_mark:|
|Login by verifiable credentials|:heavy_check_mark:|
|Shortcode for creating connections|:heavy_check_mark:|
|Credential mapping (choose the claims to use as identifier and role)|:heavy_check_mark:|
|Configuration section for each service|:heavy_check_mark:|
|Support for testing the configuration of each service|:heavy_check_mark:|
|Extensible interface for adding services via interoperabiliy plugins|:heavy_check_mark:|
|Integration of Trinsic service|:heavy_check_mark:|
|Integration of Evernym service|:heavy_check_mark:|
|Integration of EASSI service|:heavy_check_mark:|
|CI/CD Pipeline|:heavy_check_mark:|
|Technical documentation|:heavy_check_mark:|
|Test plans|:heavy_check_mark:|

|Integration of walt.id |:hourglass:|
|Integration of Gataca |:hourglass:|
|Integration of SICPA Spain |:hourglass:|
|Integration of iGrant.io |:hourglass:|

|Final security assessment|:hourglass:|

## Interop work

As described above, we tried to support as many different SSI services as possible, because as the plugin operates at the application layer, interoperability is a responsibility of the protocols layered below the APIs that implement the services.

# Who is currently using it?

In november 2021 we published a demo website for testing the features of the plugin: http://demossi.associazioneblockchain.it

Since January 2022, the Italian company Araneum Group srl (www.araneum.it) will start using the plugin for the editors of their website.

Associazione Blockchain Italia is planning to release a credential on Sovrin MainNet to all their associates which will also enable them to access the services of their institutional site (https://associazioneblockchain.it) using the plugin.

After the final security assessment and after publishing the final source code, we will promote the use of the plugin on several WordPress communities.

# Contribution to open source 

The full source code of the plugin, together with the source code of the interoperability plugins, will be published as open source at the following address:

https://github.com/Associazione-Blockchain-Italia/SSIPlugin

We plan to use a source code license to be agreed between the subgrantees of the ESSIF-Lab calls.

# Further work

As we did with the POC of the project, before the final publication of the code, we will have a complete security assessment of the code by a white hat security professional.

During ESSIF-lab project, we have initiated conversations and potential future collaborations with essif-lab subgrantees and non-essif-lab subgrantees. Below is listed a compilation of the different activities and possible future collaborations : 

* Gataca. Ongoing discussions to become an implementor of the Verifier Universal Interface API in WordPreSSI Login, providing issuance and verification capabilities for the Gataca Wallet. 

* SICPA Spain. Ongoing discussions to create an interoperability plugin to support their API, providing issuance and verification capabilities.

* iGrant.io. Ongoing discussions to create an interoperability plugin to support their API which provides issuance and verification capabilities to their iGrant.io wallet.
