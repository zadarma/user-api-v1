<?php

namespace Zadarma_API;

use Zadarma_API\Response\Balance;
use Zadarma_API\Response\DirectNumber;
use Zadarma_API\Response\IncomingCallsStatistics;
use Zadarma_API\Response\NumberLookup;
use Zadarma_API\Response\PbxInfo;
use Zadarma_API\Response\PbxInternal;
use Zadarma_API\Response\PbxRecording;
use Zadarma_API\Response\PbxRecordRequest;
use Zadarma_API\Response\PbxRedirection;
use Zadarma_API\Response\PbxStatistics;
use Zadarma_API\Response\PbxStatus;
use Zadarma_API\Response\Price;
use Zadarma_API\Response\Redirection;
use Zadarma_API\Response\SipRedirection;
use Zadarma_API\Response\SipRedirectionStatus;
use Zadarma_API\Response\RequestCallback;
use Zadarma_API\Response\Sip;
use Zadarma_API\Response\SipCaller;
use Zadarma_API\Response\SipStatus;
use Zadarma_API\Response\Sms;
use Zadarma_API\Response\SpeechRecognition;
use Zadarma_API\Response\Statistics;
use Zadarma_API\Response\Tariff;
use Zadarma_API\Response\Timezone;
use Zadarma_API\Response\WebrtcKey;
use Zadarma_API\Response\Zcrm;
use Zadarma_API\Webhook\AbstractNotify;
use Zadarma_API\Webhook\NotifyAnswer;
use Zadarma_API\Webhook\NotifyEnd;
use Zadarma_API\Webhook\NotifyInternal;
use Zadarma_API\Webhook\NotifyIvr;
use Zadarma_API\Webhook\NotifyOutEnd;
use Zadarma_API\Webhook\NotifyOutStart;
use Zadarma_API\Webhook\NotifyRecord;
use Zadarma_API\Webhook\NotifyStart;

class Api extends Client
{
    const VERSION = 'v1';

    const PBX_REDIRECTION_NO_GREETING = 'no';
    const PBX_REDIRECTION_STANDART_GREETING = 'standart';
    const PBX_REDIRECTION_OWN_GREETING = 'own';

    const IN_CALLS = 'in';
    const OUT_CALLS = 'out';

    /**
     * Return user balance.
     *
     * @return Balance
     * @throws ApiException
     */
    public function getBalance()
    {
        $data = $this->request('info/balance');
        return new Balance($data);
    }

    /**
     * Return call rate in the user's current price plan.
     *
     * @param string $number
     * @param null|string $callerId
     * @return Price
     * @throws ApiException
     */
    public function getPrice($number, $callerId = null)
    {
        $params = ['number' => self::filterNumber($number)];
        if ($callerId) {
            $params['caller_id'] = self::filterNumber($number);
        }
        $data = $this->request('info/price', $params);
        return new Price($data['info']);
    }

    /**
     * Return user's timezone.
     *
     * @return Timezone
     * @throws ApiException
     */
    public function getTimezone()
    {
        $data = $this->request('info/timezone');
        return new Timezone($data);
    }

    /**
     * Return information about the user's current price plan.
     *
     * @return Tariff
     * @throws ApiException
     */
    public function getTariff()
    {
        $data = $this->request('tariff');
        return new Tariff($data['info']);
    }

    /**
     * Request a callback.
     * @see https://zadarma.com/en/services/calls/callback/
     *
     * @param string from Your phone/SIP number, the PBX extension number or the PBX scenario,
     *  to which the CallBack is made.
     * @param string to The phone or SIP number that is being called.
     * @param null|string sip SIP user's number or the PBX extension number,
     *  which is used to make the call.
     * @param null|string predicted If this flag is specified the request is predicted
     *  (the system calls the “to” number, and only connects it to your SIP, or your phone number,
     *  if the call is successful.);
     * @return RequestCallback
     * @throws ApiException
     */
    public function requestCallback($from, $to, $sip = null, $predicted = null)
    {
        $params = [
            'from' => $from,
            'to' => self::filterNumber($to),
        ];
        $params = $params + self::filterParams([
            'sip' => is_null($sip) ? null : self::filterNumber($sip),
            'predicted' => $predicted,
        ]);
        $data = $this->request('request/callback', $params);
        return new RequestCallback($data);
    }

