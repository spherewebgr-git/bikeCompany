<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Order Completed</title>
</head>

<body>

<h1>Order confirmation</h1>

<p>
    Hello
    {{ $order->user->first_name ?? $order->user->name ?? 'Customer' }},
</p>

<p>
    Your order has been completed successfully.
</p>

<hr>

<h2>Order details</h2>

<p>
    <strong>Order number:</strong>
    #{{ $order->id }}
</p>

<p>
    <strong>Order date:</strong>
    {{ $order->order_date?->format('d/m/Y') }}
</p>

@if ($order->bike->SKU)
    <p>
        <strong>Bike SKU:</strong>
        {{ $order->bike->SKU }}
    </p>
@endif

<p>
    <strong>Colour:</strong>
    {{ $order->bike->colour }}
</p>

<p>
    <strong>Price:</strong>
    €{{ number_format($order->price, 2) }}
</p>

@if ($order->dropoff_address)
    <p>
        <strong>Delivery address:</strong>
        {{ $order->dropoff_address }}
    </p>
@endif

<p>
    <strong>Payment method:</strong>

    @if ($order->payed_off)
        Card
    @else
        Cash on delivery
    @endif
</p>

<p>
    Thank you for choosing Bike Company.
</p>

</body>
</html>
