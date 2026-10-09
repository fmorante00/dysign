<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $announcement->subject }}
    </title>
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background: #f4f5f8;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
    "
>

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    role="presentation"
    style="
        background: #f4f5f8;
        padding: 32px 16px;
    "
>
    <tr>
        <td align="center">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                role="presentation"
                style="
                    max-width: 650px;
                    background: #ffffff;
                    border: 1px solid #e5e7eb;
                "
            >

                <tr>
                    <td
                        style="
                            background: #101064;
                            padding: 28px 32px;
                            border-bottom: 4px solid #D4A017;
                        "
                    >

                        <div
                            style="
                                font-size: 11px;
                                letter-spacing: 3px;
                                text-transform: uppercase;
                                color: #E7C75B;
                                margin-bottom: 8px;
                            "
                        >
                            Event Communication
                        </div>

                        <div
                            style="
                                color: #ffffff;
                                font-size: 24px;
                                font-weight: bold;
                            "
                        >
                            DySign
                        </div>

                        <div
                            style="
                                color: rgba(255,255,255,.7);
                                font-size: 13px;
                                margin-top: 6px;
                            "
                        >
                            Event Participation Management System
                        </div>

                    </td>
                </tr>

                <tr>
                    <td style="padding: 32px;">

                        <p
                            style="
                                margin-top: 0;
                                font-size: 15px;
                                line-height: 1.7;
                            "
                        >
                            Good day,
                            <strong>{{ $recipientName }}</strong>!
                        </p>

                        <h1
                            style="
                                margin: 24px 0 12px;
                                color: #101064;
                                font-size: 22px;
                                line-height: 1.4;
                            "
                        >
                            {{ $announcement->subject }}
                        </h1>

                        @if($announcement->event)

                            <div
                                style="
                                    margin: 20px 0;
                                    padding: 18px;
                                    background: #f8f8fb;
                                    border-left: 4px solid #D4A017;
                                "
                            >

                                <div
                                    style="
                                        font-size: 11px;
                                        text-transform: uppercase;
                                        letter-spacing: 2px;
                                        color: #777777;
                                        margin-bottom: 8px;
                                    "
                                >
                                    Event
                                </div>

                                <strong
                                    style="color: #101064;"
                                >
                                    {{ $announcement->event->event_name }}
                                </strong>

                                @if($announcement->event->event_date)

                                    <div
                                        style="
                                            margin-top: 8px;
                                            font-size: 13px;
                                            color: #666666;
                                        "
                                    >
                                        Date:
                                        {{ \Carbon\Carbon::parse(
                                            $announcement->event->event_date
                                        )->format('F d, Y') }}
                                    </div>

                                @endif

                                @if(
                                    $announcement->event->start_time &&
                                    $announcement->event->end_time
                                )

                                    <div
                                        style="
                                            margin-top: 4px;
                                            font-size: 13px;
                                            color: #666666;
                                        "
                                    >
                                        Time:

                                        {{ \Carbon\Carbon::parse(
                                            $announcement->event->start_time
                                        )->format('g:i A') }}

                                        -

                                        {{ \Carbon\Carbon::parse(
                                            $announcement->event->end_time
                                        )->format('g:i A') }}
                                    </div>

                                @endif

                                @if($announcement->event->location)

                                    <div
                                        style="
                                            margin-top: 4px;
                                            font-size: 13px;
                                            color: #666666;
                                        "
                                    >
                                        Venue:
                                        {{ $announcement->event->location }}
                                    </div>

                                @endif

                            </div>

                        @endif

                        <div
                            style="
                                margin-top: 24px;
                                font-size: 15px;
                                line-height: 1.8;
                                color: #444444;
                            "
                        >
                            {!! nl2br(e($announcement->message)) !!}
                        </div>

                        <p
                            style="
                                margin-top: 32px;
                                font-size: 13px;
                                color: #777777;
                                line-height: 1.6;
                            "
                        >
                            Any image or PDF included with this announcement
                            can be found in the email attachments.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td
                        style="
                            padding: 20px 32px;
                            background: #f8f8fb;
                            border-top: 1px solid #eeeeee;
                            color: #888888;
                            font-size: 11px;
                            line-height: 1.6;
                        "
                    >
                        This is an automated announcement sent through
                        the DySign Event Participation Management System.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>