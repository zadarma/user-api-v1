<?php

namespace Zadarma_API\Response;


class Redirection extends Response
{
    public $sip_id;
    public $status;
    public $condition;
    public $destination;
    public $destination_value;
}