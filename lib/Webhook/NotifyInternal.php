<?php

namespace Zadarma_API\Webhook;

class NotifyInternal extends AbstractNotify
{
    /** @var string the call start time */
    public $call_start;

    /** @var string the call ID */
    public $pbx_call_id;

    /** @var string the caller's phone number */
    public $caller_id;

    /** @var string the phone number that was called */
    public $called_did;

    /** @var string (optional) extension number */
    public $internal;


    public function getSignatureString()
    {
        return $this->caller_id.$this->called_did.$this->call_start;
    }
}