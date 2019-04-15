<?php


namespace Zadarma_API\Webhook;


class NotifyIvr extends NotifyStart
{
    /* optional parameters */
    public $ivr_saydigits;
    public $ivr_saynumber;
    /** @var WaitDtmf|null  */
    public $wait_dtmf;

    public function __construct($values)
    {
        parent::__construct($values);
        $this->wait_dtmf = !empty($this->wait_dtmf) ? new WaitDtmf($this->wait_dtmf) : null;
    }
}