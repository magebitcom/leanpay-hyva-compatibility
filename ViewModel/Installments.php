<?php
declare(strict_types=1);

namespace Leanpay\PaymentHyva\ViewModel;

use Hyva\Theme\ViewModel\CurrentProduct;
use Leanpay\Payment\Block\Installment\Pricing\Render\TemplatePriceBox;
use Magento\Catalog\Block\Product\View;
use Magento\Checkout\Model\Session;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\LayoutInterface;

class Installments implements ArgumentInterface
{
    /**
     * @var array<string,string>
     */
    private array $templateCache = [];

    /**
     * @param TemplatePriceBox $templatePriceBox
     * @param Session $checkoutSession
     * @param LayoutInterface $layout
     * @param SerializerInterface $serializer
     * @param CurrentProduct $currentProduct
     */
    public function __construct(
        private readonly TemplatePriceBox $templatePriceBox,
        private readonly Session $checkoutSession,
        private readonly LayoutInterface $layout,
        private readonly SerializerInterface $serializer,
        private readonly CurrentProduct $currentProduct,

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
     * Check if current page is PDP
     *
     * @return bool
     */
    public function isProductPage(): bool
    {
        return in_array('catalog_product_view', $this->layout->getUpdate()->getHandles());
    }

    /**
     * Get current product ID
     *
     * @return int
     */
    public function getProductId(): int
    {
        return (int)$this->currentProduct->get()?->getId();
    }

    /**
     * Get installment value map
     *
     * @return string
     */
    public function getInstallmentMap(): string
    {
        if (!$this->isProductPage()) {
            return '{}';
        }

        $block = $this->layout->createBlock(View::class);

        $installmentMap = $this->serializer->unserialize($block->getJsonConfig());

        return (string)$this->serializer->serialize($installmentMap['installmentHtmlMap'] ?? []);
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
                ->toHtml() ?? '';
        }

        return $this->templateCache[$cacheKey];
    }
}
