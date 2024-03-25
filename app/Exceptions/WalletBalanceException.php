<?php

namespace App\Exceptions;

use App\Common\Response;
use App\Enums\Message;
use Exception;

class WalletBalanceException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return (new Response)->exception(Message::WALLET_BALANCE_ERROR, 404);
    }
}
