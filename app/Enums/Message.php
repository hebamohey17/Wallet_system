<?php

namespace App\Enums;

enum Message
{
    const WRONG_CREDENTIALS = 'credentialsNotCorrect';
    const PHONE_SHOULD_NOT_CONTAIN_LETTERS = 'phoneNumberShouldNotHasLetters';
    const TERMS_NOT_ACCEPTED = 'termsNotAccepted';
    const USER_NOT_FOUND = 'userNotFound';
    const WALLET_DEPOSIT_ERROR = 'wallet_deposit_error';
    const WALLET_BALANCE_ERROR = 'WALLET_BALANCE_ERROR';
}
