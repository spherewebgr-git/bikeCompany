<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Password Changed</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

@php
    $customerName =
        trim(
            ($user->first_name ?? '') . ' ' .
            ($user->last_name ?? '')
        ) ?: ($user->name ?? 'Customer');
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
                            Password changed
                        </h1>

                        <p style="margin:10px 0 0; color:#d1d5db; font-size:15px;">
                            Your account password was updated successfully
                        </p>

                    </td>
                </tr>

                {{-- Main content --}}
                <tr>
                    <td style="padding:35px 30px; color:#374151; font-size:15px; line-height:1.6;">

                        <p style="margin:0 0 15px;">
                            Hello <strong>{{ $customerName }}</strong>,
                        </p>

                        <p style="margin:0 0 20px;">
                            This email confirms that the password for your Bike Company
                            account was changed successfully.
                        </p>

                        <div style="margin:25px 0; padding:20px; background-color:#ecfdf5; border:1px solid #a7f3d0; border-radius:6px;">

                            <p style="margin:0; color:#065f46; font-size:16px; font-weight:700;">
                                Your password has been updated
                            </p>

                            <p style="margin:8px 0 0; color:#047857;">
                                You can continue using your account with your new password.
                            </p>

                        </div>

                        {{-- Account information --}}
                        <h3 style="margin:0; padding:12px 15px; background-color:#f3f4f6; color:#111827; font-size:18px;">
                            Account information
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
                                    {{ $user->email }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 15px;">
                                    <strong>Changed at</strong>
                                </td>

                                <td align="right"
                                    style="padding:12px 15px;">
                                    {{ now()->format('d/m/Y H:i') }}
                                </td>
                            </tr>

                        </table>

                        <div style="margin:25px 0; padding:20px; background-color:#fef2f2; border:1px solid #fecaca; border-radius:6px;">

                            <p style="margin:0; color:#991b1b; font-size:16px; font-weight:700;">
                                Did you not make this change?
                            </p>

                            <p style="margin:8px 0 0; color:#b91c1c;">
                                If you did not change your password, please reset it
                                immediately and contact Bike Company support.
                            </p>

                        </div>

                        <table role="presentation"
                               cellspacing="0"
                               cellpadding="0"
                               style="margin:25px auto 0;">

                            <tr>
                                <td align="center"
                                    style="border-radius:6px; background-color:#1f2937;">

                                    <a href="{{ route('password.request') }}"
                                       style="
                                           display:inline-block;
                                           padding:12px 26px;
                                           color:#ffffff;
                                           text-decoration:none;
                                           font-size:15px;
                                           font-weight:700;
                                           border-radius:6px;
                                       ">
                                        Reset your password
                                    </a>

                                </td>
                            </tr>

                        </table>

                        <p style="margin:30px 0 0;">
                            Thank you for using Bike Company.
                        </p>

                        <p style="margin:5px 0 0; color:#6b7280; font-size:14px;">
                            For security reasons, your password is never displayed
                            or included in this email.
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
                            This is an automated security notification.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>
