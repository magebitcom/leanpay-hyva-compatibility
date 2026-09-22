# Release Notes

This directory contains release notes for the Leanpay Hyvä compatibility module, organized by version and date.

## Structure

Release notes are organized chronologically with the most recent releases at the top.

## Format

Each release note file is named after the module version it documents, for example `0.2.0.md`.

---

## Recent Releases

### [2026-09-22 - 0.2.0: Category page performance](./0.2.0.md)
Preloads Leanpay category promotion data once per product listing instead of once per product card, and skips the preload safely when paired with a payment module that predates it. Requires leanpay/payment 0.17.0 or newer to take effect.
