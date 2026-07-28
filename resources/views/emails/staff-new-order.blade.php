<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>New Order Notification</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

@php
    $isRent =
        strtolower($order->bike->provision->name ?? '') === 'rent';

    $customerName =
        trim(
            ($order->user->first_name ?? '') . ' ' .
            ($order->user->last_name ?? '')
        ) ?: 'Customer';
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
                            alt="{{ config('app.name', 'Bike Company') }}"
                            width="180"
                            style="display:block; height:auto; max-width:180px; margin:0 auto 15px;">

                        <h1 style="margin:0; color:#ffffff; font-size:27px; font-weight:700;">

                            @if($isRent)
                                New rental order
                            @else
                                New purchase order
                            @endif

                        </h1>

                        <p style="margin:10px 0 0; color:#d1d5db; font-size:15px;">

                            @if($isRent)
                                A customer has requested a bike rental
                            @else
                                A customer has purchased a bike
                            @endif

                        </p>

                    </td>
                </tr>

                {{-- Main content --}}
                <tr>
                    <td style="padding:35px 30px; color:#374151; font-size:15px; line-height:1.6;">

                        <p style="margin:0 0 15px;">
                            Hello staff member,
                        </p>

                        @if($isRent)

                            <p style="margin:0 0 20px;">
                                A new bike rental order has been submitted by
                                <strong>{{ $customerName }}</strong>.
                            </p>

                            <div style="margin:25px 0; padding:20px; background-color:#eff6ff; border:1px solid #93c5fd; border-radius:6px;">

                                <p style="margin:0; color:#1d4ed8; font-size:16px; font-weight:700;">
                                    New rental requires attention
                                </p>

                                <p style="margin:8px 0 0; color:#1e40af;">
                                    Please review the rental dates, pickup location
                                    and bike availability before preparing the order.
                                </p>

                            </div>

                        @else

                            <p style="margin:0 0 20px;">
                                A new bike purchase order has been submitted by
                                <strong>{{ $customerName }}</strong>.
                            </p>

                            <div style="margin:25px 0; padding:20px; background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:6px;">

                                <p style="margin:0; color:#065f46; font-size:16px; font-weight:700;">
                                    New purchase requires attention
                                </p>

                                <p style="margin:8px 0 0; color:#047857;">
                                    Please review the payment and delivery information
                                    and begin preparing the bike for delivery.
                                </p>

                            </div>

                        @endif

                        {{-- Customer information --}}
                        <h3 style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;">
                            Customer information
                        </h3>

                        <table role="presentation"
                               width="100%"
                               cellspacing="0"
                               cellpadding="0"
                               style="width:100%; border-collapse:collapse; margin-bottom:30px;">

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Name</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    {{ $customerName }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Email</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    {{ $order->user->email ?? 'Not available' }}
                                </td>
                            </tr>

                            @if($order->user->phone ?? null)
                                <tr>
                                    <td style="padding:12px 15px;">
                                        <strong>Phone</strong>
                                    </td>

                                    <td align="right"
                                        style="padding:12px 15px;">
                                        {{ $order->user->phone }}
                                    </td>
                                </tr>
                            @endif

                        </table>

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
                                    <strong>Provision</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb; font-weight:700;">

                                    @if($isRent)
                                        Rental
                                    @else
                                        Purchase
                                    @endif

                                </td>
                            </tr>

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
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>SKU</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    {{ $order->bike->SKU ?? 'Not available' }}
                                </td>
                            </tr>

                            @if($isRent)
                                <tr>
                                    <td style="padding:12px 15px;">
                                        <strong>Serial number</strong>
                                    </td>

                                    <td align="right"
                                        style="padding:12px 15px;">
                                        {{ $order->bike->serialnum ?? 'Not available' }}
                                    </td>
                                </tr>
                            @endif

                        </table>

                        {{-- Order information --}}
                        <h3 style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;">
                            Order information
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
                                    <strong>Order date</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    {{
                                        $order->order_date
                                            ? $order->order_date->format('d/m/Y')
                                            : $order->created_at->format('d/m/Y')
                                    }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Price</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb; font-weight:700;">
                                    {{ number_format($order->price, 2) }} EUR
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Payment method</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    {{ $order->payed_off ? 'Card' : 'Cash on delivery' }}
                                </td>
                            </tr>

                            @if($isRent)

                                <tr>
                                    <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                        <strong>Pickup location</strong>
                                    </td>

                                    <td align="right"
                                        style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                        {{ $order->location->name ?? 'Not available' }}
                                    </td>
                                </tr>

                                @if($order->start_date ?? null)
                                    <tr>
                                        <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                            <strong>Rental start</strong>
                                        </td>

                                        <td align="right"
                                            style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                            {{ $order->start_date->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endif

                                @if($order->end_date ?? null)
                                    <tr>
                                        <td style="padding:12px 15px;">
                                            <strong>Rental end</strong>
                                        </td>

                                        <td align="right"
                                            style="padding:12px 15px;">
                                            {{ $order->end_date->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endif

                            @else

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

                            @endif

                        </table>

                            <p style="margin:30px 0 0;">
                                Please open the staff order management page and review
                                the new order.
                            </p>

                            <p style="margin:5px 0 0; color:#6b7280; font-size:14px;">
                                This notification was sent automatically when the
                                customer completed the order.
                            </p>

                            <table role="presentation"
                                cellspacing="0"
                                cellpadding="0"
                                style="margin:25px auto 0;">

                                <tr>
                                    <td align="center"
                                        style="border-radius:6px; background-color:#1f2937;">

                                        <a href="{{ route('dashboard.management.bikes') }}"
                                            style="
                                                    display:inline-block;
                                                    padding:12px 24px;
                                                    color:#ffffff;
                                                    text-decoration:none;
                                                    font-size:15px;
                                                    font-weight:700;
                                                    border-radius:6px;
                                                                        ">
                                                View order
                                        </a>

                                    </td>
                                </tr>

                            </table>
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
                            Automated staff order notification
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>
