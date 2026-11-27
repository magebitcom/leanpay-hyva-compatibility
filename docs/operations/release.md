---
type: Runbook
title: Release
description: "How a version of the Leanpay Hyva module is released: release branch, version, release notes, tag, GitHub release, Packagist, and the message to Leanpay."
stale_after: "2027-05-27"
---

# Release

A release ships the approved changes to merchants through Packagist as `leanpay/payment-hyva`. Release once Leanpay has approved the tickets on a test shop ([testing](../testing/README.md)). The Hyva module and the base plugin are two packages with two release cycles; release both when both changed, the base plugin first when the Hyva module needs its new code.

1. In this repository, branch `release/<dd.mm.yyyy>` from `master` and merge the approved `feature/LMM-<n>` branches into it. A feature that Leanpay has not approved stays out.
2. Raise `version` in `composer.json` (semantic versioning). When the module calls a base plugin method from a newer release, raise the `leanpay/payment` constraint in the same file to that release.
3. Add `docs/releases/<version>.md`, in the structure of the previous notes, and a summary entry at the top of `docs/releases/README.md` with the date, the version, a short title and the base plugin version it requires.
4. Run `docs/.health-checker/run`, then open the pull request `release/<dd.mm.yyyy>` into `master` and merge it.
5. Tag the merge commit with the version, without a `v` prefix, and create a GitHub release from the tag.
6. Packagist picks the tag up within minutes. Check that [leanpay/payment-hyva](https://packagist.org/packages/leanpay/payment-hyva) shows the version, and [leanpay/payment](https://packagist.org/packages/leanpay/payment) too when the base plugin was released with it.
7. Comment on the Jira ticket or tickets for Leanpay: the Packagist links, the GitHub release links (the ZIP) and the install command, `composer require leanpay/payment:<constraint> leanpay/payment-hyva:<constraint>`. Leanpay publishes the ZIP on its website and tells its merchants.
