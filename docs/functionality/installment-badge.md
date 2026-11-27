---
type: Functionality
title: Installment badge on Hyva pages
description: "How the Leanpay installment badge and calculator look and behave on Hyva product pages, listings and the checkout: widget designs, price and variant changes, listing cards and the admin settings that control them."
covers:
  - Plugin/ViewModel
  - ViewModel/Installments.php
  - view/frontend/layout/hyva_catalog_category_view.xml
  - view/frontend/layout/hyva_catalog_product_view.xml
  - view/frontend/layout/hyva_catalogsearch_result_index.xml
  - view/frontend/layout/hyva_cms_index_index.xml
  - view/frontend/templates/pricing
stale_after: "2027-05-27"
---

# Installment badge on Hyva pages

On Hyva shops a buyer sees what a purchase costs per month with Leanpay: next to the price on the product page, under each product card on listings, and under the Leanpay method in Hyva Checkout. On the product page and in the checkout a calculator lets the buyer compare installment counts. The Hyva module draws the badge; the plans, promotions, texts and settings come from the base plugin ([leanpay-integration](https://github.com/magebitcom/leanpay-integration), read at commit 77a23cc).

## What it does

**The badge on the product page and in the checkout.** At or below the Amount Threshold the badge shows the monthly installment for the Default Installment Count and a text about no interest and no costs. Above the threshold it shows "from" the lowest monthly price and the longest plan, "split into up to N installments". An arrow opens the calculator: the Leanpay logo, the monthly price of the selected plan, a slider over the available installment counts, the quick information text with a More information link, a Check your limit button, and a deposit line when the base plugin allows a down payment. The texts are Slovenian source phrases that the base plugin translates into Croatian and Romanian.

**Two designs.** The Installment Widget Design setting chooses one for the product page, for the box that replaces itself after a price change, and for the checkout:

- Default: the full box; the calculator opens as a dropdown below it on a large screen and as a bottom sheet on a small one.
- Narrow: a compact box on a large screen, so its text stays on one line in a narrow buy box, and the calculator opens as a centred modal; on a small screen the box and the bottom sheet look as in Default.

The compact design exists for narrow buy boxes, where the text broke onto two lines and the inline calculator was cramped. A layout argument on the installment block in a merchant's theme still overrides the setting ([development](../operations/development.md) names it).

**Price changes on the product page.** When a configurable option or a custom option changes the price, the badge and calculator update. The badge texts for the product's own prices are pre-rendered into the page; for another amount the page asks the base plugin's installment route. A request for an earlier price is cancelled when a new one starts, and a late answer for an older price or variant is dropped, so the badge ends on the latest choice. A page without an applicable installment still holds an empty hidden placeholder, so a later price change can fill it.

**Variants with their own promotion.** A variant of a configurable product can carry its own Leanpay promotion. The buyer who selects it sees that promotion's terms, not the parent's: checkout already applies the variant's promotion, and the parent's terms undersold it on the page. A variant without a promotion shows the parent's terms or the general ones.
**Listings.** Under each card on category, search and home listings and in product widgets, a compact label shows the Leanpay monthly price text with the Leanpay logo. For amounts below the Amount Threshold it shows the Under Threshold Text instead. Nothing shows where the base plugin has no plan for the amount or the Leanpay views exclude the page. The promotions of the cards on a listing are loaded in one batch, because a lookup per card made cold listings slow.
## What the client can change

Settings of the base plugin, owned by the admin, under Stores > Configuration > Sales > Payment Methods:

- Leanpay, **Show installment price in following pages**: Homepage, Product, Category, or disabled in all views; set at default or website scope, so store views inherit it. Product also covers the checkout box. Category covers the listing cards, those on the home page and in widgets included, because cards drawn by Hyva's product list item follow Category.
- Leanpay, **Minimum Order Total** and **Maximum Order Total**: a price outside them shows no badge; an empty field means no limit on that side.
- Leanpay Installment price, **Installment Widget Design** (Default or Narrow) and **Color Theme**, per store view. The theme colours the badge, the calculator and the method icon.
- Leanpay Installment price, Advanced: **Amount Threshold**, **Default Installment Count (≤ Threshold)**, **Under Threshold Text** and **Installment Quick Information**.
- Promotions come from product and category promotion attributes and Stores > Configuration > Sales > Leanpay Promos > Multiple Financing Products, owned by the base plugin.

## Before you change it

- The product page and the checkout share one script and one template. Test both after a change to either.
- Guard each call to a base plugin method that a recent base release added, and raise the base plugin constraint in the module's composer file with it.
- The root element of the badge carries an id that the script looks up, and a key built from the rendered data. A key that changes on each render closes the calculator whenever Hyva Checkout re-renders.
- A new Tailwind class needs a Tailwind build of the shop ([troubleshooting](../operations/troubleshooting.md)). Keep the markup CSP safe ([architecture](../architecture/README.md), Alpine and CSP).
