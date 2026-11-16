<?php

namespace App\Support;

/**
 * Pure PHP A4 report. The caller provides the already-authorized, filtered
 * department duty records; no database access or attendance writes here.
 */
final class StaffDutyMonitoringPdf
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

    public static function render(string $department, array $records, array $summary, array $filters): string
    {
        $doc = new self();
        $doc->cover($department, $summary, $filters);
        $doc->records($records);
        return $doc->build();
    }

    private function nextPage(): void
    {
        if ($this->pageNumber > 0) {
            $this->pages[] = $this->commands;
        }
        $this->commands = '';
        $this->pageNumber++;
    }

    private function cover(string $department, array $summary, array $filters): void
    {
        $this->nextPage();
        $this->rect(0, 0, self::WIDTH, 141, self::NAVY);
        $this->rect(0, 138, self::WIDTH, 4, self::GOLD);
        $this->write('DYSIGN / DEPARTMENT STAFF', 40, 40, 10, true, self::GOLD);
        $this->write('Duty Monitoring Report', 40, 80, 22, true, [1,1,1]);
        $this->write('Attendance Personnel assignments and recorded duty activity', 40, 106, 9, false, [0.89,0.91,0.98]);

        $this->label('REPORT SCOPE', 40, 173);
        $this->write('Department: ' . $this->shorten($department, 65), 40, 201, 12, true, self::NAVY);
        $this->write('Generated: ' . (new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila')))->format('M d, Y g:i A') . ' (PH)', 40, 225, 9, false, self::SLATE);
        $this->line(40, 248, 555, 248, self::BORDER, 1);

        $this->label('FILTERED DUTY SUMMARY', 40, 280);
        $cards = [
            ['TOTAL', (int) $summary['total']],
            ['COMPLETED', (int) $summary['completed']],
            ['NEEDS REVIEW', (int) $summary['attention']],
            ['ASSIGNED / ON DUTY', (int) $summary['active']],
        ];
        foreach ($cards as $i => [$label, $value]) {
            $x = 40 + $i * 130;
            $this->rect($x, 300, 123, 80, self::LIGHT);
            $this->write($label, $x+9, 324, 7.5, true, self::SLATE);
            $this->write((string)$value, $x+9, 359, 22, true, self::NAVY);
        }
        $this->write('Assigned ' . $summary['assigned'] . '    On Duty ' . $summary['on_duty'] . '    Completed ' . $summary['completed'] . '    Missed ' . $summary['missed'] . '    Incomplete ' . $summary['incomplete'], 40, 405, 9, false, self::SLATE);

        $this->label('APPLIED FILTERS', 40, 463);
        $this->write('Personnel / event search: ' . $this->shorten($filters['search'] ?: 'All', 54), 40, 490, 10, false, self::SLATE);
        $this->write('Status: ' . ($filters['status'] ?: 'All') . '  |  Event: ' . ($filters['event'] ?: 'All department events'), 40, 517, 9, false, self::SLATE);
        $this->write('Event dates: ' . ($filters['date_from'] ?: 'Any') . '  to  ' . ($filters['date_to'] ?: 'Any'), 40, 542, 9, false, self::SLATE);
        $this->write('Sort: ' . ucfirst($filters['sort']) . '  |  Matching records: ' . $summary['total'], 40, 568, 9, false, self::SLATE);

        $this->label('REPORT NOTES', 40, 639);
        $this->write('Check-in and check-out times reflect saved personnel duty records only.', 40, 668, 9, false, self::SLATE);
        $this->write('Blank timestamps mean no time was recorded. Missing times are not inferred.', 40, 691, 9, false, self::SLATE);
        $this->write('This report is system-generated and is not a signed certification.', 40, 714, 9, false, self::SLATE);
    }

    private function records(array $records): void
    {
        $this->nextPage();
        $this->tableHeading('DUTY RECORDS');
        $y = 177;
        foreach ($records as $i => $item) {
            if ($y + 49 > 760) {
                $this->nextPage();
                $this->tableHeading('DUTY RECORDS (CONTINUED)');
                $y = 177;
            }
            $this->rect(40, $y, 515, 47, $i % 2 === 0 ? [1,1,1] : self::LIGHT);
            $this->write($this->shorten(trim($item->last_name . ', ' . $item->first_name), 24), 47, $y+17, 8.2, true, self::NAVY);
            $this->write($this->shorten($item->position ?: 'Attendance Personnel', 24), 47, $y+33, 7.3, false, self::SLATE);
            $this->write($this->shorten((string)$item->event_name, 23), 184, $y+17, 8.1, true, self::NAVY);
            $this->write($this->formatDate($item->event_date), 184, $y+33, 7.5, false, self::SLATE);
            $this->write($this->formatClock($item->start_time) . ' - ' . $this->formatClock($item->end_time), 317, $y+25, 7.1, false, self::SLATE);
            $this->write($this->formatClock($item->check_in_at), 397, $y+25, 7.5, false, self::SLATE);
            $this->write($this->formatClock($item->check_out_at), 449, $y+25, 7.5, false, self::SLATE);
            $this->write($this->shorten((string)$item->duty_status, 11), 501, $y+17, 7.8, true, self::NAVY);
            $this->write($item->completed_minutes === null ? 'No duration' : (string)((int)$item->completed_minutes) . ' min', 501, $y+33, 7, false, self::SLATE);
            $this->line(40, $y+47, 555, $y+47, self::BORDER, .6);
            $y += 49;
        }
        if (!$records) {
            $this->write('No duty records match the selected filters.', 46, 217, 10, false, self::SLATE);
        }
    }

    private function tableHeading(string $title): void
    {
        $this->rect(0, 0, self::WIDTH, 102, self::NAVY);
        $this->rect(0, 98, self::WIDTH, 4, self::GOLD);
        $this->write('DYSIGN / PERSONNEL DUTY MONITORING', 40, 40, 9, true, self::GOLD);
        $this->write($title, 40, 73, 16, true, [1,1,1]);
        $this->rect(40, 136, 515, 38, self::LIGHT);
        foreach ([['PERSONNEL',47], ['EVENT / DATE',184], ['SCHEDULE',317], ['IN',397], ['OUT',449], ['STATUS',501]] as [$label, $x]) {
            $this->write($label, $x, 159, 7.6, true, self::NAVY);
        }
    }

    private function formatDate($value): string
    {
        try { return (new \DateTimeImmutable((string)$value))->format('M d, Y'); }
        catch (\Throwable $e) { return 'N/A'; }
    }

    private function formatClock($value): string
    {
        if (!$value) { return '--'; }
        try { return (new \DateTimeImmutable((string)$value))->format('g:i A'); }
        catch (\Throwable $e) { return '--'; }
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
        $this->write('DySign - Personnel duty monitoring  /  Authorized department staff', 40, 808, 8, false, self::SLATE);
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
