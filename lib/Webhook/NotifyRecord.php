<?php

namespace Zadarma_API\Webhook;

class NotifyRecord extends AbstractNotify
{
    /** @var string  unique ID of the call with the call recording */
    public $call_id_with_rec;

    /** @var string  permanent ID of the external call to the PBX (does not alter with the scenario changes, voice menu, etc., it is displayed in the statistics and notifications) */
    public $pbx_call_id;

    public function getSignatureString()
    {
        return $this->pbx_call_id.$this->call_id_with_rec;
    }
}