<?php

namespace Zadarma_API\Response;


class Statistics extends Response
{
    /** @var string start date of the statistics display */
    public $start;

    /** @var string end date of the statistics display */
    public $end;

    /**
     * id – call ID;  <br>
     * sip – SIP-number;  <br>
     * callstart – the call start time;  <br>
     * description – description of call destination;  <br>
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
     * billseconds – the amount of seconds;  <br>
     * cost – the cost per minute of calls to this destination;  <br>
     * billcost – the cost of the paid minutes;  <br>
     * currency – the cost currency;  <br>
     * from – which number was used to make a call;  <br>
     * to – the phone number that was called.  <br>
     * @var array
     */
    public $stats;
}