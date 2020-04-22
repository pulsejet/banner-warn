<?php

    /**
     * Banner Warn
     *
     * Displays warnings to the user under various contexts
     *
     * @license MIT License: <http://opensource.org/licenses/MIT>
     * @author Varun Patil
     * @category  Plugin for RoundCube WebMail
     */
    class banner_warn extends rcube_plugin
    {
        public $task = 'mail';

        function init()
        {
            $this->load_config();
            $this->add_hook('storage_init', array($this, 'storage_init'));
            $this->add_hook('message_objects', array($this, 'warn'));
        }

        public function storage_init($p)
        {
            $p['fetch_headers'] = trim($p['fetch_headers'] . ' ' . strtoupper('X-Spam-Status') . ' ' . strtoupper('Received-SPF'));
            return $p;
        }

        public function warn($args)
        {
            $RCMAIL = rcmail::get_instance();

            // Preserve exiting headers
            $content = $args['content'];
            $message = $args['message'];

            // Safety check
            if ($message === NULL || $message->sender === NULL || $message->headers->others === NULL) {
                return array();
            }

            // Warn users if mail from outside organization
            if (!preg_match($RCMAIL->config->get('org_email_regex'), $message->sender['mailto'])) {
                array_push($content, '<p class="warning"> This mail
                    originated from outside of your organization. Do not click links or
                    open attachments unless you recognize the sender and know 
                    the content is safe.</p>');
            }

            // Check X-Spam-Status
            $spamStatus = $message->headers->others['x-spam-status'];
            if (isset($spamStatus) && (strpos(strtolower($spamStatus), 'yes') === 0)) {
                array_push($content, '<p class="error"> This mail
                    has been identified as possible spam. Be extra careful while interacting
                    with its contents.</p>');
            }

            // Check Received-SPF
            $spamStatus = $message->headers->others['received-spf'];
            if (isset($spamStatus) && (strpos(strtolower($spamStatus), 'pass') !== 0)) {
                array_push($content, '<p class="error"> The authenticity of the
                    sender of this email could not be verified. <br> Avoid clicking links,
                    downloading attachments or replying with personal information.</p>');
            }

            return array('content' => $content);
        }
    }

