// This is your test publishable API key.
const stripe = Stripe(publicKey);
let elements;

initialize();
checkStatus();

document
    .querySelector("#payment-form")
    .addEventListener("submit", handleSubmit);

// Fetches a payment intent and captures the client secret
function initialize() {
    elements = stripe.elements({
        clientSecret
    });

    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");
}

async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);

    const {
        error
    } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            // Make sure to change this to your payment completion page
            return_url: redirectRoute,
        },
    });

    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.
    if (error.type === "card_error" || error.type === "validation_error") {
        await showMessage(error.message, 'alert-danger');
    } else {
        await showMessage("An unexpected error occurred.", 'alert-danger');
    }

    setLoading(false);
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get(
        "payment_intent_client_secret"
    );

    if (!clientSecret) {
        return;
    }

    const {paymentIntent} = await stripe.retrievePaymentIntent(clientSecret);

    switch (paymentIntent.status) {
        case "succeeded":
            await showMessage("Payment succeeded!", 'alert-success');
            await submitForm(paymentIntent.id);
            break;
        case "processing":
            await showMessage("Your payment is processing.", 'alert-info');
            break;
        case "requires_payment_method":
            await showMessage("Your payment was not successful, please try again.", 'alert-danger');
            break;
        default:
            await showMessage("Something went wrong.", 'alert-danger');
            break;
    }
}

async function submitForm(paymentIntentId) {
    axios.post(paymentStoreRoute,
        {
            payment_intend_id: paymentIntentId,
            amount: amount,
        })
        .then((response) => {
            showMessage('Payment Stored Successfully for payment_intend_id: ' + response.data.payment_intend_id, 'alert-success');
            setTimeout(function () {
                window.location.href = redirectRoute;
            }, 2000)
        })
        .catch((errors) => {
            if (errors.response.status === 422) {
                if (errors.response.data.errors['payment_intend_id'])
                    showMessage(errors.response.data.errors['payment_intend_id'][0], 'alert-danger');
                else if (errors.response.data.errors['amount'])
                    showMessage(errors.response.data.errors['amount'][0], 'alert-danger');
                else
                    showMessage('Cant Store Payment, Invalid Data Provided', 'alert-danger');
            } else
                showMessage(errors.response.data.error, 'alert-danger');
        });
}

// ------- UI helpers -------

async function showMessage(messageText, alertClass) {
    const messageContainer = document.querySelector("#payment-message");

    messageContainer.classList.remove("hidden");
    messageContainer.classList.add('alert');
    messageContainer.classList.add(alertClass);
    messageContainer.textContent = messageText;

    setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageContainer.classList.remove('alert');
        messageContainer.classList.remove(alertClass);
        messageContainer.textContent = null;
    }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submit").disabled = true;
        document.querySelector("#spinner").classList.remove("sr-only");
        document.querySelector("#button-text").classList.add("hidden");
    } else {
        document.querySelector("#submit").disabled = false;
        document.querySelector("#spinner").classList.add("sr-only");
        document.querySelector("#button-text").classList.remove("hidden");
    }
}
