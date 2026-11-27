---
type: Architecture
title: Architecture of the Leanpay Hyva module
description: "What the Leanpay Hyva module adds on Hyva pages compared with the base plugin's Luma frontend, and how it hooks into Hyva: layout, plugin, view model, Magewire, Alpine, CSP and Tailwind."
covers:
  - Block
  - Magewire
  - Plugin
  - ViewModel
  - etc
  - view
  - registration.php
stale_after: "2027-05-27"
---

# Architecture

The Leanpay Hyva module shows Leanpay installment financing on Hyva pages: the installment badge with its calculator, and Leanpay as a payment method in Hyva Checkout. It depends on the base plugin ([leanpay-integration](https://github.com/magebitcom/leanpay-integration), read at commit 77a23cc) and on the Hyva default theme. It holds no payment logic, no call to Leanpay's API and no admin setting of its own: the amounts, plans, promotions, settings and server endpoints come from the base plugin, and this module draws the result the way Hyva pages work, with Alpine.js, Tailwind CSS and, in Hyva Checkout, Magewire.

The base plugin's own frontend is built for Luma: a checkout payment renderer, a stylesheet and RequireJS scripts that follow price changes. Hyva pages run on Alpine.js instead, so this module repeats the same behaviour there.

## What it adds on Hyva pages

| Page | Base plugin on Luma | This module on Hyva | Home doc |
|---|---|---|---|
| Hyva Checkout, payment step | A checkout payment renderer | A Leanpay payment method block with a Magewire component, the registration of the method in the Hyva Checkout script API, and the installment widget under the method | [Leanpay at Hyva Checkout](../functionality/checkout.md) |
| Product page | A price box template and RequireJS mixins that react to option and swatch changes | Its own price box template and an Alpine component that follows Hyva's price calculation and the selected variant | [Installment badge](../functionality/installment-badge.md) |
| Category, search and home listings, product widgets | Plugins on the Luma listing blocks | A plugin on Hyva's product list item that preloads the promotion data of a listing in one batch and adds the badge under each card's price | [Installment badge](../functionality/installment-badge.md) |
| Styling | One stylesheet | The stylesheet is removed from the Hyva product, category, search and home pages; Tailwind classes and the colour variables the base plugin prints from its Color Theme setting style the widget | [Installment badge](../functionality/installment-badge.md) |

## How it hooks in

- **Compat module registration.** The module registers itself with Hyva's compat module fallback as the Hyva version of the base plugin. Hyva then renders a template of this module in place of the base plugin's template with the same path, which is how the module's badge template takes the base template's place without a layout change. Hyva's [compatibility module docs](https://docs.hyva.io/hyva-themes/compatibility-modules/technical-deep-dive.html) describe the mechanism. The registration also puts the module into the shop's Tailwind build.
- **Layout.** One layout file per Hyva page type adjusts the base plugin's layout:
  - product page: removes the base stylesheet, moves the installment container into the additional product information area and declares the badge script as a dependency of the installment block;
  - category, search and home pages: remove the base stylesheet;
  - checkout: adds the Leanpay payment method block to the payment methods container, with the Magewire component and the icon settings, and adds the registration script and the badge script to the Hyva Checkout script containers.
- **Listing plugin.** Before a card renders, it hands the loaded product collection of the listing to the base plugin's promotion preload; after the card's price renders, it adds the installment price block with the category view. The preload call is skipped with a base plugin that lacks it.
- **View model.** One view model serves the templates: the checkout badge for the quote's grand total, whether the page is a product page, the current product, and the map from amount to pre-rendered badge that the base plugin puts into the product page's JSON configuration.
- **Magewire.** The Leanpay component extends Magewire's component class when Magewire is installed and an empty class otherwise, so the module loads in a shop without Hyva Checkout. The component refreshes when a shipping method is selected.
- **Payment method block.** It adds the icon of the chosen colour theme to the method's metadata every time Hyva Checkout reads it.
- **Alpine and CSP.** Hyva's Alpine build for strict Content Security Policy evaluates no inline expressions. So directive values in the templates name a method or property without a call or an expression, and each inline script is registered with Hyva's CSP helper (see Hyva's [CSP compatibility docs](https://docs.hyva.io/hyva-themes/writing-code/csp/csp-compatibility.html)). The module has no CSP whitelist of its own. The redirect to Leanpay is a form post, and the base plugin's whitelist allows Leanpay's hosts as form targets.
- **Dependencies on the base plugin.** The templates and view model call the base plugin's helper, price box block and routes directly, so their signatures are this module's contract. Calls to methods that appeared in a recent base release are guarded, because the two packages are released separately.

## Outside systems

| System | What it gives this module | What this module gives it |
|---|---|---|
| Base plugin | Amounts, installment plans, promotions, colour theme, design setting, the installment and place-order routes, the CSP whitelist, the translations | Nothing |
| Hyva theme and Hyva Checkout | Product list item, price calculation script, checkout script API, Magewire | The badge, the Leanpay payment method and its refresh |
| Leanpay | Nothing: the module makes no call to Leanpay. The buyer is sent to Leanpay's checkout by a form post to the address the base plugin returns | The loan application is on Leanpay's side |
| [Test shops](https://github.com/magebitcom/leanpay-magento-website) | The Hyva shop includes this module as a submodule | See [testing](../testing/README.md) |
