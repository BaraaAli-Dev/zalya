@component('mail::message')
    # Thank you for your order, {{ $order->customer_name }}!

    Your order **#{{ $order->id }}** has been placed successfully and is now being processed.

    ## Order Summary

    @component('mail::table')
        | Product | Qty | Price |
        |:--------|:---:|------:|
        @foreach ($order->items as $item)
            | {{ $item->product->product_name ?? 'Product' }} | {{ $item->quantity }} |
            {{ number_format($item->price * $item->quantity, 2) }} EGP |
        @endforeach
    @endcomponent

    **Total: {{ number_format($order->total_price, 2) }} EGP**

    ## Shipping Address
    {{ $order->shipping_street }}<br>
    {{ $order->shipping_city }}@if ($order->shipping_state)
        , {{ $order->shipping_state }}
    @endif
    <br>
    {{ $order->shipping_country }}

    Payment Method: Cash on Delivery

    @component('mail::button', ['url' => config('app.url')])
        Continue Shopping
    @endcomponent

    Thanks for choosing Zalya — Unforgettable Fragrance.<br>
    Zalya Perfume Team
@endcomponent
