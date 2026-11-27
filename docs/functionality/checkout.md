---
type: Functionality
title: Leanpay at Hyva Checkout
description: "How Leanpay appears as a payment method in Hyva Checkout, how its installment box follows the order total, how Place Order sends the buyer to Leanpay, and what the buyer sees when that fails."
covers:
  - Block/Checkout
  - Magewire/Checkout
  - ViewModel/Installments.php
  - view/frontend/layout/hyva_checkout_components.xml
  - view/frontend/layout/hyva_checkout_index_index.xml
  - view/frontend/templates/checkout
stale_after: "2027-05-27"
---

# Leanpay at Hyva Checkout

In Hyva Checkout a buyer can pay with Leanpay: the method shows Leanpay's icon and an installment box for the order total, and Place Order sends the buyer to Leanpay to complete the loan application. This doc covers what the Hyva module does in the checkout. The payment itself (the token request, Leanpay's callback, the order status, the success and failure pages) belongs to the base plugin ([leanpay-integration](https://github.com/magebitcom/leanpay-integration), read at commit 77a23cc).

## What it does

**The payment method.** Leanpay appears in the list of payment methods with the icon of the colour theme the admin chose: a small image that loads lazily, with the translatable alt text "Leanpay Icon". Under the method the buyer sees the installment box for the quote's grand total, drawn with the checkout logo and with the calculator behind its arrow. The box, its two designs and the calculator are described in [installment badge](installment-badge.md).

**Price changes.** The box shows the order total. When the buyer selects a shipping method, the Leanpay component refreshes, so the installments follow the new total. Hyva Checkout also re-renders the method on its own, for example when it saves the checkout data in the background. The box keeps an open calculator while its data is unchanged, and is rebuilt when the amount, the plans, the design or the visibility change.

**Placing the order.** Place Order first runs Hyva Checkout's normal order placement. The module then asks the base plugin for Leanpay's checkout address and token, and, after the checkout's own navigation steps, sends the buyer to Leanpay by posting a form. The base plugin handles the buyer's return, successful or not.

**When placing the order fails.** The base plugin answers with an error when it has no Leanpay token for the session. A failed request, an error flag or a missing address in the answer ends the same way: the buyer sees a dialog that cannot be cancelled, titled "Something went wrong while placing the order", with the server's message or a general request to try again, and Hyva Checkout's own error handling follows. The title is fixed English text; the server's message is translated by the base plugin.

## Outside systems

- **Base plugin:** the place-order route that returns Leanpay's checkout address and token, and the rule that offers Leanpay or not.
- **Leanpay:** the buyer fills in the loan application on Leanpay's side after the redirect.

## What the client can change

These are base plugin settings under Stores > Configuration > Sales > Payment Methods > Leanpay, owned by the admin and read by the base plugin:

- **Enable** switches the method on or off.
- **Minimum Order Total** and **Maximum Order Total** limit when Leanpay is offered: the method is hidden when the order total is below the minimum or above the maximum, and an empty field means no limit on that side.
- **Payment from Applicable Countries** and **Payment from Applicable Billing Countries**, with their country lists, limit the countries Leanpay is offered for.
- **Color Theme** in the group Leanpay Installment price sets the colours of the method icon and the installment box; **Installment Widget Design** in the same group sets the box design.

## Before you change it

- Magewire's payment method directive has to sit in the root element of the method template, so the template wraps the box in one element for that reason.
- The registration script waits for Hyva Checkout's script API to initialize before it registers the method. Keep that wait.
- The shipping method listener on the Leanpay component keeps the box in step with the total. Keep it when the component changes.
- The box script is shared with the product page. Keep both uses working, see [installment badge](installment-badge.md), Before you change it.
- Keep the template and script CSP safe: [architecture](../architecture/README.md), Alpine and CSP.
