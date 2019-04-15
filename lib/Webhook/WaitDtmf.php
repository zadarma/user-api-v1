<?php


namespace Zadarma_API\Webhook;


use Zadarma_API\Response\Response;

class WaitDtmf extends Response
{
    /** @var string dtmf name */
    public $name;

    /** @var string entered digits */
    public $digits;

    /** @var string default behaviour is active */
    public $default_behaviour;

}