<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Bike Return Reminder</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

@php
    $customerName =
        trim(
            ($order->user->first_name ?? '') . ' ' .
            ($order->user->last_name ?? '')
        ) ?: ($order->user->name ?? 'Customer');
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

                <tr>
                    <td align="center"
                        style="padding:30px 20px; background-color:#1f2937; border-radius:10px 10px 0 0;">

                        <img
                            src="{{ $message->embed(public_path('images/bikeco-light-logo.png')) }}"
                            alt="{{ config('app.name', 'Bike Company') }}"
                            width="180"
                            style="display:block; height:auto; max-width:180px; margin:0 auto 15px;">

                        <h1 style="margin:0; color:#ffffff; font-size:27px; font-weight:700;">
                            Bike return reminder
                        </h1>

                        <p style="margin:10px 0 0; color:#d1d5db; font-size:15px;">
                            Your rental return date is approaching
                        </p>

                    </td>
                </tr>

                <tr>
                    <td style="padding:35px 30px; color:#374151; font-size:15px; line-height:1.6;">

                        <p style="margin:0 0 15px;">
                            Hello <strong>{{ $customerName }}</strong>,
                        </p>

                        <p style="margin:0 0 20px;">
                            This is a reminder that your rented bike must be returned
                            on the date shown below.
                        </p>

                        <div style="margin:25px 0; padding:20px; background-color:#fff7ed; border:1px solid #fdba74; border-radius:6px;">

                            <p style="margin:0; color:#9a3412; font-size:16px; font-weight:700;">
                                Return date
                            </p>

                            <p style="margin:8px 0 0; color:#c2410c; font-size:22px; font-weight:700;">
                                {{ $order->rent_end->format('d/m/Y') }}
                            </p>

                        </div>

                        <h3 style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;">
                            Rental information
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
                                    <strong>Bike</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    {{ $order->bike->brand->name ?? 'Bike' }}
                                    {{ $order->bike->type->name ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Rental start</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    {{ $order->rent_start?->format('d/m/Y') ?? 'Not available' }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Return date</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb; font-weight:700;">
                                    {{ $order->rent_end->format('d/m/Y') }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px;">
                                    <strong>Return location</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px;">
                                    {{ $order->location->name ?? 'Not available' }}
                                </td>
                            </tr>

                        </table>

                        <div style="margin:25px 0; padding:20px; background-color:#eff6ff; border:1px solid #93c5fd; border-radius:6px;">

                            <p style="margin:0; color:#1d4ed8; font-size:16px; font-weight:700;">
                                Please return the bike on time
                            </p>

                            <p style="margin:8px 0 0; color:#1e40af;">
                                Please return the bike to the selected location by
                                the end of your rental period.
                            </p>

                        </div>

                        <p style="margin:30px 0 0;">
                            Thank you for choosing Bike Company.
                        </p>

                        <p style="margin:5px 0 0; color:#6b7280; font-size:14px;">
                            This reminder was sent automatically before the scheduled
                            bike return date.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td align="center"
                        style="padding:22px 20px; background-color:#f9fafb; border-top:1px solid #e5e7eb; border-radius:0 0 10px 10px; color:#6b7280; font-size:13px;">

                        <p style="margin:0 0 5px;">
                            © {{ date('Y') }} Bike Company
                        </p>

                        <p style="margin:0;">
                            Automated rental return reminder
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>