    /**
     * Return the list of user's SIP-numbers.
     *
     * @return array
     * @throws ApiException
     */
    public function getSip()
    {
        $data = $this->request('sip');
        unset($data['status']);
        if (is_array($data['sips']) && $data['sips']) {
            foreach ($data['sips'] as &$sipData) {
                $sipData = new Sip($sipData);
            }
        }
        return $data;
    }

    /**
     * Return the user's SIP number online status.
     *
     * @param $sipId
     * @return SipStatus
     * @throws ApiException
     */
    public function getSipStatus($sipId)
    {
        $data = $this->request('sip/' . self::filterNumber($sipId) . '/status');
        return new SipStatus($data);
    }

    /**
     * Return the current call forwarding based on the user's SIP numbers.
     *
     * @param null|integer $sipId Selection of the specific SIP ID.
     * @return Redirection[]
     * @throws ApiException
     */
    public function getSipRedirection($sipId = null)
    {
        $params = $sipId ? ['id' => self::filterNumber($sipId)] : [];
        $data = $this->request('sip/redirection', $params);
        return self::arrayToResultObj($data['info'], Response\Redirection::class);
    }

    /**
     * Return information about the user's phone numbers.
     * @return DirectNumber[]
     * @throws ApiException
     */
    public function getDirectNumbers()
    {
        $data = $this->request('direct_numbers');
        return self::arrayToResultObj($data['info'], DirectNumber::class);
    }

    /**
     * Return online status of the PBX extension number.
     * @return PbxInternal
     * @throws ApiException
     */
    public function getPbxInternal()
    {
        $data = $this->request('pbx/internal');
        return new PbxInternal($data);
    }

    /**
     * Return online status of the PBX extension number.
     * @param $pbxId
     * @return PbxStatus
     * @throws ApiException
     */
    public function getPbxStatus($pbxId)
    {
        $data = $this->request('pbx/internal/' . self::filterNumber($pbxId) . '/status');
        return new PbxStatus($data);
    }

    /**
     * Return information about the PBX extension number.
     * @param $pbxId
     * @return PbxInfo
     * @throws ApiException
     */
    public function getPbxInfo($pbxId)
    {
        $data = $this->request('pbx/internal/' . self::filterNumber($pbxId) . '/info');
        return new PbxInfo($data);
    }

    /**
     * Return call recording file request.
     * @param string|null $callId Unique call ID, it is specified in the name of the file with the call
     *  recording (unique for every recording)
     * @param string|null $pbxCallId Permanent ID of the external call to the PBX
     * @param integer|null $lifetime The link's lifetime in seconds (minimum - 180, maximum - 5184000, default - 1800)
     * @return PbxRecordRequest
     * @throws ApiException
     */
    public function getPbxRecord($callId, $pbxCallId, $lifetime = null)
    {
        $params = array_filter([
            'call_id' => $callId,
            'pbx_call_id' => $pbxCallId,
        ]);
        if (!$params) {
            throw new ApiException('callId or pbxCallId required');
        }
        if ($lifetime) {
            $params['lifetime'] = $lifetime;
        }
        $data = $this->request('pbx/record/request', $params);
        return new PbxRecordRequest($data);
    }

    /**
     * Return call forwarding parameters on the PBX extension number.
     * @param integer $pbxNumber PBX extension number
     * @return PbxRedirection
     * @throws ApiException
     */
    public function getPbxRedirection($pbxNumber)
    {
        $data = $this->request('pbx/redirection', ['pbx_number' => self::filterNumber($pbxNumber)]);
        return new PbxRedirection($data);
    }

    /**
     * Return overall statistics.
     * Maximum period of getting statistics is - 1 month. If the limit in the request is exceeded, the time period
     * automatically decreases to 30 days. If the start date is not specified, the start of the current month will be
     * selected. If the end date is not specified, the current date and time will be selected.
     *
     * @param string|null $start The start date of the statistics display (format - y-m-d H:i:s)
     * @param string|null $end The end date of the statistics display (format - y-m-d H:i:s)
     * @param integer|null $sip Filter based on a specific SIP number
     * @param bool|null $costOnly Display only the amount of funds spent during a specific period
     * @param string|null $type Request type: overall (is not specified in the request), toll and ru495
     * @param integer|null $skip Number of lines to be skipped in the sample. The output begins from skip +1 line.
     * @param integer|null $limit The limit on the number of input lines
     *  (the maximum value is 1000, the default value is 1000)
     * @return Statistics
     * @throws ApiException
     */
    public function getStatistics(
        $start = null,
        $end = null,
        $sip = null,
        $costOnly = null,
        $type = null,
        $skip = null,
        $limit = null
    ) {
        $params = [
            'start' => $start,
            'end' => $end,
            'sip' => is_null($sip) ? null : self::filterNumber($sip),
            'cost_only' => $costOnly,
            'type' => $type,
            'skip' => $skip,
            'limit' => $limit,
        ];
        $data = $this->request('statistics', self::filterParams($params));
        return new Statistics($data);
    }

