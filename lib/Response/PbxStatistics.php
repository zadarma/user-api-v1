<?php


namespace Zadarma_API\Response;


class PbxStatistics extends Response
{
    /** @var string start date of the statistics display */
    public $start;

    /** @var string end date of the statistics display */
    public $end;

    /** @var integer format of the statistics result (2 - new, 1 - old) */
    public $version;

    /**
     * sip – SIP-number;  <br>
     * callstart – the call start time;  <br>
     * clid – CallerID  <br>
     * destination – the call destination;  <br>
     * disposition – the call status:  <br>
     * 'answered' – conversation,  <br>
     * 'busy' – busy,  <br>
     * 'cancel' - cancelled,  <br>
     * 'no answer' - no answer,  <br>
     * 'failed' - failed,  <br>
     * 'no money' - no funds, the limit has been exceeded,  <br>
     * 'unallocated number' - the phone number does not exist,  <br>
     * 'no limit' - the limit has been exceeded,  <br>
     * 'no day limit' - the day limit has been exceeded,  <br>
     * 'line limit' - the line limit has been exceeded,  <br>
     * 'no money, no limit' - the limit has been exceeded;  <br>
     * seconds – the amount of seconds;
     * is_recorded – (true, false) recorded or no conversations;
     * pbx_call_id – permanent ID of the external call to the PBX (does not alter with the scenario changes,
     *  voice menu, etc., it is displayed in the statistics and notifications);
     * @var array
     */
    public $stats;
}