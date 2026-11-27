---
type: Local Setup
title: Development
description: "How to work on the Leanpay Hyva module: where it runs, the branch flow for a change, rebuilding after a change, overriding the widget design in a theme, pairing it with the base plugin, and the docs check."
covers:
  - .github/workflows/docs-health.yml
stale_after: "2027-05-27"
---

# Development

This repository has no shop of its own. The module runs inside the Hyva test shop of the website repository ([leanpay-magento-website](https://github.com/magebitcom/leanpay-magento-website), branch `hyva-m249`), where it is a git submodule beside the base plugin. The website's own docs describe the local stack in full: Magebit Docker, the `d/` wrappers, the copy of a test shop's database and media. This doc holds what the module adds.

## What you need first

- GitHub access to this repository, to the base plugin ([leanpay-integration](https://github.com/magebitcom/leanpay-integration)) and to the website repository.
- A local copy of the Hyva shop, set up as the website docs say for the branch `hyva-m249`.

## Work on a change

1. In the website checkout the module is the folder `app/code/Leanpay/PaymentHyva`, its own git repository. Run git for the module inside that folder, not in the website root.
2. Branch `feature/LMM-<n>` in this repository. When the ticket also changes the base plugin, use the same branch name in both. [AGENTS.md](../../AGENTS.md) holds the pull request flow.
3. Check the branch out in that folder. A local shop runs whatever the folder holds; `git submodule update --remote`, run in the website root, moves it back to the branch tip the website declares, which is `master` for the Hyva shop.
4. After a template, layout or `etc/frontend/di.xml` change, run `d/magento cache:flush` from the website root.
5. A Tailwind class that no template used before shows after a Tailwind build: `make build` in the website root, which runs the build through `d/cli-php` on Hyva branches.

## Override the widget design in a theme

The Installment Widget Design setting chooses the design on the product page, in price updates and in the checkout. A merchant's theme can still force one: set the layout argument `installment_design` to `narrow` or `default` on the block `product.price.installment_price` in the theme's `hyva_catalog_product_view.xml`. The argument wins over the setting on that page.

## Pair it with the base plugin

The module calls the base plugin's helper, price box block and routes. The `leanpay/payment` constraint in `composer.json` names the oldest base plugin it works with. Composer enforces it for Packagist installs, not for submodules, so on the test shops check by hand that the two submodules are compatible. The module guards the two calls to newer base methods (the promotion preload and the design setting), so a mismatched pair renders without that feature instead of failing.

## Hooks and checks

The repository has no hook manager and no committed hook, and `composer.json` defines no scripts. The docs check runs on every pull request into `staging` and `master` (`.github/workflows/docs-health.yml`) and by hand:

```
docs/.health-checker/run
```

To run it on each commit locally, create `.git/hooks/commit-msg`, make it executable, and give it:

```
#!/bin/sh
exec docs/.health-checker/run "$1"
```

The check judges the branch against `origin/staging` or `origin/master`, whichever leaves fewer commits on the branch. The docs-health run needs Node 12 or newer on the host. What else protects a change is in [testing](../testing/README.md).
