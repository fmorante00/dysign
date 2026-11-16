<?php

namespace App\Support;

/**
 * Pure PHP, read-only A4 PDF generator for one department event.
 * The controller supplies department-scoped, filtered attendance records.
 */
final class StaffEventAttendancePdf
{
    private const WIDTH = 595.28;
    private const HEIGHT = 841.89;
    private const NAVY = [0.063, 0.063, 0.392];
    private const GOLD = [0.831, 0.627, 0.09];
    private const SLATE = [0.275, 0.329, 0.408];
    private const LIGHT = [0.957, 0.969, 0.984];
    private const BORDER = [0.89, 0.918, 0.957];
    private array $pages = [];
    private string $commands = '';
    private int $pageNumber = 0;

    public static function render(string $department, object $event, array $records, object $statistics, array $filters): string
    {
        $report = new self();
        $report->cover($department, $event, $records, $statistics, $filters);
        $report->records($event, $records);
        return $report->build();
    }

    private function nextPage(): void
    {
        if ($this->pageNumber > 0) {
            $this->pages[] = $this->commands;
        }
        $this->commands = '';
        $this->pageNumber++;
    }

    private function cover(string $department, object $event, array $records, object $statistics, array $filters): void
    {
        $this->nextPage();
        $this->rect(0, 0, self::WIDTH, 132, self::NAVY);
        $this->rect(0, 128, self::WIDTH, 4, self::GOLD);
        $this->write('DYSIGN  /  DEPARTMENT STAFF', 40, 38, 10, true, self::GOLD);
        $this->write('Event Attendance Report', 40, 77, 21, true, [1, 1, 1]);
        $this->write('Recorded attendance and check-in/check-out details', 40, 102, 9, false, [0.87, 0.9, 0.98]);

        $this->label('SELECTED EVENT', 40, 161);
        $this->write($this->shorten((string) $event->event_name, 60), 40, 190, 16, true, self::NAVY);
        $date = is_object($event->event_date) && method_exists($event->event_date, 'format')
            ? $event->event_date->format('M d, Y')
            : (string) ($event->event_date ?? 'N/A');
        $this->write('Date: ' . $date . '  |  Status: ' . (string) $event->status, 40, 212, 9, false, self::SLATE);
        $this->write('Location: ' . $this->shorten((string) ($event->location ?? 'N/A'), 76), 40, 231, 9, false, self::SLATE);
        $this->write('Department: ' . $this->shorten($department, 72), 40, 250, 9, false, self::SLATE);
        $this->write('Generated: ' . (new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila')))->format('M d, Y g:i A') . ' (PH)', 40, 269, 9, false, self::SLATE);
        $this->line(40, 291, 555, 291, self::BORDER, 1);

        $this->label('EVENT ATTENDANCE SUMMARY', 40, 316);
        $metrics = [
            ['RECORDED', (string) (int) $statistics->recorded],
            ['ATTENDED', (string) ((int) $statistics->present + (int) $statistics->late)],
            ['PRESENT', (string) (int) $statistics->present],
            ['LATE', (string) (int) $statistics->late],
        ];
        foreach ($metrics as $i => [$label, $value]) {
            $x = 40 + $i * 130;
            $this->rect($x, 331, 123, 82, self::LIGHT);
            $this->write($label, $x + 10, 357, 8, true, self::SLATE);
            $this->write($value, $x + 10, 392, 22, true, self::NAVY);
        }
        $this->write('Attended = saved Present or Late records. No missing check-in/out times are inferred.', 40, 442, 9, false, self::SLATE);

        $this->label('APPLIED FILTERS', 40, 491);
        $this->write('Status: ' . $this->shorten((string) $filters['status'], 40) . '  |  Student search: ' . $this->shorten((string) ($filters['search'] ?: 'All'), 35), 40, 517, 9, false, self::SLATE);
        $this->write('Program: ' . $this->shorten((string) ($filters['program'] ?: 'All'), 24) . '  |  Sort: ' . str_replace('_', ' ', (string) $filters['sort']), 40, 538, 9, false, self::SLATE);
        $this->write('Matching attendance entries: ' . count($records), 40, 562, 9, true, self::NAVY);

        $this->label('REPORT NOTES', 40, 616);
        $this->write('Times come from saved attendance records, not event schedules.', 40, 639, 9, false, self::SLATE);
        $this->write('A blank check-in or check-out means no verified time was recorded.', 40, 662, 9, false, self::SLATE);
        $this->write('The complete filtered student list begins on the next page.', 40, 685, 9, false, self::SLATE);
        $this->write('For authorized department staff only. System-generated, not a signed certification.', 40, 724, 8, false, self::SLATE);
    }

    private function records(object $event, array $records): void
    {
        $this->nextPage();
        $this->tableHeading('ATTENDANCE RECORDS', $this->shorten((string) $event->event_name, 68));
        $y = 173;
        foreach ($records as $i => $row) {
            if ($y + 44 > 763) {
                $this->nextPage();
                $this->tableHeading('ATTENDANCE RECORDS (CONTINUED)', $this->shorten((string) $event->event_name, 68));
                $y = 173;
            }
            $this->rect(40, $y, 515, 43, $i % 2 === 0 ? [1, 1, 1] : self::LIGHT);
            $name = trim((string) $row->last_name . ', ' . (string) $row->first_name);
            $this->write($this->shorten($name, 32), 47, $y + 17, 8.7, true, self::NAVY);
            $this->write($this->shorten((string) $row->student_number, 29), 47, $y + 32, 7.5, false, self::SLATE);
            $this->write($this->shorten((string) ($row->program_code ?: 'N/A') . ' / Y' . ($row->year_level ?: '-'), 16), 239, $y + 25, 8, false, self::SLATE);
            $this->write($this->formatTime($row->time_in), 327, $y + 25, 7.8, false, self::SLATE);
            $this->write($this->formatTime($row->time_out), 406, $y + 25, 7.8, false, self::SLATE);
            $this->write($this->shorten((string) $row->status, 14), 492, $y + 25, 8, true, self::NAVY);
            $this->line(40, $y + 43, 555, $y + 43, self::BORDER, .6);
            $y += 45;
        }
        if (!$records) {
            $this->write('No recorded attendance matches the selected event and filters.', 48, 212, 10, false, self::SLATE);
        }
    }

    private function formatTime($value): string
    {
        if (!$value) { return 'Not recorded'; }
        try {
            return (new \DateTimeImmutable((string) $value, new \DateTimeZone('Asia/Manila')))->format('m/d h:i A');
        } catch (\Throwable $e) {
            return 'Not recorded';
        }
    }

    private function tableHeading(string $title, string $subtitle): void
    {
        $this->rect(0, 0, self::WIDTH, 94, self::NAVY);
        $this->rect(0, 91, self::WIDTH, 4, self::GOLD);
        $this->write('DYSIGN  /  EVENT ATTENDANCE REPORT', 40, 35, 9, true, self::GOLD);
        $this->write($title, 40, 67, 16, true, [1, 1, 1]);
        $this->write($subtitle, 40, 118, 9, false, self::SLATE);
        $this->rect(40, 136, 515, 36, self::LIGHT);
        foreach ([['STUDENT', 47], ['PROGRAM', 239], ['TIME IN', 327], ['TIME OUT', 406], ['STATUS', 492]] as [$label, $x]) {
            $this->write($label, $x, 158, 8, true, self::NAVY);
        }
    }
    private function build(): string
    {
        $this->pages[] = $this->commands;
        $count = count($this->pages);
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
        ];
        $kids = [];
        foreach ($this->pages as $index => $commands) {
            $pageId = 5 + $index * 2;
            $contentId = $pageId + 1;
            $kids[] = $pageId . ' 0 R';
            $commands .= $this->footer($index + 1, $count);
            $objects[$pageId] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' . self::WIDTH . ' ' . self::HEIGHT . '] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents ' . $contentId . ' 0 R >>';
            $objects[$contentId] = '<< /Length ' . strlen($commands) . " >>\nstream\n" . $commands . "\nendstream";
        }
        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $count . ' >>';
        ksort($objects);
        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0 => 0];
        foreach ($objects as $id => $body) {
            $offsets[$id] = strlen($pdf);
            $pdf .= $id . " 0 obj\n" . $body . "\nendobj\n";
        }
        $xrefStart = strlen($pdf);
        $maxId = max(array_keys($objects));
        $pdf .= 'xref' . "\n0 " . ($maxId + 1) . "\n0000000000 65535 f \n";
        for ($i = 1; $i <= $maxId; $i++) {
            $pdf .= sprintf('%010d 00000 n ', $offsets[$i]) . "\n";
        }
        return $pdf . 'trailer' . "\n<< /Size " . ($maxId + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefStart . "\n%%EOF\n";
    }

