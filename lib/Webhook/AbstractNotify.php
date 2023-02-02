<?php

namespace Zadarma_API\Webhook;

abstract class AbstractNotify extends \Zadarma_API\Response\Response
{
    const EVENT_START = 'NOTIFY_START';
    const EVENT_INTERNAL = 'NOTIFY_INTERNAL';
    const EVENT_ANSWER = 'NOTIFY_ANSWER';
    const EVENT_END = 'NOTIFY_END';
    const EVENT_OUT_START = 'NOTIFY_OUT_START';
    const EVENT_OUT_END = 'NOTIFY_OUT_END';
    const EVENT_RECORD = 'NOTIFY_RECORD';
    const EVENT_IVR = 'NOTIFY_IVR';
    const EVENT_SPEECH_RECOGNITION = 'SPEECH_RECOGNITION';
    const EVENT_SMS = 'SMS';

    public $event;

    abstract public function getSignatureString();
}