<?php

use Zadarma_API\Api;
use Zadarma_API\Webhook\AbstractNotify;
use Zadarma_API\Webhook\NotifyIvr;
use Zadarma_API\Webhook\NotifyStart;
use Zadarma_API\Webhook\Request;

require_once __DIR__.DIRECTORY_SEPARATOR.'include.php';


class WebhookExample {
    private static $api = null;

    // TODO: replace 'xxxxxxxxxxxxxxxx' values with real file id form sound manager window
    const INFO_FILE_ID = 'xxxxxxxxxxxxxxxx';
    const WRONG_INPUT_FILE_ID = 'xxxxxxxxxxxxxxxx';

    const DTMF_NAME_2 = 'date';
    const DTMF_NAME_3 = 'menu';

    // You could set any nesting level for menu.
    // Digits '4' and '5' are reserved (see example3).
    public static $menu = [
        'file' => 'xxxxxxxxxxxxxxxx',
        1 => [
            'file' => 'xxxxxxxxxxxxxxxx',
            1 => [
                'file' => 'xxxxxxxxxxxxxxxx',
                1 => [
                    'file' => 'xxxxxxxxxxxxxxxx',
                ],
            ],
            2 => [
                'file' => 'xxxxxxxxxxxxxxxx',
            ],
        ],
        2 => [
            'file' => 'xxxxxxxxxxxxxxxx',
        ],
    ];

    /**
     * The system dictates the last 3 digits of the caller's number and exits.
     */
    public static function example1()
    {
        /** @var NotifyStart $notify */
        $notify = self::getEvent([AbstractNotify::EVENT_START, AbstractNotify::EVENT_IVR]);
        if (!$notify) {
            return;
        }
        $request = new Request();
        if ($notify->event == AbstractNotify::EVENT_START) {
            $request->setIvrSayDigits(mb_substr($notify->caller_id, -3), 'en');
        } else {
            $request->setHangup();
        }
        $request->send();
    }

    /**
     * Enter the date of birth through dtmf and the system will say how many days are left.
     */
    public static function example2()
    {
        $request = new Request();
        $notify = self::getEvent([AbstractNotify::EVENT_START, AbstractNotify::EVENT_IVR]);
        if ($notify->event == AbstractNotify::EVENT_START) {
            /** @var NotifyStart $notify */
            $request
                ->setIvrPlay(self::INFO_FILE_ID)
                ->setWaitDtmf(7, 1, 4, self::DTMF_NAME_2, 'hangup')
                ->send();
        } elseif ($notify->event == AbstractNotify::EVENT_IVR) {
            /** @var NotifyIvr $notify */
            if (!$notify->wait_dtmf
                || $notify->wait_dtmf->name != self::DTMF_NAME_2
                || $notify->wait_dtmf->default_behaviour
            ) {
                $request
                    ->setHangup()
                    ->send();
            } else {

                if (mb_strlen($notify->wait_dtmf->digits) == 4) {
                    $month = mb_substr($notify->wait_dtmf->digits, 0, 2);
                    $day = mb_substr($notify->wait_dtmf->digits, 2);
                    if ($month > 0 && $month <= 12 && $day > 0 && $day <= date('t', mktime(0, 0, 0, $month))) {
                        $birth = (new DateTime())->setDate(date('Y'), $month, $day);
                        $diff = (new DateTime())->diff($birth)->format('%r%a');
                        if ($diff < 0) {
                            $diff += (date('L') ? 366 : 365);
                        }

                        $request
                            ->setIvrSayNumber($diff, 'en')
                            ->send();
                        return;
                    }
                }

                $request
                    ->setIvrPlay(self::WRONG_INPUT_FILE_ID)
                    ->setWaitDtmf(7, 1, 4, self::DTMF_NAME_2, 'hangup')
                    ->send();
            }
        }
    }

    /**
     * Implementation of multi-level menu.
     * Enter '1' or '2' to go to the next menu, '4' to return to the level above, '5' for hangup.
     */
    public static function example3()
    {
        $request = new Request();
        /** @var NotifyIvr $notify */

        if ($notify = self::getEvent([AbstractNotify::EVENT_START])) {
            $request
                ->setIvrPlay(self::$menu['file'])
                ->setWaitDtmf(5, 1, 1, self::DTMF_NAME_3, 'hangup')
                ->send();
            return;
        }

        $notify = self::getEvent([AbstractNotify::EVENT_IVR]);
        if (!empty($notify->wait_dtmf->digits) && mb_strpos($notify->wait_dtmf->name, self::DTMF_NAME_3) === 0) {
            $menu = mb_substr($notify->wait_dtmf->name, mb_strlen(self::DTMF_NAME_3));

            switch ($notify->wait_dtmf->digits){
                case 1:
                case 2:
                case 3:
                    $menu .= $notify->wait_dtmf->digits;
                    break;

                case 4:
                    if ($menu) {
                        $menu = mb_substr($menu, 0, mb_strlen($menu) - 1);
                    }
                    break;

                case 5:
                default:
                    $request->setHangup()->send();
                    return;
            }

            list($menu, $file) = self::getMenuFile($menu);
            $request
                ->setIvrPlay($file)
                ->setWaitDtmf(5, 1, 1, self::DTMF_NAME_3.$menu, 'hangup')
                ->send();
        }
    }

    private static function getEvent($allowedTypes)
    {
        if (self::$api === null) {
            self::$api = new Api(KEY, SECRET, true);
        }
        return self::$api->getWebhookEvent($allowedTypes);
    }

    private static function getMenuFile($menuDigits)
    {
        $menuDigits = (string)$menuDigits;
        $menuDigitsResult = '';
        $menu = self::$menu;
        while ($menuDigits) {
            $currentDigit = mb_substr($menuDigits, 0, 1);
            $menuDigits = mb_substr($menuDigits, 1);
            if (isset($menu[$currentDigit])) {
                $menu = $menu[$currentDigit];
                $menuDigitsResult .= $currentDigit;
            } else {
                break;
            }
        }
        return [$menuDigitsResult, $menu['file']];
    }
}

WebhookExample::example1();