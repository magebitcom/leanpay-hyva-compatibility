<?php
declare(strict_types=1);

namespace Leanpay\PaymentHyva\Magewire\Checkout\Payment\Method;

use Leanpay\PaymentHyva\Magewire\Checkout\MagewireComponent;

class Leanpay extends MagewireComponent
{
    // Refresh installments on shipping method change
    protected $listeners = [
        'shipping_method_selected' => 'refresh',
    ];
}
