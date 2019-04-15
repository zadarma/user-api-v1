<?php

namespace Zadarma_API\Webhook;

class NotifyOutStart extends AbstractNotify
{
    /** @var string the call start time */
    public $call_start;

    /** @var string the call ID */
    public $pbx_call_id;

    /** @var string (optional) extension number */
    public $internal;

    /** @var string the phone number that was called */
    public $destination;


    public function getSignatureString()
    {
        return $this->internal.$this->destination.$this->call_start;
    }
}