<?php


namespace Zadarma_API\Response;


class DirectNumber extends Response
{
    /** @var string the user's purchased virtual phone number */
    public $number;

    /** @var string the phone number status */
    public $status;

    /** @var string country (for common and revenue) */
    public $country;

    /** @var string description: city or type (for common and revenue) */
    public $description;

    /** @var string the virtual phone number "name" (set by the user) */
    public $number_name;

    /** @var integer the SIP connected to the phone number */
    public $sip;

    /** @var string the "name" of the SIP connected to the phone number */
    public $sip_name;

    /** @var string the date of purchase */
    public $start_date;

    /** @var string the end date of the user's payment period */
    public $stop_date;

    /** @var float the phone number cost (for common) */
    public $monthly_fee;

    /** @var string the currency of the phone number cost (for common) */
    public $currency;

    /** @var integer the number of lines on the phone number (for common) */
    public $channels;

    /** @var bool the total duration of incoming calls for the current month (for revenue) */
    public $minutes;

    /** @var bool the automatic phone number extension is enabled or disabled
     * (for common, revenue, rufree)
     */
    public $autorenew;

    /** @var string the phone number is being tested or not. */
    public $is_on_test;

    /** @var string phone number type: common (virtual number), inum (free international number),
     * rufree (free Moscow number), revenue (free Moscow 495 number).
     */
    public $type;
}