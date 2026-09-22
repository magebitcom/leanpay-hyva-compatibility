<?php
/**
 * @author    Magebit <info@magebit.com>
 * @copyright Copyright (c) Magebit, Ltd. (https://magebit.com)
 * @license   https://magebit.com/code-license
 */

declare(strict_types=1);

namespace Leanpay\PaymentHyva\Plugin\ViewModel;

use Hyva\Theme\ViewModel\ProductListItem;
use Leanpay\Payment\Helper\Data;
use Leanpay\Payment\Helper\InstallmentHelper;
use Leanpay\Payment\Pricing\Price\Installment;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;

class ProductListItemPlugin
{
    /**
     * @param LayoutInterface $layout
     * @param Data $helper
     */
    public function __construct(
        private readonly LayoutInterface $layout,
        private readonly Data $helper,
    ) {
    }

    /**
     * Preload category promotion data for the whole listing so the installment box is not queried per card
     *
     * @param ProductListItem $subject
     * @param Product $product
     * @param AbstractBlock $parentBlock
     *
     * @return void
     */
    public function beforeGetItemHtml(ProductListItem $subject, Product $product, AbstractBlock $parentBlock): void
    {
        // The preload is provided by Leanpay_Payment. The two modules are released and
        // deployed independently, so skip the preload when paired with a payment module
        // that predates it rather than breaking the page render.
        if (!method_exists($this->helper, 'preloadCategoryPromotions')) {
            return;
        }

        $collection = $parentBlock instanceof ListProduct
            ? $parentBlock->getLoadedProductCollection()
            : $parentBlock->getData('product_collection');

        if ($collection instanceof Collection && $collection->isLoaded()) {
            $this->helper->preloadCategoryPromotions($collection->getLoadedIds());
        }
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
