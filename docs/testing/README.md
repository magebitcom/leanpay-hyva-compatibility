---
type: Testing
title: Testing
description: "How a change to the Leanpay Hyva module is checked: the docs check on pull requests, manual testing on a Hyva shop, which Hyva test shop runs which branch, and why the website's acceptance suite does not apply."
covers:
  - .github/workflows/docs-health.yml
stale_after: "2027-05-27"
---

# Testing

The module has no automated tests, no static analysis and no unit test runner. What protects a change before it reaches merchants is a manual check on a Hyva shop, the docs check, and Leanpay's approval on the ticket.

| Layer | Runs | Blocks a merge | More |
|---|---|---|---|
| Docs check: changed code whose covering doc was not updated, and docs with a broken path or link | On pull requests into `staging` and `master`, and by hand with `docs/.health-checker/run` | No: the branch rule on both branches requires a pull request and the Portal's Magebit Review status check, and forbids force pushes and deletion | [Development](../operations/development.md), Hooks and checks |
| Manual test on a Hyva shop | By hand, after the change is on the shop | No | Manual testing below |
| Leanpay's approval | By Leanpay's reviewer in the ticket, after a test shop shows the change | No; a release waits for it | [Release](../operations/release.md) |

## Manual testing

The module's pages exist only in the Hyva theme and Hyva Checkout, so a change is checked on a Hyva shop. Two shops can run a change:

- **The Hyva test shop** (the website branch `hyva-m249`, Magebit's Dev 6). It has the Hyva theme and Hyva Checkout, runs in Leanpay's sandbox with its own sandbox keys, and has the Slovenian and the Romanian store view and a prepared test catalog. A deploy builds the head of `master` of this module and of the base plugin, whatever commit the website records, because the website's submodule declaration names `master`. A change that exists only on `staging` or a feature branch cannot be tried there until it reaches `master`.
- **A local Hyva shop** with the change's branch checked out in the module folder ([development](../operations/development.md)). Use it to try a change before it is released.

A merge into this repository does not deploy the shop. To see a change that reached `master`, start the build of the shop's Jenkins job by hand, `leanpay2/hyva-m249/magento`: Build Now in Jenkins, or the Jenkins tab of Portal > Testing environments, after the VM is up if it sleeps. Jenkins reports "No Changes" because it compares only the website repository; the build still deploys the module's new head. Do not edit code on the VM: every build wipes its workspace.

Which checks to make for which change:

- Listings (category, search, home, widgets): the label shows on each card with the same promotion as before; with a cold cache and a query log, the category promotion lookup runs once per page and not once per card.
- Product page: the badge and the calculator open and close; a price change by a configurable or custom option updates the badge and keeps the design; a variant with its own promotion shows its terms, and switching back shows the parent's.
- Installment Widget Design: Default and Narrow on a large and a small screen.
- Hyva Checkout: with Leanpay selected, the arrow opens the calculator; a shipping method change refreshes the box and the calculator opens again afterwards; Place Order redirects to Leanpay's sandbox.
- On each page you tested: no errors in the browser console.
- A base plugin older than the module expects: pages render without a fatal error ([troubleshooting](../operations/troubleshooting.md)).

Placing an order on the shop creates a test order and opens a checkout in Leanpay's sandbox; use only sandbox data. After the test, post the result and the shop's URL in the Jira ticket, and wait there for Leanpay's reviewer.

## The website's acceptance suite

The website repository holds an acceptance suite written for the Luma storefront. Its selectors do not fit Hyva Checkout, so it is not one of the checks for this module and gives no cover for a Hyva change.
