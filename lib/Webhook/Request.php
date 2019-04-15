<?php


namespace Zadarma_API\Webhook;


class Request
{
    protected $data = [];

    public static function getSupportedLanguages()
    {
        return ['ru', 'ua', 'en', 'es', 'pl'];
    }

    public function setIvrPlay($id)
    {
        $this->data['ivr_play'] = $id;
        return $this;
    }

    public function setIvrSayPopular($number, $language)
    {
        $this->data['ivr_saypopular'] = (int)$number;
        return $this->setLanguage($language);
    }

    public function setIvrSayDigits($number, $language)
    {
        $this->data['ivr_saydigits'] = (int)$number;
        return $this->setLanguage($language);
    }

    public function setIvrSayNumber($number, $language)
    {
        $this->data['ivr_saynumber'] = (int)$number;
        return $this->setLanguage($language);
    }

    public function setWaitDtmf($timeout, $attempts, $maxdigits, $name, $default)
    {
        $this->data['wait_dtmf'] = [
            'timeout' => (int)$timeout,
            'attempts' => (int)$attempts,
            'maxdigits' => (int)$maxdigits,
            'name' => $name,
            'default' => $default,
        ];
        return $this;
    }

    public function setRedirect($redirect, $returnTimeout)
    {
        $this->data['redirect'] = $redirect;
        $this->data['return_timeout'] = (int)$returnTimeout;
        return $this;
    }

    public function setHangup()
    {
        $this->data['hangup'] = 1;
        return $this;
    }

    public function setCallerName($name)
    {
        $this->data['caller_name'] = $name;
        return $this;
    }

    /**
     * @param string $language one of values returned by getSupportedLanguages() method
     * @return $this
     */
    public function setLanguage($language)
    {
        if (!in_array($language, self::getSupportedLanguages())) {
            throw new \BadMethodCallException('Wrong language.');
        }
        $this->data['language'] = $language;
        return $this;
    }

    /**
     * Validate data prepared for send and return it or sends it and terminate script.
     * @param bool $return
     * @return string
     */
    public function send($return = false)
    {
        if (isset($this->data['ivr_play']) && !isset($this->data['wait_dtmf'])) {
            throw new \BadMethodCallException('setWaitDtmf must be called before send.');
        }

        $result = json_encode($this->data);
        if ($return) {
            return $result;
        } else {
            echo $result;
            exit;
        }
    }
}