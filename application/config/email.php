<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Configuration
|--------------------------------------------------------------------------
|
| Configuration for sending emails via SMTP.
|
| IMPORTANT: You must enable "Less secure apps" or generate an "App Password"
| if using Gmail. Standard passwords often do not work with SMTP.
|
*/

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'mail.socmacs.edu.in';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'azamit@socmacs.edu.in'; // Updated to local email
$config['smtp_pass'] = 'tain007tain'; // Updated with user provided password
$config['smtp_timeout'] = 30;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['wordwrap'] = TRUE;
$config['validation'] = TRUE; // Validates email addresses

// FIX FOR SSL CERTIFICATE ERROR
// Disables strict SSL verification which fails on some shared hosting (cprapid vs gmail)
$config['smtp_crypto'] = 'ssl'; // Enforce SSL encryption for Port 465
$config['smtp_conn_options'] = array(
    'ssl' => array(
        'verify_peer' => FALSE,
        'verify_peer_name' => FALSE,
        'allow_self_signed' => TRUE
    )
);
