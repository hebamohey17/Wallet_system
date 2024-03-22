<?php

namespace App\Common;

class ResponseMessage
{
    public $messageKey = '';

    public function __construct($messageKey)
    {
        $this->messageKey = $messageKey;
    }

    public function get(): string
    {
        return trans($this->messageKey);
    }
}
