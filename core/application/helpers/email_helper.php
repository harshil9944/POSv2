<?php
if ( !function_exists( '_sg_send' ) ) {
    function _sg_send( $to, $subject, $text, $html ) {

        /*$email = new \SendGrid\Mail\Mail();

    $email->setFrom(_get_setting('from_email','rc@yellowsky.in'), _get_setting('from_name','Yellow Sky'));
    $email->setSubject($subject);
    $email->addTo($to['email'], $to['name']);
    $email->addContent("text/plain", $text);
    $email->addContent("text/html", $html);

    $sendgrid = new \Sendgrid(_get_config('sendgrid_api_key'));
    try {
    $response = $sendgrid->send($email);

    if($response->statusCode()==202) {
    return true;
    }else{
    return false;
    }
    } catch (Exception $e) {
    log_message('error',$e->getMessage());
    }*/

    }
}
if ( !function_exists( '_ci_send' ) ) {
    /*function _ci_send($to,$subject,$text,$html,$attachments=array()) {
    _library('email');
    $obj =& get_instance();

    $obj->email->from(_get_setting('from_email','rc@yellowsky.in'), _get_setting('from_name','Yellow Sky'));
    $obj->email->to($to);

    if($_SERVER['CI_ENV']=='production') {
    $obj->email->bcc('divaa.customers@gmail.com');
    }

    $obj->email->set_mailtype('html');
    $obj->email->subject($subject);
    $obj->email->message($html);
    $obj->email->set_alt_message($text);

    if($attachments) {
    foreach ($attachments as $attachment) {
    $obj->email->attach($attachment);
    }
    }

    $result = $obj->email->send();

    if($result) {
    $obj->email->clear();
    return true;
    }else{
    log_message('error',$obj->email->print_debugger());
    $obj->email->clear();
    return false;
    }

    }*/
    function _ci_send( $to, $subject, $text, $html, $attachments = [] ) {

        _load_config( 'email' );

        $mail_type = _get_config( 'mail_type' );
        if ( $mail_type == 'sendgrid' ) {
            return _sg_send( $to, $subject, $text, $html, $attachments );
        } else {

            _library( 'email' );
            $obj = &get_instance();

            /*$config = array();
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'xxx';
            $config['smtp_user'] = 'xxx';
            $config['smtp_pass'] = 'xxx';
            $config['smtp_port'] = 25;
            $obj->email->initialize($config);*/

            $config = [];
            $config['protocol'] = _get_config( 'mail_protocol' );
            $config['smtp_host'] = _get_config( 'smtp_host' );
            $config['smtp_user'] = _get_config( 'smtp_user' );
            $config['smtp_pass'] = _get_config( 'smtp_pass' );
            $config['smtp_port'] = _get_config( 'smtp_port' );
            $config['smtp_timeout'] = '30';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['mailtype'] = 'html';
            $config['validation'] = TRUE;
            $obj->email->initialize( $config );

            $obj->email->smtp_crypto = _get_config( 'smtp_encryption' );

            $obj->email->from( _get_setting( 'from_email', DEFAULT_EMAIL ), _get_setting( 'from_name', DEFAULT_EMAIL_NAME ) );
            $obj->email->to( $to['email'] );

            $obj->email->reply_to(DEFAULT_REPLY_TO_EMAIL,DEFAULT_REPLY_TO_NAME);

            /*if ( $_SERVER['CI_ENV'] == 'production' ) {
                $obj->email->bcc( 'divaa.customers@gmail.com' );
            }*/

            $obj->email->subject( $subject );
            $obj->email->message( $html );
            $obj->email->set_alt_message( $text );

            if ( $attachments ) {
                foreach ( $attachments as $attachment ) {
                    $obj->email->attach( $attachment );
                }
            }

            $result = $obj->email->send();
            if ( $result ) {
                $obj->email->clear();
                return true;
            } else {
                log_message( 'error', $obj->email->print_debugger() );
                $obj->email->clear();
                return false;
            }
        }
    }
}
