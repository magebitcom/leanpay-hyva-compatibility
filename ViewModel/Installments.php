<?php
declare(strict_types=1);

namespace Leanpay\PaymentHyva\ViewModel;

use Leanpay\Payment\Block\Installment\Pricing\Render\TemplatePriceBox;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Installments implements ArgumentInterface
{
    private const TEMPLATE_CHECKOUT = 'Leanpay_PaymentHyva::pricing/render/installment.phtml';

    /**
     * @var array<string,string>
     */
    private array $templateCache = [];

    /**
     * @param TemplatePriceBox $templatePriceBox
     * @param Session $checkoutSession
     */
    public function __construct(
        private readonly TemplatePriceBox $templatePriceBox,
        private readonly Session $checkoutSession,
    ) {
    }

    /**
     * Retrieves HTML for checkout
     *
     * @return string
     */
    public function getCheckoutHtml(): string
    {
        return $this->getHtmlFromCache(
            (float)$this->checkoutSession->getQuote()->getGrandTotal(),
            true
        );
    }

    /**
     * Retrieves HTML from cache
     *
     * @param float $amount
     * @param bool $isCheckout
     *
     * @return string
     */
    private function getHtmlFromCache(float $amount, bool $isCheckout = false): string
    {
        $cacheKey = $amount . '-' . (int)$isCheckout;

        if (!isset($this->templateCache[$cacheKey])) {
            $this->templateCache[$cacheKey] = $this->templatePriceBox
                ->setData('amount', $amount)
                ->setData('is_checkout', $isCheckout)
                ->setTemplate(self::TEMPLATE_CHECKOUT)
                ->toHtml() ?? '';
        }

        return $this->templateCache[$cacheKey];
    }
}