    /**
     * Return PBX statistics.
     * @see Api::getStatistics() For $start, $end, $skip, $limit parameters details.
     *
     * @param string|null $start
     * @param string|null $end
     * @param true|bool $newFormat Format of the statistics result.
     * @param integer|null $skip
     * @param integer|null $limit
     * @param string|null $callType IN_CALLS for incoming calls, OUT_CALLS for outgoing, null for both
     * @return PbxStatistics
     * @throws ApiException
     */
    public function getPbxStatistics(
        $start = null,
        $end = null,
        $newFormat = true,
        $callType = null,
        $skip = null,
        $limit = null
    ) {
        $params = [
            'start' => $start,
            'end' => $end,
            'version' => $newFormat ? 2 : 1,
            'skip' => $skip,
            'limit' => $limit,
            'call_type' => $callType,
        ];
        $data = $this->request('statistics/pbx', self::filterParams($params));
        return new PbxStatistics($data);
    }

    /**
     * Return CallBack widget statistics.
     * @see Api::getStatistics() For $start and $end parameters details.
     *
     * @param string|null $start
     * @param string|null $end
     * @param string|null $widget_id
     * @return PbxStatistics
     * @throws ApiException
     */
    public function getCallbackWidgetStatistics($start = null, $end = null, $widget_id = null)
    {
        $params = [
            'start' => $start,
            'end' => $end,
            'widget_id' => $widget_id,
        ];
        $data = $this->request('statistics/callback_widget', self::filterParams($params));
        return new PbxStatistics($data);
    }

    /**
     * Return overall incoming calls statistics.
     * Maximum period of getting statistics is - 1 month. If the limit in the request is exceeded, the time period
     * automatically decreases to 30 days. If the start date is not specified, the start of the current month will be
     * selected. If the end date is not specified, the current date and time will be selected.
     *
     * @param string|null $start The start date of the statistics display (format - y-m-d H:i:s)
     * @param string|null $end The end date of the statistics display (format - y-m-d H:i:s)
     * @param integer|null $sip Filter based on a specific SIP number
     * @param integer|null $skip Number of lines to be skipped in the sample. The output begins from skip +1 line.
     * @param integer|null $limit The limit on the number of input lines
     *  (the maximum value is 1000, the default value is 1000)
     * @return IncomingCallsStatistics
     * @throws ApiException
     */
    public function getIncomingCallStatistics($start = null, $end = null, $sip = null, $skip = null, $limit = null)
    {
        $params = [
            'start' => $start,
            'end' => $end,
            'sip' => is_null($sip) ? null : self::filterNumber($sip),
            'skip' => $skip,
            'limit' => $limit,
        ];
        $data = $this->request('statistics/incoming-calls', self::filterParams($params));
        return new IncomingCallsStatistics($data);
    }

    /**
     * Changing of the CallerID.
     * @param integer id The SIP ID, which needs the CallerID to be changed;
     * @param string number The new (changed) phone number, in international format
     * (from the list of confirmed or purchased phone numbers).
     * @return SipCaller
     * @throws ApiException
     */
    public function setSipCallerId($sipId, $number)
    {
        $params = [
            'id' => self::filterNumber($sipId),
            'number' => self::filterNumber($number)
        ];
        $data = $this->request('sip/callerid', $params, 'put');
        return new SipCaller($data);
    }


    /**
     * Call forwarding switch on/off based on the SIP number.
     * @param integer $sipId
     * @param bool $statusOn True for 'on' and false for 'off' status.
     * @return SipRedirectionStatus
     * @throws ApiException
     */
    public function setSipRedirectionStatus($sipId, $statusOn)
    {
        $params = [
            'id' => self::filterNumber($sipId),
            'status' => $statusOn ? 'on' : 'off',
        ];
        $data = $this->request('sip/redirection', $params, 'put');
        return new SipRedirectionStatus($data);
    }

