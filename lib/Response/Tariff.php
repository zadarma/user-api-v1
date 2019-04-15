<?php

namespace Zadarma_API\Response;


class Tariff extends Response
{
    /** @var integer User's current price plan ID. */
    public $tariff_id;

    /** @var string The name of the user's current price plan. */
    public $tariff_name;

    /** @var bool The current price plan is active or inactive. */
    public $is_active;

    /** @var float The cost of the price plan. */
    public $cost;

    /** @var string The price plan currency. */
    public $currency;

    /** @var integer The amount of price plan seconds used. */
    public $used_seconds;

    /** @var integer The amount of price plan seconds used on calls to mobiles. */
    public $used_seconds_mobile;

    /** @var integer The amount of price plan seconds used on calls to landlines. */
    public $used_seconds_fix;

    /** @var integer The user's price plan ID for the next time period. */
    public $tariff_id_for_next_period;

    /** @var string The name of the user's price plan for the next time period. */
    public $tariff_for_next_period;
}