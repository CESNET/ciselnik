# Číselník-Laravel

Číselník (could probably be translated to English as "Directory") is [Laravel](https://laravel.com)-based web application for managing organizations participating in eduID.cz and eduroam.cz federations or CESNET CA.

[![Actions Status](https://github.com/JanOppolzer/ciselnik-laravel/workflows/Laravel/badge.svg)](https://github.com/JanOppolzer/ciselnik-laravel/actions)

## Requirements

This application is written in Laravel 9 and uses PHP version at least 8.0.2.

Authentication is managed by locally running Shibboleth Service Provider, so Apache web server is highly recommended as there is an official Shibboleth module for Apache.

-   PHP 8.0.2+
-   Shibboleth SP 3.x
-   Apache 2.4
-   MariaDB 10.6

The above mentioned requirements can easily be achieved by using Ubuntu 22.04 LTS (Jammy Jellyfish). For those running older Ubuntu or Debian, [Ondřej Surý's PPA repository](https://launchpad.net/~ondrej/+archive/ubuntu/php/) might be very appreciated.

## Installation

The easiest way to install Číselník is to use [Envoy](https://laravel.com/docs/9.x/envoy) script in [ciselnik-envoy](https://github.com/JanOppolzer/ciselnik-envoy) repository. That repository also contains configuration snippets for Apache and Shibboleth SP.

To prepare the server for Číselník, I am using an [Ansible](https://www.ansible.com) playbook that is currently not publicly available due to being part of our larger and internal mechanism, but I am willing to share it and definitelly will do that in the future.
