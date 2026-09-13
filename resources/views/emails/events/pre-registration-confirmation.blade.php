<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Registration Confirmed</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <!-- Header -->
        <tr>
            <td style="background-color: #861408; padding: 24px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 20px;">Pre-Registration Successful!</h1>
            </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding: 30px;">
                <p style="font-size: 16px; color: #333333;">Thank you, <strong>{{ $user->first_name }}
                        {{ $user->last_name }}</strong>!</p>
                <p style="color: #555555; line-height: 1.5;">You have successfully pre-registered for
                    <strong>{{ $event->title }}</strong>.
                </p>

                <!-- Credentials Card -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; margin: 20px 0;">
                    <tr>
                        <td>
                            <p style="margin: 0 0 8px 0; color: #333333;">Your email / username:
                                <strong>{{ $user->email }}</strong>
                            </p>
                            <p style="margin: 0; color: #333333;">Temporary Pwd:
                                <strong
                                    style="background-color: #e2e8f0; padding: 2px 8px; border-radius: 4px; font-family: monospace; font-size: 15px; color: #1e293b;">{{ $tempPassword }}</strong>
                            </p>
                        </td>
                    </tr>
                </table>

                <p
                    style="color: #d97706; font-size: 14px; background-color: #fef3c7; padding: 10px; border-radius: 4px; margin: 0 0 20px 0;">
                    <strong>Important:</strong> Please log in using these credentials and update your password under
                    your account settings.
                </p>

                <!-- Button -->
                <div style="text-align: center; margin-top: 25px;">
                    <a href="{{ route('login') }}"
                        style="background-color: #861408; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; display: inline-block;">Log
                        In to Dashboard</a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </td>
        </tr>
    </table>
</body>

</html>