    /**
     * Changing of the call forwarding parameters.
     * @param integer $sipId
     * @param string $number phone number
     * @return SipRedirection
     * @throws ApiException
     */
    public function setSipRedirectionNumber($sipId, $number)
    {
        $params = [
            'id' => self::filterNumber($sipId),
            'type' => 'phone',
            'number' => self::filterNumber($number),
        ];
        $data = $this->request('sip/redirection', $params, 'put');
        return new SipRedirection($data);
    }

    /**
     * Enabling of the call recording on the PBX extension number.
     * @param integer $sipId
     * @param string $status One of the values: "on" - switch on, "off" - switch off, "on_email" - enable the option to
     * send the recordings to the email address only, "off_email" - disable the option to send the recordings to the
     * email address only, "on_store" - enable the option to save the recordings to the cloud, "off_store" - disable the
     * option to save the recordings to the cloud.
     * @param string|null $email (optional) change the email address, where the call recordings will be sent.
     * You can specify up to 3 email addresses, separated by comma.
     * @param string|null $speechRecognition  (optional) change the speech recognition settings: "all" - recognize all,
     * "optional" - recognize selectively in statistics, "off" - disable.
     * @return PbxRecording
     * @throws ApiException
     */
    public function setPbxRecording($sipId, $status, $email = null, $speechRecognition = null)
    {
        if (!in_array($status, ['on', 'off', 'on_email', 'off_email', 'on_store', 'off_store'])) {
            throw new \BadFunctionCallException('Wrong status parameter');
        }
        $params = [
            'id' => self::filterNumber($sipId),
            'status' => $status
        ];
        if ($email) {
            $params['email'] = $email;
        }
        if ($speechRecognition && !in_array($status, ['off', 'off_store'])) {
            if (!in_array($speechRecognition, ['all', 'optional', 'off'])) {
                throw new \BadFunctionCallException('Wrong speechRecognition parameter');
            }
            $params['speech_recognition'] = $speechRecognition;
        }
        $data = $this->request('pbx/internal/recording', $params, 'put');
        return new PbxRecording($data);
    }

    /**
     * Sending the SMS messages.
     *
     * @param string|array $to Phone number(s), where to send the SMS message (array of numbers can be specified);
     * @param string $message Message (standard text limit applies; the text will be separated into several SMS messages,
     *  if the limit is exceeded);
     * @param string $callerId Phone number, from which the SMS messages is sent (can be sent only from list of user's
     *  confirmed phone numbers).
     * @return Sms
     * @throws ApiException
     */
    public function sendSms($to, $message, $callerId = null)
    {
        $to = array_map([self::class, 'filterNumber'], is_array($to) ? $to : [$to]);
        $params = [
            'number' => implode(',', $to),
            'message' => $message,
        ];
        if ($callerId) {
            $params['caller_id'] = $callerId;
        }
        $data = $this->request('sms/send', $params, 'post');
        return new Sms($data);
    }

    /**
     * Number lookup for one phone number.
     * @param string $number Phone number.
     * @return NumberLookup
     * @throws ApiException
     */
    public function numberLookup($number)
    {
        $data = $this->request('info/number_lookup', ['numbers' => self::filterNumber($number)], 'post');
        return new NumberLookup($data['info']);
    }

    /**
     * Number lookup for multiple phone numbers.
     * @param string[] $numbers Phone number.
     * @throws ApiException
     */
    public function numberLookupMultiple($numbers)
    {
        $numbers = array_filter(array_map('\Zadarma_API\Api::filterNumber', $numbers));
        $this->request('info/number_lookup', ['numbers' => $numbers], 'post');
    }

    /**
     * Turn off call forwarding parameters on the PBX extension number.
     * @param integer $pbxNumber PBX extension number.
     * @return PbxRedirection
     * @throws ApiException
     */
    public function setPbxRedirectionOff($pbxNumber)
    {
        $params = [
            'pbx_number' => self::filterNumber($pbxNumber),
            'status' => 'off',
        ];
        $data = $this->request('pbx/redirection', $params, 'post');
        return new PbxRedirection($data);
    }

