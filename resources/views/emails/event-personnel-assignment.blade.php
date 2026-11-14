<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>DySign Personnel Assignment</title></head>
<body style="margin:0;padding:0;background:#f4f5f8;font-family:Arial,Helvetica,sans-serif;color:#333333;">
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f4f5f8;padding:32px 16px;"><tr><td align="center">
  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:650px;background:#ffffff;border:1px solid #e5e7eb;">
    <tr><td style="background:#101064;border-bottom:4px solid #D4A017;padding:28px 32px;">
      <div style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#E7C75B;margin-bottom:8px;">Personnel Duty Notification</div>
      <div style="color:#ffffff;font-size:24px;font-weight:bold;">DySign</div>
      <div style="margin-top:6px;color:#c8c8e8;font-size:13px;">Event Participation Management System</div>
    </td></tr>
    <tr><td style="padding:32px;">
      <p style="margin:0;font-size:15px;line-height:1.7;">Good day, <strong>{{ $recipientName }}</strong>!</p>
      <h1 style="margin:24px 0 12px;color:#101064;font-size:22px;line-height:1.4;">You have been assigned to an event</h1>
      <p style="margin:0 0 18px;font-size:14px;line-height:1.8;color:#575b68;">Your department has assigned you as Attendance Personnel for the event below.</p>
      <div style="padding:18px;background:#f8f8fb;border-left:4px solid #D4A017;margin:20px 0;">
        <div style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#777777;margin-bottom:8px;">Event Assignment</div>
        <strong style="color:#101064;font-size:16px;">{{ $eventName }}</strong>
        <div style="font-size:13px;line-height:1.9;color:#555b69;margin-top:8px;">
          <div><strong>Department:</strong> {{ $departmentName }}</div>
          <div><strong>Date:</strong> {{ $eventDate }}</div>
          <div><strong>Time:</strong> {{ $startTime }} – {{ $endTime }}</div>
          <div><strong>Venue:</strong> {{ $venue }}</div>
          <div><strong>Role:</strong> Attendance Personnel</div>
        </div>
      </div>
      <div style="margin:18px 0;background:#fff8e5;border-left:4px solid #D4A017;padding:16px;color:#64480d;font-size:13px;line-height:1.8;">
        <strong>Attendance Duty Assigned</strong><br>
        Review your assigned duty and event schedule in the DySign portal. Follow the authorized RFID attendance procedures during the event.
      </div>
      <a href="{{ $dutiesUrl }}" style="display:inline-block;margin:0 0 20px;padding:12px 18px;background:#101064;color:#ffffff;text-decoration:none;font-size:13px;font-weight:bold;">View My Duties</a>
      <p style="font-size:12px;line-height:1.7;color:#7b8190;">If you believe this assignment is incorrect or you have a schedule conflict, contact your department event coordinator.</p>
    </td></tr>
    <tr><td style="padding:20px 32px;border-top:1px solid #e5e7eb;background:#f8f8fb;color:#9197a5;font-size:11px;line-height:1.6;">This is an automated personnel assignment notification sent through the DySign Event Participation Management System.</td></tr>
  </table>
</td></tr></table>
</body></html>
