<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DySign Event Eligibility</title>
</head>
<body style="margin:0;padding:0;background:#f4f5f8;font-family:Arial,Helvetica,sans-serif;color:#333333;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f4f5f8;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:650px;background:#ffffff;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#101064;padding:28px 32px;border-bottom:4px solid #D4A017;">
                            <div style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#E7C75B;margin-bottom:8px;">
                                Event Eligibility Notification
                            </div>
                            <div style="color:#ffffff;font-size:24px;font-weight:bold;">DySign</div>
                            <div style="color:#c5c5e0;font-size:13px;margin-top:6px;">Event Participation Management System</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0;font-size:15px;line-height:1.7;">
                                Good day, <strong>{{ $recipientName }}</strong>!
                            </p>

                            <h1 style="margin:24px 0 12px;color:#101064;font-size:22px;line-height:1.4;">
                                You are eligible for an event
                            </h1>
                            <p style="font-size:15px;line-height:1.8;color:#444444;margin:0 0 20px;">
                                You have been included in the eligible student roster for the event below.
                            </p>

                            <div style="margin:20px 0;padding:18px;background:#f8f8fb;border-left:4px solid #D4A017;">
                                <div style="font-size:11px;text-transform:uppercase;letter-spacing:2px;color:#777777;margin-bottom:8px;">
                                    Event
                                </div>
                                <div style="color:#101064;font-size:16px;font-weight:bold;line-height:1.5;">{{ $eventName }}</div>
                                <div style="margin-top:8px;font-size:13px;line-height:1.8;color:#666666;">
                                    <div><strong>Date:</strong> {{ $eventDate }}</div>
                                    <div><strong>Time:</strong> {{ $startTime }}@if($endTime) - {{ $endTime }}@endif</div>
                                    <div><strong>Venue:</strong> {{ $venue }}</div>
                                    <div><strong>Participation:</strong> {{ $participationType }}</div>
                                </div>
                            </div>

                            @if($participationType === 'Required')
                                <div style="margin:22px 0;padding:16px 18px;background:#fff9e8;border-left:4px solid #D4A017;font-size:14px;line-height:1.8;color:#51401b;">
                                    <strong>Attendance Required</strong><br>
                                    You are automatically included as a required participant. Please attend the event and complete your RFID check-in as instructed by the attendance personnel. This email is not proof of attendance.
                                </div>
                            @else
                                <div style="margin:22px 0;padding:16px 18px;background:#eef4ff;border-left:4px solid #101064;font-size:14px;line-height:1.8;color:#263a65;">
                                    <strong>Optional Participation</strong><br>
                                    Your participation is optional. Please visit the DySign Student Portal to register while the registration window is open. Being eligible does not automatically register you.
                                </div>
                            @endif

                            <table cellpadding="0" cellspacing="0" role="presentation" style="margin:25px 0 22px;">
                                <tr>
                                    <td style="background:#101064;padding:12px 20px;">
                                        <a href="{{ $portalUrl }}" style="color:#ffffff;text-decoration:none;font-size:13px;font-weight:bold;display:inline-block;">View Eligible Events</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:25px 0 0;font-size:13px;line-height:1.7;color:#777777;">
                                This notification reflects a change in your eligibility. For questions or corrections, please contact your department event coordinator.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px;background:#f8f8fb;border-top:1px solid #eeeeee;color:#888888;font-size:11px;line-height:1.6;">
                            This is an automated eligibility notification sent through the DySign Event Participation Management System.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
