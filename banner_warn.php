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
            $this->load_config('config.inc.php.dist');
            $this->load_config('config.inc.php');

            $this->include_script('banner_warn.js');
            $this->include_stylesheet('banner_warn.css');

            $this->add_hook('storage_init', array($this, 'storage_init'));
            $this->add_hook('message_objects', array($this, 'warn'));
            $this->add_hook('messages_list', array($this, 'mlist'));
        }

        public function storage_init($p)
        {
	    
            $RCMAIL = rcmail::get_instance();
	    $x_spam_header = $RCMAIL->config->get('x_spam_header', 'x-spam-status');
            $p['fetch_headers'] = trim($p['fetch_headers'] . ' ' . strtoupper($x_spam_header) . ' ' . strtoupper('X-Spam-Level'). ' ' . strtoupper('Received-SPF'));
            return $p;
        }

        public function warn($args)
        {
            $this->add_texts('localization/');

            // Preserve exiting headers
            $content = $args['content'];
            $message = $args['message'];

            // Safety check
            if ($message === NULL || $message->sender === NULL || $message->headers->others === NULL) {
                return array();
            }

            // Warn users if mail from outside organization
            if ($this->addressExternal($message->sender['mailto'])) {
                array_push($content, '<div class="notice warning">' . $this->gettext('from_outsite') . '</div>');
            }

            // Check X-Spam-Status
            if ($this->isSpam($message->headers)) {
                array_push($content, '<div class="notice error">' . $this->gettext('posible_spam') . '</div>');
            }

            // Check Received-SPF
            if ($this->spfFails($message->headers)) {
                array_push($content, '<div class="notice error">' . $this->gettext('spf_fail') . '</div>');
            }

            return array('content' => $content);
        }

        public function mlist($p)
        {
            if (!empty($p['messages'])) {
                $RCMAIL = rcmail::get_instance();

                // Check if avatars disabled
                if (!$RCMAIL->config->get('avatars', true)) return;

                $banner_avatar = array();
                foreach ($p['messages'] as $index => $message) {
                    // Create entry
                    $banner_avatar[$message->uid] = array();

                    // Parse from address
                    $from = rcube_mime::decode_address_list($message->from, 1, true, null, false)[1];

                    // Check if we have a from email address (uhh)
                    if (isset($from)) {
                        // Get first letter of name
                        $name = $from["name"];
                        if (empty($name)) {
                            $name = $from["mailto"];
                        }
                        $name = preg_replace("/[^A-Za-z0-9 ]/", '', $name);
                        $name = strtoupper($name[0]);

                        // Get md5 color from email
                        $color = substr(md5($from["mailto"]), 0, 6);
                    }

                    // Check for SPF fail
                    if (!isset($from) || $this->isSpam($message) || $this->spfFails($message)) {
                        $color = 'ff0000';
                        $name = '!';
                        $banner_avatar[$message->uid]['alert'] = 1;
                    }
                    else if ($RCMAIL->config->get('avatars_external_border', true) && $this->addressExternal($from["mailto"])) {
                        $banner_avatar[$message->uid]['warn'] = 1;
                    }

                    $banner_avatar[$message->uid]['name'] = $name;
                    $banner_avatar[$message->uid]['from'] = $from['mailto'];
                    $banner_avatar[$message->uid]['color'] = $color;
                }

                $RCMAIL->output->set_env('banner_avatar', $banner_avatar);
            }

            return $p;
        }

        private function addressExternal($address) {
            $RCMAIL = rcmail::get_instance();
            return (!preg_match($RCMAIL->config->get('org_email_regex'), $address));
        }

        private function spfFails($headers) {
            $spfStatus = $headers->others['received-spf'];
            return (isset($spfStatus) && (strpos(strtolower($spfStatus), 'pass') !== 0));
        }

        private function isSpam($headers) {
            $RCMAIL = rcmail::get_instance();

	    $x_spam_header = $RCMAIL->config->get('x_spam_header', 'x-spam-status');

            $spamStatus = $headers->others[$x_spam_header];
            if (isset($spamStatus) && (strpos(strtolower($spamStatus), 'yes') === 0)) return true;

            $spamLevel = $headers->others['x-spam-level'];
            return (isset($spamLevel) && substr_count($spamLevel, '*') >= $RCMAIL->config->get('spam_level_threshold', 3));
        }
    }

