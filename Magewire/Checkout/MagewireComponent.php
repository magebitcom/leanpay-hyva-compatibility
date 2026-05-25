<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Leanpay\PaymentHyva\Magewire\Checkout;

if (!class_exists("\Magewirephp\Magewire\Component")) {
    class MagewireComponent {}
} else {
    class MagewireComponent extends \Magewirephp\Magewire\Component {}
}
