<?php

namespace App\Enums;

enum TransactionTypeEnum
{
    const DEPOSIT = 'deposit';

    const WITHDRAWAL = 'withdrawal';
    const FEES = 'fees';
    const Send = 'send';
    const RESCEIVE = 'resceive';
}
