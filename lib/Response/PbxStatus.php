<?php
namespace Zadarma_API\Response;

class PbxStatus extends Response
{
    /** @var integer PBX ID */
    public $pbx_id;

    /** @var integer PBX extension number */
    public $number;

    /** @var bool online-status (true|false) */
    public $is_online;
}