    /**
     * Turn on and setup call forwarding to phone on the PBX extension number.
     * @param integer $pbxNumber PBX extension number.
     * @param string $destination Phone number.
     * @param bool $always Always forward calls or only if there is no answer.
     * @param bool $setCallerId Setting up your CallerID during the call forwarding.
     * @return PbxRedirection
     * @throws ApiException
     */
    public function setPbxPhoneRedirection($pbxNumber, $destination, $always, $setCallerId)
    {
        $params = [
            'pbx_number' => self::filterNumber($pbxNumber),
            'type' => 'phone',
            'condition' => $always ? 'always' : 'noanswer',
            'destination' => self::filterNumber($destination),
            'set_caller_id' => $setCallerId ? 'on' : 'off',
        ];

        $data = $this->request('pbx/redirection', $params, 'post');
        return new PbxRedirection($data);
    }

    /**
     * Turn on and setup call forwarding to voicemail on the PBX extension number.
     * @param integer $pbxNumber PBX extension number.
     * @param string $destination Email address.
     * @param bool $always Always forward calls or only if there is no answer.
     * @param string $greeting Notifications about call forwarding, possible values:
     *  Api::PBX_REDIRECTION_NO_GREETING, Api::PBX_REDIRECTION_STANDART_GREETING, Api::PBX_REDIRECTION_OWN_GREETING.
     * @param string $greetingFile Path to file with notification in mp3 format or wav below 5 MB.
     *  Specified only when greeting = own.
     * @return PbxRedirection
     * @throws ApiException
     */
    public function setPbxVoicemailRedirection($pbxNumber, $destination, $always, $greeting, $greetingFile = null)
    {
        if (!filter_var($destination, FILTER_VALIDATE_EMAIL)) {
            throw new \BadFunctionCallException('Wrong email parameter');
        }
        $allowedRedirections = [
            self::PBX_REDIRECTION_NO_GREETING,
            self::PBX_REDIRECTION_OWN_GREETING,
            self::PBX_REDIRECTION_STANDART_GREETING
        ];
        if (!in_array($greeting, $allowedRedirections)) {
            throw new \BadFunctionCallException('Wrong voicemailGreeting parameter');
        }
        $params = [
            'pbx_number' => self::filterNumber($pbxNumber),
            'type' => 'voicemail',
            'condition' => $always ? 'always' : 'noanswer',
            'destination' => $destination,
            'voicemail_greeting' => $greeting,
        ];
        if ($greeting == self::PBX_REDIRECTION_OWN_GREETING) {
            if (
                !$greetingFile
                || !file_exists($greetingFile)
                || !in_array(pathinfo($greetingFile, PATHINFO_EXTENSION), ['wav', 'mp3'])
            ) {
                throw new \BadFunctionCallException('Greeting file does not exist or has wrong extension.');
            }
            $params['greeting_file'] = curl_file_create($greetingFile);
        }
        $data = $this->request('pbx/redirection', $params, 'post');
        return new PbxRedirection($data);
    }

    /**
     * Start speech recognition.
     * @param string $callId Unique call ID, it is specified in the name of the file with the call
     *  recording (unique for every recording)
     * @param null|string $lang recognition language (not required)
     * @return bool
     * @throws ApiException
     */
    public function startSpeechRecognition($callId, $lang = null)
    {
        $params = [
            'call_id' => $callId,
        ];
        if ($lang) {
            $params['lang'] = $lang;
        }
        $data = $this->request('speech_recognition', $params, 'put');
        return $data['status'] == 'success';
    }

    /**
     * Obtaining recognition results.
     * @param string $callId Unique call ID, it is specified in the name of the file with the call
     *  recording (unique for every recording)
     * @param null|string $lang recognition language (not required)
     * @param bool $returnWords return words or phrases
     * @param bool $returnAlternatives return alternative results
     * @return SpeechRecognition
     * @throws ApiException
     */
    public function getSpeechRecognitionResult($callId, $lang = null, $returnWords = false, $returnAlternatives = false)
    {
        $params = [
            'call_id' => $callId,
            'return' => $returnWords ? 'words' : 'phrases',
            'alternatives' => (int)$returnAlternatives,
        ];
        if ($lang) {
            $params['lang'] = $lang;
        }
        $data = $this->request('speech_recognition', $params, 'get');
        return new SpeechRecognition($data);
    }

