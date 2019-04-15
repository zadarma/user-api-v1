<?php

namespace Zadarma_API\Webhook;

class NotifyEnd extends AbstractNotify
{
    /** @var string the call start time; */
    public $call_start;

    /** @var string the call ID; */
    public $pbx_call_id;

    /** @var string the caller's phone number; */
    public $caller_id;

    /** @var string the phone number that was called; */
    public $called_did;

    /** @var string (optional) extension number; */
    public $internal;

    /** @var string length in seconds; */
    public $duration;
    /** @var string call status: 'answered', 'busy', 'cancel', 'no answer', 'failed', 'no money', 'unallocated
     * number', 'no limit', 'no day limit', 'line limit', 'no money, no limit';
     */
    public $disposition;

    /** @var string call status code Q.931; */
    public $status_code;

    /** @var string 1 - there is a call recording, 0 - there is no call recording; */
    public $is_recorded;

    /** @var string the ID of the call with call recording (we recommend you to download the recorded file in 40 seconds after the notification, as certain time period is needed for the file with the recording to be saved).
     */
    public $call_id_with_rec;

    public function getSignatureString()
    {
        return $this->caller_id.$this->called_did.$this->call_start;
    }
}