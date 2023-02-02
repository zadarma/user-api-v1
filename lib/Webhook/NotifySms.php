<?php

namespace Zadarma_API\Webhook;

class NotifySms extends AbstractNotify
{
    /** @var string the sender's phone number */
    public $caller_id;

    /** @var string the receiver's phone number */
    public $caller_did;

    /** @var string text message */
    public $text;

    /** @var array text message */
    public $result;

    public function getSignatureString()
    {
        return $this->result;
    }
}