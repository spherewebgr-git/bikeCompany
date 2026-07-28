<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Order Completed</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

@php
    $isBuy = strtolower($order->bike->provision->name ?? '') === 'buy';
@endphp

<table role="presentation"
       width="100%"
       cellspacing="0"
       cellpadding="0"
       style="width:100%; background-color:#f3f4f6; padding:30px 15px;">

    <tr>
        <td align="center">

            <table role="presentation"
                   width="600"
                   cellspacing="0"
                   cellpadding="0"
                   style="width:100%; max-width:600px; background-color:#ffffff; border-radius:10px;">

                {{-- Header --}}
                <tr>
                    <td align="center"
                        style="padding:30px 20px; background-color:#1f2937; border-radius:10px 10px 0 0;">

                        <img
                            src="{{ $message->embed(public_path('images/bikeco-light-logo.png')) }}"
                            alt="{{ config('app.name', 'Trail Bike') }}"
                            width="180"
                            style="display:block; height:auto; max-width:180px; margin:0 auto 15px;">

                        <h1 style="margin:0; color:#ffffff; font-size:27px; font-weight:700;">

                            @if($isBuy)
                                Your order has been delivered
                            @else
                                Your rental has been completed
                            @endif

                        </h1>

                        <p style="margin:10px 0 0; color:#d1d5db; font-size:15px;">

                            @if($isBuy)
                                We hope you enjoy your new bike
                            @else
                                Thank you for returning your rental bike
                            @endif

                        </p>

                    </td>
                </tr>

                {{-- Main content --}}
                <tr>
                    <td style="padding:35px 30px; color:#374151; font-size:15px; line-height:1.6;">

                        <p style="margin:0 0 15px;">
                            Hello

                            <strong>
                                {{
                                    trim(
                                        ($order->user->first_name ?? '') . ' ' .
                                        ($order->user->last_name ?? '')
                                    ) ?: 'customer'
                                }}
                            </strong>,
                        </p>

                        @if($isBuy)

                            <p style="margin:0 0 20px;">
                                We are happy to inform you that your order
                                <strong>#{{ $order->id }}</strong>
                                has been successfully delivered.
                            </p>

                            <div style="margin:25px 0; padding:20px; background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:6px;">

                                <p style="margin:0; color:#065f46; font-size:16px; font-weight:700;">
                                    Your bike has been delivered successfully
                                </p>

                                <p style="margin:8px 0 0; color:#047857;">
                                    Your order has arrived at the selected delivery
                                    address. We hope you enjoy your new bike and
                                    have many safe rides.
                                </p>

                            </div>

                        @else

                            <p style="margin:0 0 20px;">
                                Your rental order
                                <strong>#{{ $order->id }}</strong>
                                has been completed successfully.
                            </p>

                            <div style="margin:25px 0; padding:20px; background-color:#eff6ff; border:1px solid #93c5fd; border-radius:6px;">

                                <p style="margin:0; color:#1d4ed8; font-size:16px; font-weight:700;">
                                    Your rental bike has been returned successfully
                                </p>

                                <p style="margin:8px 0 0; color:#1e40af;">
                                    Thank you for returning the bike. We hope you
                                    enjoyed your ride and we look forward to seeing
                                    you again.
                                </p>

                                <p style="margin:15px 0 0; color:#1e40af;">
                                    <strong>Return location:</strong><br>

                                    {{ $order->location->name ?? 'Not available' }}
                                </p>

                            </div>

                        @endif

                        {{-- Bike information --}}
                        <h3 style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;">
                            Bike information
                        </h3>

                        <table role="presentation"
                               width="100%"
                               cellspacing="0"
                               cellpadding="0"
                               style="width:100%; border-collapse:collapse; margin-bottom:30px;">

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Brand</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">

                                    {{ $order->bike->brand->name ?? 'Not available' }}

                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Type</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">

                                    {{ $order->bike->type->name ?? 'Not available' }}

                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Speed</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">

                                    {{ $order->bike->speed->gears ?? 'Not available' }}

                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Colour</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">

                                    {{ $order->bike->colour ?? 'Not available' }}

                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px;">
                                    <strong>SKU</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px;">

                                    {{ $order->bike->SKU ?? 'Not available' }}

                                </td>
                            </tr>

                            <tr>
                                @if($order->bike->provision->name == 'rent')
                                    <td style="padding:12px 15px; border-top:1px solid #e5e7eb;">
                                        <strong>Serial number</strong>
                                    </td>

                                    <td align="right"
                                        style="padding:12px 15px; border-top:1px solid #e5e7eb;">
                                        {{ $order->bike->serialnum }}
                                    </td>
                                @endif

                            </tr>

                        </table>

                        {{-- Completion information --}}
                        <h3 style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;">

                            @if($isBuy)
                                Delivery information
                            @else
                                Rental completion information
                            @endif

                        </h3>

                        <table role="presentation"
                               width="100%"
                               cellspacing="0"
                               cellpadding="0"
                               style="width:100%; border-collapse:collapse;">

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Order number</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">

                                    #{{ $order->id }}

                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Order status</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb; font-weight:700; color:#047857;">

                                    @if($isBuy)
                                        Delivered
                                    @else
                                        Rental completed
                                    @endif

                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Completion date</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">

                                    {{ $order->updated_at?->format('d/m/Y H:i') ?? 'Not available' }}

                                </td>
                            </tr>

                            @if($isBuy)

                                @if($order->dropoff_address)

                                    <tr>
                                        <td style="padding:12px 15px;">
                                            <strong>Delivery address</strong>
                                        </td>

                                        <td align="right"
                                            style="padding:12px 15px;">

                                            {{ $order->dropoff_address }}

                                        </td>
                                    </tr>

                                @endif

                            @else

                                <tr>
                                    <td style="padding:12px 15px;">
                                        <strong>Return location</strong>
                                    </td>

                                    <td align="right"
                                        style="padding:12px 15px;">

                                        {{ $order->location->name ?? 'Not available' }}

                                    </td>
                                </tr>

                            @endif

                        </table>

                        <p style="margin:30px 0 0;">
                            Thank you for choosing Bike Company.
                        </p>

                        <p style="margin:5px 0 0; color:#6b7280; font-size:14px;">

                            @if($isBuy)
                                We hope you enjoy your new bike and we look
                                forward to seeing you again.
                            @else
                                We hope you enjoyed your rental and we look
                                forward to welcoming you again.
                            @endif

                        </p>

                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td align="center"
                        style="padding:22px 20px; background-color:#f9fafb; border-top:1px solid #e5e7eb; border-radius:0 0 10px 10px; color:#6b7280; font-size:13px;">

                        <p style="margin:0 0 5px;">
                            © {{ date('Y') }} Bike Company
                        </p>

                        <p style="margin:0;">
                            This is an automated order status email.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>
