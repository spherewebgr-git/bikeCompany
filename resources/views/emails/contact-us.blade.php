<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>New Contact Message</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

<table
    role="presentation"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    style="width:100%; background-color:#f3f4f6; padding:30px 15px;"
>
    <tr>
        <td align="center">

            <table
                role="presentation"
                width="600"
                cellspacing="0"
                cellpadding="0"
                style="width:100%; max-width:600px; background-color:#ffffff; border-radius:10px;"
            >

                <tr>
                    <td
                        align="center"
                        style="padding:30px 20px; background-color:#1f2937; border-radius:10px 10px 0 0;"
                    >
                        <img
                            src="{{ $message->embed(public_path('images/bikeco-light-logo.png')) }}"
                            alt="{{ config('app.name', 'Bike Company') }}"
                            width="180"
                            style="display:block; height:auto; max-width:180px; margin:0 auto 15px;"
                        >

                        <h1 style="margin:0; color:#ffffff; font-size:27px;">
                            New Contact Message
                        </h1>

                        <p style="margin:10px 0 0; color:#d1d5db; font-size:15px;">
                            A customer submitted the Contact Us form
                        </p>
                    </td>
                </tr>

                <tr>
                    <td
                        style="padding:35px 30px; color:#374151; font-size:15px; line-height:1.6;"
                    >

                        <h3
                            style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;"
                        >
                            Contact information
                        </h3>

                        <table
                            role="presentation"
                            width="100%"
                            cellspacing="0"
                            cellpadding="0"
                            style="width:100%; border-collapse:collapse; margin-bottom:30px;"
                        >
                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Name</strong>
                                </td>

                                <td
                                    align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;"
                                >
                                    {{ $senderName }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px; border-bottom:1px solid #e5e7eb;">
                                    <strong>Email</strong>
                                </td>

                                <td
                                    align="right"
                                    style="padding:12px 15px; border-bottom:1px solid #e5e7eb;"
                                >
                                    <a href="mailto:{{ $senderEmail }}">
                                        {{ $senderEmail }}
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px;">
                                    <strong>Subject</strong>
                                </td>

                                <td
                                    align="right"
                                    style="padding:12px 15px;"
                                >
                                    {{ $contactSubject }}
                                </td>
                            </tr>
                        </table>

                        <h3
                            style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;"
                        >
                            Message
                        </h3>

                        <div
                            style="margin-top:15px; padding:20px; background-color:#f9fafb; border:1px solid #e5e7eb; border-radius:6px;"
                        >
                            {!! nl2br(e($contactMessage)) !!}
                        </div>

                        <p style="margin:30px 0 0; color:#6b7280;">
                            You can reply directly to this email to contact
                            {{ $senderName }}.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td
                        align="center"
                        style="padding:22px 20px; background-color:#f9fafb; border-top:1px solid #e5e7eb; border-radius:0 0 10px 10px; color:#6b7280; font-size:13px;"
                    >
                        <p style="margin:0 0 5px;">
                            © {{ date('Y') }} Bike Company
                        </p>

                        <p style="margin:0;">
                            This message was sent through the Contact Us form.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
