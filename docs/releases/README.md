# Release Notes

This directory contains release notes for the Leanpay Hyvä compatibility module, organized by version and date.

## Structure

Release notes are organized chronologically with the most recent releases at the top.

## Format

Each release note file is named after the module version it documents, for example `0.2.0.md`.

---

## Recent Releases

### [2026-10-13 - 0.3.0: Selected variant promotion on the product page](./0.3.0.md)
The product page installment badge now shows the selected configurable variant's Leanpay promotion, with the parent's as fallback, including the AJAX fallback and quick variant switching. Requires leanpay/payment 0.18.0 or newer.

### [2026-10-01 - 0.2.0: Category page performance, design setting, checkout hardening](./0.2.0.md)
Preloads Leanpay category promotion data once per product listing instead of once per product card, and skips the preload safely when paired with a payment module that predates it. The installment widget design is now chosen in admin and applies to the product page, price change updates and Hyvä Checkout; the product page now defaults to the Default design. The calculator no longer closes when Hyvä Checkout re-renders the payment method. Requires leanpay/payment 0.17.0 or newer.
