<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Email extends CI_Email
{
    /**
     * SMTP Connection Options (e.g., SSL Context)
     * @var array
     */
    public $smtp_conn_options = array();

    /**
     * SMTP Connect
     *
     * @return  string
     */
    protected function _smtp_connect()
    {
        log_message('debug', 'MY_Email: _smtp_connect() called');
        if (is_resource($this->_smtp_connect)) {
            return TRUE;
        }

        $ssl = ($this->smtp_crypto === 'ssl') ? 'ssl://' : '';

        $this->_smtp_connect = FALSE;

        // Use the class property, which CI auto-populates from config array
        $conn_options = $this->smtp_conn_options;

        if (empty($conn_options)) {
            $conn_options = array(
                'ssl' => array(
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE
                )
            );
        }

        if (!empty($conn_options) && is_array($conn_options)) {
            $context = stream_context_create($conn_options);
            $this->_smtp_connect = stream_socket_client(
                $ssl . $this->smtp_host . ':' . $this->smtp_port,
                $errno,
                $errstr,
                $this->smtp_timeout,
                STREAM_CLIENT_CONNECT,
                $context
            );
        } else {
            // Fallback to original behavior if no options
            $this->_smtp_connect = fsockopen(
                $ssl . $this->smtp_host,
                $this->smtp_port,
                $errno,
                $errstr,
                $this->smtp_timeout
            );
        }

        if (!is_resource($this->_smtp_connect)) {
            $msg = "SMTP Connection Failed ($errno): $errstr. Host: $ssl$this->smtp_host, Port: $this->smtp_port";
            $this->_set_error_message('lang:email_smtp_error', $msg);
            log_message('error', "MY_Email: $msg");
            return FALSE;
        }

        stream_set_timeout($this->_smtp_connect, $this->smtp_timeout);
        $greeting = $this->_get_smtp_data();
        $this->_set_error_message($greeting);
        log_message('debug', "MY_Email Server Greeting: " . $greeting);

        if ($this->smtp_crypto === 'tls') {
            $this->_send_command('hello');
            $this->_send_command('starttls');

            // Apply stream crypto (TLS)
            // We need to pass the context here too if possible, but stream_socket_enable_crypto doesn't take context directly
            // typically context is applied at connection.
            // For TLS start, we might need manual handling if initial connection wasn't SSL.
            $crypto_method = STREAM_CRYPTO_METHOD_TLS_CLIENT;

            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $crypto_method |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
                $crypto_method |= STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT;
            }

            if (!stream_socket_enable_crypto($this->_smtp_connect, TRUE, $crypto_method)) {
                $this->_set_error_message('lang:email_smtp_error', $this->_get_smtp_data());
                return FALSE;
            }
        }

        return $this->_send_command('hello');
    }
}
