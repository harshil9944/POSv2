<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

//default or sendgrid
$config['mail_type'] = 'default';

//sendgrid settings
$config['sendgrid_api_key'] = '';

//default settings
/*$config['mail_protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.mailtrap.io';
$config['smtp_user'] = 'c188be26e716bc';
$config['smtp_pass'] = '5fd338df973378';
$config['smtp_port'] = 2525;
$config['smtp_encryption'] = '';*/

$config['mail_protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.mailersend.net';
$config['smtp_user'] = 'MS_FnggTW@inntechfuture.com';
$config['smtp_pass'] = 'qmqB16hdr1Bzp7Nh';
$config['smtp_port'] = 587;
$config['smtp_encryption'] = 'tls';