    /**
     * Return notify object populated from postData, depending on 'event' field.
     * If cannot match event to object, return null.
     * Perform signature test, before populating data.
     * Throw SignatureException in case of signature test failure.
     * @param array|null $eventFilter array of allowed events. If not specified, return all events.
     * Example: [AbstractNotify::EVENT_START, AbstractNotify::EVENT_IVR]
     * @param array|null $postData Data for model populating. If null, $_POST values used.
     * @param null $signature
     * @return null|NotifyStart
     */
    public function getWebhookEvent($eventFilter = null, $postData = null, $signature = null)
    {
        if ($postData === null) {
            $postData = $_POST;
        }
        if (empty($postData['event']) || ($eventFilter && !in_array($postData['event'], $eventFilter))) {
            return null;
        }

        if ($signature === null) {
            $headers = getallheaders();
            if (empty($headers['Signature'])) {
                return null;
            } else {
                $signature = $headers['Signature'];
            }
        }

        switch ($postData['event']) {
            case AbstractNotify::EVENT_START:
                $notify = new NotifyStart($postData);
                break;

            case AbstractNotify::EVENT_IVR:
                $notify = new NotifyIvr($postData);
                break;

            case AbstractNotify::EVENT_INTERNAL:
                $notify = new NotifyInternal($postData);
                break;

            case AbstractNotify::EVENT_ANSWER:
                $notify = new NotifyAnswer($postData);
                break;

            case AbstractNotify::EVENT_END:
                $notify = new NotifyEnd($postData);
                break;

            case AbstractNotify::EVENT_OUT_START:
                $notify = new NotifyOutStart($postData);
                break;

            case AbstractNotify::EVENT_OUT_END:
                $notify = new NotifyOutEnd($postData);
                break;

            case AbstractNotify::EVENT_RECORD:
                $notify = new NotifyRecord($postData);
                break;

            default:
                return null;
        }

        if ($signature != $this->encodeSignature($notify->getSignatureString())) {
            return null;
        }

        return $notify;
    }

    /**
     * @param $method
     * @param array $params
     * @param string $requestType
     * @return Zcrm
     * @throws ApiException
     */
    public function zcrmRequest($method, $params = [], $requestType = 'get')
    {
        $result = $this->call('/' . self::VERSION . '/zcrm' . $method, $params, $requestType);

        $result = json_decode($result, true);
        if ((!empty($result['status']) && $result['status'] == 'error') || $this->getHttpCode() >= 400) {
            throw new ApiException(isset($result['data']) ? $result['data'] : '', $this->getHttpCode());
        }
        if ($result === null) {
            throw new ApiException('Wrong response', $this->getHttpCode());
        }

        return new Zcrm($result);
    }

    /**
     * Get a key for a webrtc widget.
     * @param string $sipLogin SIP login or login of PBX extension number
     * @return WebrtcKey
     * @throws ApiException
     */
    public function getWebrtcKey($sipLogin)
    {
        return new WebrtcKey($this->request('webrtc/get_key', ['sip' => $sipLogin]));
    }

    /**
     * Make request to api with error checking.
     *
     * @param $method
     * @param array $params
     * @param string $requestType
     * @return array
     * @throws ApiException
     */
    public function request($method, $params = [], $requestType = 'get')
    {
        $result = $this->call('/' . self::VERSION . '/' . $method . '/', $params, $requestType);

        $result = json_decode($result, true);
        if ((!empty($result['status']) && $result['status'] == 'error') || $this->getHttpCode() >= 400) {
            throw new ApiException($result['message'], $this->getHttpCode());
        }
        if ($result === null) {
            throw new ApiException('Wrong response', $this->getHttpCode());
        }
        return $result;
    }

    /**
     * Filter from non-digit symbols.
     *
     * @param string $number
     * @return string
     * @throws ApiException
     */
    protected static function filterNumber($number)
    {
        $number = preg_replace('/\D/', '', $number);
        if (!$number) {
            throw new ApiException('Wrong number format.');
        }
        return $number;
    }

    /**
     * Remove null value items from params.
     * @param $params
     * @return mixed
     */
    protected static function filterParams($params)
    {
        foreach ($params as $k => $v) {
            if (is_null($v)) {
                unset($params[$k]);
            }
        }
        return $params;
    }

    /**
     * Convert items of array to object of given class name.
     *
     * @param array $array
     * @param string $resultClassName
     * @return array
     */
    protected static function arrayToResultObj($array, $resultClassName)
    {
        foreach ($array as &$item) {
            $item = new $resultClassName($item);
        }
        return $array;
    }
}
