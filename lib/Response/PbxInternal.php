<?php

namespace Zadarma_API\Response;


class PbxInternal extends Response
{
    /** @var integer the user's PBX ID */
    public $pbx_id;

    /** @var integer[] the list of extension numbers */
    public $numbers;
}