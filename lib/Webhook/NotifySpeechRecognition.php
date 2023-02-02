<?php

namespace Zadarma_API\Webhook;

class NotifySpeechRecognition extends AbstractNotify
{
    /** @var string language */
    public $lang;

    /** @var string recording filename */
    public $call_id;

    /** @var string the call ID */
    public $pbx_call_id;

    /** @var string optional flag meaning recording is voicemail */
    public $voicemail;

    /** @var array recognition result */
    public $result;

    public function getSignatureString()
    {
        return $this->result;
    }
}