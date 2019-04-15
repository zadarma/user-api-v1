<?php

namespace Zadarma_API\Response;


class NumberLookup extends Response
{
    public $mcc;
    public $mnc;
    public $mccName;
    public $mncName;
    public $ported;
    public $roaming;
    public $errorDescription;
    public $status;
}