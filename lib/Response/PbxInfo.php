<?php
namespace Zadarma_API\Response;

class PbxInfo extends Response
{
    /** @var integer PBX ID */
    public $pbx_id;

    /** @var integer PBX extension number */
    public $number;

    /** @var string display name */
    public $name;

    /** @var string CallerID */
    public $caller_id;

    /** @var string change the CallerID from the app (true|false) */
    public $caller_id_app_change;

    /** @var string CallerID by direction (true|false) */
    public $caller_id_by_direction;

    /** @var string number of lines */
    public $lines;

    /** @var string ip access restriction (false if omitted) */
    public $ip_restriction;

    /** @var string record conversations to the cloud (Without recognition|For manual recognition|For automatic speech recognition|false) */
    public $record_store;

    /** @var string email for sending conversation recordings (false if not enabled) */
    public $record_email;
}