    private function footer(int $page, int $total): string
    {
        $old = $this->commands;
        $this->commands = '';
        $this->line(40, 785, 555, 785, self::BORDER, .8);
        $this->write('DySign - Event attendance report  /  Authorized staff use only', 40, 808, 8, false, self::SLATE);
        $this->write('Page ' . $page . ' of ' . $total, 506, 808, 8, true, self::NAVY);
        $result = $this->commands;
        $this->commands = $old;
        return $result;
    }

    private function label(string $text, float $x, float $top): void
    {
        $this->write($text, $x, $top, 9, true, [0.62, 0.43, 0.035]);
    }

    private function rect(float $x, float $top, float $width, float $height, array $rgb): void
    {
        $this->commands .= $this->rgb($rgb) . sprintf(' rg %.2F %.2F %.2F %.2F re f', $x, self::HEIGHT - $top - $height, $width, $height) . "\n";
    }

    private function line(float $x1, float $top1, float $x2, float $top2, array $rgb, float $width): void
    {
        $this->commands .= $this->rgb($rgb) . sprintf(' RG %.2F w %.2F %.2F m %.2F %.2F l S', $width, $x1, self::HEIGHT - $top1, $x2, self::HEIGHT - $top2) . "\n";
    }

    private function write(string $text, float $x, float $top, float $font, bool $bold, array $rgb): void
    {
        $text = $this->winAnsi($text);
        $text = str_replace(['\\', '(', ')', "\r", "\n"], ['\\\\', '\\(', '\\)', ' ', ' '], $text);
        $this->commands .= $this->rgb($rgb) . sprintf(' rg BT /%s %.2F Tf %.2F %.2F Td (%s) Tj ET', $bold ? 'F2' : 'F1', $font, $x, self::HEIGHT - $top, $text) . "\n";
    }

    private function rgb(array $values): string
    {
        return sprintf('%.3F %.3F %.3F', $values[0], $values[1], $values[2]);
    }

    private function shorten(string $text, int $limit): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            return mb_strlen($text) <= $limit ? $text : mb_substr($text, 0, $limit - 3) . '...';
        }
        return strlen($text) <= $limit ? $text : substr($text, 0, $limit - 3) . '...';
    }

    private function winAnsi(string $text): string
    {
        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        return $converted === false ? preg_replace('/[^\x20-\x7E]/', '?', $text) : $converted;
    }
}
