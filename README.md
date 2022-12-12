# WordPress SSI Plugin

## Introduction

This plugin enables registration and authentication of users on WordPress sites using the Self Sovereign Identity model.

The advantages of using this plugin are:

- It can significantly increase security for all WordPress sites. WordPress powers 35% of the Internet in 2020. With the current system, all the WordPress sites have a repository of usernames and password, which are subject to serious risk of hacking and generate costs. The engine is frequently updated for security reasons, but the updates are often not rolled out with the same speed, generating risks.

- It can enable a decentralized online login system based on privacy-by design: users will login minimizing the display of personal data and without relying on centralized parties for storage and management.

- It can have high scalability and impact: it reaches a very huge audience, contributing to raise awareness and increase knowledge about SSI among users, and also helping the emergence of good practices for online services.


## Prerequisites

WordPress >= 5.8, PHP >= 7.3, PHP 8

## How it works

The plugin has been implemented during the ESSIF-lab IOC2 Infrastructure Oriented Call 2.

The registration use case emits a credential with the currently selected default role in the General Settings page and sends it to the user.

The login use case allows the user to login with the previously created credential.

The credentials can be subsequently revoked in the settings page (and the corresponding WordPress user is consequently deleted).

The plugin includes an extensibility mechanism in order to support different SSI services, currently the supported services are: Trinsic, Evernym Verity SDK, EASSI and Aca-Py.

Please refer to the documentation folder for further information. 

## Acknowledgements
This effort is part of a project that has received funding from the European Union’s Horizon 2020 research and innovation program under grant agreement No 871932 delivered through our participation in the eSSIF-Lab, which aims to advance the broad adoption of self-sovereign identity for the benefit of all.
