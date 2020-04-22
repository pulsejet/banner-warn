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
            $this->add_hook('message_objects', array($this, 'warn'));
        }

        function warn($args)
        {
            $RCMAIL = rcmail::get_instance();

            // Preserve exiting headers
            $content = $args['content'];
            $message = $args['message'];

            // Safety check
            if ($message === NULL && $message->sender === NULL) {
                return array();
            }

            // Warn users if mail from outside organization
            if (!preg_match($RCMAIL->config->get('org_email_regex'), $message->sender['mailto'])) {
                array_push($content, '<p class="warning"> This mail
                    originated from outside of your organization. Do not click links or
                    open attachments unless you recognize the sender and know 
                    the content is safe</p>');
            }

            return array('content' => $content);
        }
    }

