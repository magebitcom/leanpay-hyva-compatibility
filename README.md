# Leanpay Hyva module

This module shows Leanpay installment financing in Hyva themes: the installment badge and calculator next to prices, and Leanpay as a payment method in Hyva Checkout. Magebit builds it for Leanpay. Merchants install it from Packagist as [leanpay/payment-hyva](https://packagist.org/packages/leanpay/payment-hyva), beside the Leanpay payment plugin that it requires.

Jira ticket prefix: LMM (project "Leanpay Module M2").

## Other repositories

- [leanpay-integration](https://github.com/magebitcom/leanpay-integration): the base plugin ([leanpay/payment](https://packagist.org/packages/leanpay/payment)). It holds the payment method, the Leanpay API, the installment plans, the promotions, the admin settings and the Luma frontend. This module draws what it computes on Hyva pages.
- [leanpay-magento-website](https://github.com/magebitcom/leanpay-magento-website): Magebit's test shops. The Hyva shop includes this module as a submodule.

## Docs

- [Architecture](docs/architecture/README.md): what the module adds on Hyva pages and how it hooks in.
- [Functionality](docs/functionality/README.md): Leanpay at Hyva Checkout, and the installment badge on Hyva pages.
- [Development](docs/operations/development.md), [release](docs/operations/release.md) and [troubleshooting](docs/operations/troubleshooting.md).
- [Testing](docs/testing/README.md): manual testing on a Hyva shop.
- [Release notes](docs/releases/README.md).
- [Maintaining the docs](docs/MAINTAINING.md).

Agents and developers: follow [AGENTS.md](AGENTS.md).
