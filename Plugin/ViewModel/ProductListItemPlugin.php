<?php
/**
 * @author    Magebit <info@magebit.com>
 * @copyright Copyright (c) Magebit, Ltd. (https://magebit.com)
 * @license   https://magebit.com/code-license
 */

declare(strict_types=1);

namespace Leanpay\PaymentHyva\Plugin\ViewModel;

use Hyva\Theme\ViewModel\ProductListItem;
use Leanpay\Payment\Helper\InstallmentHelper;
use Leanpay\Payment\Pricing\Price\Installment;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\LayoutInterface;

class ProductListItemPlugin
{
    /**
     * @param LayoutInterface $layout
     */
    public function __construct(
        private readonly LayoutInterface $layout,
    ) {
    }

    /**
     * Append installment price block to PLP
     *
     * @param ProductListItem $subject
     * @param string $result
     * @param Product $product
     *
     * @return string
     */
    public function afterGetProductPriceHtml(ProductListItem $subject, string $result, Product $product): string
    {
        $priceRender = $this->getPriceRendererBlock();

        $installmentPrice = $priceRender->render(
            Installment::PRICE_CODE,
            $product,
            [
                'view_key' => InstallmentHelper::LEANPAY_INSTALLMENT_VIEW_OPTION_CATEGORY_PAGE
            ]
        );

        return join('', [$result, $installmentPrice]);
    }

    /**
     * Get price renderer block (or create if missing)
     *
     * @return Render
     */
    private function getPriceRendererBlock(): Render
    {
        /** @var Render $priceRender */
        $priceRender = $this->layout->getBlock('product.price.render.default');

        return $priceRender ?: $this->layout->createBlock(
            Render::class,
            'product.price.render.default',
            ['data' => ['price_render_handle' => 'catalog_product_prices']]
        );
    }
}
