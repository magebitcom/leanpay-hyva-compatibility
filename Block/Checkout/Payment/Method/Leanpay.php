<?php
/**
 * @author Magebit <info@magebit.com>
 * @copyright Copyright (c) Magebit, Ltd. (https://magebit.com)
 * @license https://magebit.com/code-license
 */

declare(strict_types=1);

namespace Leanpay\PaymentHyva\Block\Checkout\Payment\Method;

use Leanpay\Payment\Helper\InstallmentHelper;
use Magento\Framework\View\Element\Template;

class Leanpay extends Template
{
    /**
     * @param InstallmentHelper $installmentHelper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        private readonly InstallmentHelper $installmentHelper,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Intercept metadata reads to inject the theme-aware icon URL.
     *
     * @param string|null $key
     * @param string|int|null $index
     * @return mixed
     */
    public function getData($key = '', $index = null): mixed
    {
        if ($key === 'metadata') {
            $metadata = parent::getData('metadata') ?? [];
            $iconPath = $this->installmentHelper->getColorTheme()['icons'];
            $src = 'Leanpay_Payment::images/' . $iconPath . '/leanpay.svg';
            $metadata['icon']['src'] = $src;

            return $metadata;
        }

        return parent::getData($key, $index);
    }
}
