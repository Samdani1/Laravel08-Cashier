@extends('layouts.app')

@section('content')
<div class="py-12 mx-auto" style="width:30%;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6" style="background-color:rgb(246, 246, 246);">
                <form action="subscribepost" method="post" id="payment-form" data-secret="{{ $intent->client_secret }}">
                    @csrf
                    <div class="w-1/2 form-row">
                        <div>
                            <input type="text" id="cardholder-name" style="background-color:transparent; border: 10px green;" class="mt-2 px-5 py-2" placeholder="Enter Candidate Name">
                        </div>
                        <div class="mt-2 mb-4">
                            <input type="radio" name="plan" id="standard" value="price_1IytXdACGkxNX2DrqQSvyMnA" checked>
                            <label for="standard">Standard - $10 / month</label> <br>

                            <input type="radio" name="plan" id="premium" value="price_1IytXdACGkxNX2DrU91y7Y3R">
                            <label for="premium">Premium - $20 / month</label>
                        </div>
                        <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>

                        <!-- Used to display form errors. -->
                        <div id="card-errors" role="alert"></div>
                    </div>

                    <button class="btn btn-primary mt-4">
                        Subscribe Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Create a Stripe client.
        var stripe = Stripe('pk_test_51IXMF6ACGkxNX2DrZv4OcjrEAjyYFJAR1qVRswTT3aFA2klqDHaTsr4Ke3E9kQqXwCVsg1hyzJZLPgAKNpQ0umfK009M9fmozy');
        // Create an instance of Elements.
        var elements = stripe.elements();
        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
            color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
        };
        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});
        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
        // Handle real-time validation errors from the card Element.
        card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
        });
        // Handle form submission.
        var form = document.getElementById('payment-form');
        var cardHolderName = document.getElementById('cardholder-name');
        var clientSecret = form.dataset.secret;
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );
            if (error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {
                // Send the token to your server.
                stripeTokenHandler(setupIntent);
            }
            // stripe.createToken(card).then(function(result) {
            //     if (result.error) {
            //     // Inform the user if there was an error.
            //     var errorElement = document.getElementById('card-errors');
            //     errorElement.textContent = result.error.message;
            //     } else {
            //     // Send the token to your server.
            //     stripeTokenHandler(result.token);
            //     }
            // });
        });
        // Submit the form with the token ID.
        function stripeTokenHandler(setupIntent) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'paymentMethod');
            hiddenInput.setAttribute('value', setupIntent.payment_method);
            form.appendChild(hiddenInput);
            // Submit the form
            form.submit();
        }
    </script>
@endsection
