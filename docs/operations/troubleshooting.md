---
type: Troubleshooting
title: Troubleshooting
description: "Symptoms specific to the Leanpay Hyva module and their causes: missing Tailwind classes, CSP errors in the widget, a module not registered with Hyva, mismatched base plugin versions, a missing badge and a change that does not show on the test shop."
covers:
  - etc/frontend/di.xml
  - view/frontend/templates/checkout/page/js
  - view/frontend/templates/pricing/render/js
stale_after: "2027-05-27"
---

# Troubleshooting

## The badge or the checkout method is unstyled or misses a style

The shop's Tailwind build creates the classes that the registered modules use at the time of the build. A template that gained a new class shows it after the next build. Run `make build` in the website root locally; a test shop gets it from its Jenkins build. If none of this module's classes shows, the module's folder is missing from the shop's `app/etc/hyva-themes.json`: run `d/magento hyva:config:generate`, which lists the module through its compat registration, then build again.

## The calculator arrow does nothing, or the console reports a CSP error

Hyva's Alpine build for strict CSP evaluates no inline expressions, and an inline script must be registered with Hyva's CSP helper. A directive value that holds a call or an expression, such as a method with brackets, fails; use the bare method or property name and read the event through the component. A script without the registration call after its closing tag is blocked. See [architecture](../architecture/README.md), Alpine and CSP.

## A Hyva page shows no badge or the base plugin's markup, or a template change has no effect

The module is not registered with Hyva's compat module fallback, or is not active. The registration is in the module's `etc/frontend/di.xml`. Check `d/magento module:status Leanpay_PaymentHyva` and the same for `Hyva_CompatModuleFallback`, then run `d/magento cache:flush`.

## The widget ignores the design setting, or a listing runs a promotion lookup per card

The base plugin is older than the Hyva module expects. Composer refuses that pair, but a submodule setup does not, so the module falls back to the Default design and to a lookup per card. Update the base plugin to the release the module's `composer.json` names. See [development](development.md), Pair it with the base plugin.

## No badge shows on a page

Check, in this order: the page type is enabled in Show installment price in following pages; the price is inside Minimum Order Total and Maximum Order Total; the Leanpay plan tables have rows for the store's market (run `d/magento leanpay:import` when they are empty); the product's promotion dates. The settings are described in [installment badge](../functionality/installment-badge.md), What the client can change.

## A change does not show on the Hyva test shop

The shop runs `master` of this module, so a change that is only on `staging` or a feature branch is not there. See [testing](../testing/README.md), Manual testing.
