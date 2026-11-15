<?php

namespace App\Support;

use App\Models\Student;
use Illuminate\Support\Collection;

/**
 * Small dependency-free, read-only PDF report renderer.
 * Uses built-in PDF Helvetica fonts; no Composer package, remote service,
 * temporary file, or changes to student/attendance data are necessary.
 */
final class StudentParticipationPdf
{
    private const WIDTH = 595.28;
    private const HEIGHT = 841.89;
    private const NAVY = [0.063, 0.063, 0.392];
    private const GOLD = [0.831, 0.627, 0.09];
    private const SLATE = [0.275, 0.329, 0.408];
    private const LIGHT = [0.957, 0.969, 0.984];
    private const BORDER = [0.89, 0.918, 0.957];

    /** @var list<string> */
    private array $pages = [];
    private string $commands = '';
    private int $pageNumber = 0;

    public static function render(Student $student, array $summary, Collection $records): string
    {
        $pdf = new self();
        $pdf->cover($student, $summary, $records->count());
        $pdf->records($records);
        return $pdf->build();
    }

    private function newPage(): void
    {
        if ($this->pageNumber > 0) {
            $this->pages[] = $this->commands;
        }
        $this->pageNumber++;
        $this->commands = '';
    }

    private function cover(Student $student, array $summary, int $recordCount): void
    {
        $this->newPage();
        $this->rect(0, 0, self::WIDTH, 120, self::NAVY);
        $this->rect(0, 117, self::WIDTH, 4, self::GOLD);
        $this->write('DYSIGN  /  STUDENT PORTAL', 40, 39, 10, true, self::GOLD);
        $this->write('Participation Progress Report', 40, 73, 21, true, [1, 1, 1]);
        $this->write('Student participation and recorded campus event attendance', 40, 96, 9, false, [0.88, 0.89, 0.98]);

        $name = trim(implode(' ', array_filter([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])));
        $this->label('STUDENT INFORMATION', 40, 153);
        $this->write($this->shorten($name ?: 'Student', 56), 40, 174, 15, true, self::NAVY);
        $this->write('Student No: ' . ($student->student_number ?: 'N/A'), 40, 193, 10, false, self::SLATE);
        $this->write('Program: ' . $this->shorten((string) ($student->program_code ?: $student->program_name ?: 'N/A'), 60), 40, 208, 9, false, self::SLATE);
        $this->write('Generated: ' . (new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila')))->format('M d, Y  g:i A') . ' (PH)', 40, 223, 9, false, self::SLATE);
        $this->line(40, 242, 555, 242, self::BORDER, 1);

        $this->label('PARTICIPATION SUMMARY', 40, 268);
        $boxes = [
            ['ELIGIBLE EVENTS', (string) $summary['eligible']],
            ['EVENTS ATTENDED', (string) $summary['attended']],
            ['ATTENDANCE ENTRIES', (string) $summary['recorded']],
            ['PARTICIPATION', number_format($summary['percentage'], 1) . '%'],
        ];
        foreach ($boxes as $index => [$label, $value]) {
            $x = 40 + $index * 130;
            $this->rect($x, 283, 123, 75, self::LIGHT);
            $this->write($label, $x + 10, 304, 8, true, self::SLATE);
            $this->write($value, $x + 10, 337, 21, true, $index === 3 ? [0.67, 0.47, 0.03] : self::NAVY);
        }
        $this->write('Attendance rate (Present or Late) across completed eligible events', 40, 381, 9, false, self::SLATE);
        $this->rect(40, 395, 515, 12, [0.91, 0.93, 0.96]);
        $this->rect(40, 395, 515 * min(100, max(0, (float) $summary['percentage'])) / 100, 12, self::GOLD);
        $this->write($summary['attended'] . ' attended  |  ' . $summary['other'] . ' other completed eligible events', 40, 428, 9, true, self::NAVY);

        $this->label('RECORDED ATTENDANCE BREAKDOWN', 40, 467);
        $statuses = ['Present', 'Late', 'Checked In', 'Incomplete', 'Absent', 'Excused'];
        foreach ($statuses as $i => $status) {
            $col = $i % 3;
            $row = intdiv($i, 3);
            $x = 40 + $col * 175;
            $y = 481 + $row * 64;
            $this->rect($x, $y, 165, 55, self::LIGHT);
            $this->write($status, $x + 12, $y + 19, 9, false, self::SLATE);
            $this->write((string) ($summary['statuses'][$status] ?? 0), $x + 12, $y + 43, 17, true, self::NAVY);
        }

        $this->label('ABOUT THIS REPORT', 40, 639);
        $this->write('The attendance table starts on the next page and includes all saved records.', 40, 660, 9, false, self::SLATE);
        $this->write('No missing Time In or Time Out values are inferred from an event schedule.', 40, 677, 9, false, self::SLATE);
        $this->write('This is a system-generated student summary, not a signed certification.', 40, 694, 9, false, self::SLATE);
        $this->write('Total attendance rows in report: ' . $recordCount, 40, 721, 9, true, self::NAVY);
    }

    private function records(Collection $records): void
    {
        $this->newPage();
        $this->tableHeading('ALL RECORDED ATTENDANCE', 'Entries are ordered by event date (newest first).');
        $y = 174;
        $rowsOnPage = 0;
        foreach ($records as $record) {
            if ($y + 46 > 758) {
                $this->newPage();
                $this->tableHeading('ATTENDANCE RECORDS (CONTINUED)', 'Complete report - continued');
                $y = 174;
            }
            $shade = $rowsOnPage % 2 === 0 ? [1, 1, 1] : self::LIGHT;
            $this->rect(40, $y, 515, 43, $shade);
            $eventName = (string) ($record->event?->event_name ?? 'Event unavailable');
            $this->write($this->shorten($eventName, 31), 49, $y + 17, 9, true, self::NAVY);
            $this->write('Record #' . $record->attendance_id, 49, $y + 32, 7, false, self::SLATE);
            $this->write($record->event?->event_date?->format('M d, Y') ?? 'N/A', 255, $y + 25, 8, false, self::SLATE);
            $this->write($record->time_in?->format('g:i A') ?? '--', 340, $y + 25, 8, false, self::SLATE);
            $this->write($record->time_out?->format('g:i A') ?? '--', 410, $y + 25, 8, false, self::SLATE);
            $this->write($this->shorten((string) ($record->status ?: 'Unknown'), 13), 488, $y + 25, 8, true, self::NAVY);
            $this->line(40, $y + 43, 555, $y + 43, self::BORDER, .7);
            $y += 45;
            $rowsOnPage++;
        }
        if ($records->isEmpty()) {
            $this->write('No saved attendance records are available.', 49, 206, 10, false, self::SLATE);
        }
    }

    private function tableHeading(string $title, string $subtitle): void
    {
        $this->rect(0, 0, self::WIDTH, 93, self::NAVY);
        $this->rect(0, 91, self::WIDTH, 3, self::GOLD);
        $this->write('DYSIGN  /  PARTICIPATION REPORT', 40, 35, 9, true, self::GOLD);
        $this->write($title, 40, 67, 16, true, [1, 1, 1]);
        $this->write($subtitle, 40, 119, 9, false, self::SLATE);
        $this->rect(40, 135, 515, 35, self::LIGHT);
        foreach ([['EVENT', 49], ['EVENT DATE', 255], ['TIME IN', 340], ['TIME OUT', 410], ['STATUS', 488]] as [$label, $x]) {
            $this->write($label, $x, 157, 8, true, self::NAVY);
        }
    }

    private function build(): string
    {
        $this->pages[] = $this->commands;
        $pages = count($this->pages);
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
            $pageNo = $index + 1;
            $commands .= $this->footer($pageNo, $pages);
            $objects[$pageId] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' . self::WIDTH . ' ' . self::HEIGHT . '] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents ' . $contentId . ' 0 R >>';
            $objects[$contentId] = '<< /Length ' . strlen($commands) . " >>\nstream\n" . $commands . "\nendstream";
        }
        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $pages . ' >>';
        ksort($objects);
        $output = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0 => 0];
        foreach ($objects as $id => $content) {
            $offsets[$id] = strlen($output);
            $output .= $id . " 0 obj\n" . $content . "\nendobj\n";
        }
        $xrefOffset = strlen($output);
        $maxId = max(array_keys($objects));
        $output .= "xref\n0 " . ($maxId + 1) . "\n0000000000 65535 f \n";
        for ($i = 1; $i <= $maxId; $i++) {
            $output .= sprintf('%010d 00000 n ', $offsets[$i]) . "\n";
        }
        $output .= "trailer\n<< /Size " . ($maxId + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefOffset . "\n%%EOF\n";
        return $output;
    }

    private function footer(int $page, int $total): string
    {
        $before = $this->commands;
        $this->commands = '';
        $this->line(40, 788, 555, 788, self::BORDER, .8);
        $this->write('DySign - Student participation summary  |  Private student record', 40, 810, 8, false, self::SLATE);
        $this->write('Page ' . $page . ' of ' . $total, 503, 810, 8, true, self::NAVY);
        $commands = $this->commands;
        $this->commands = $before;
        return $commands;
    }

    private function label(string $text, float $x, float $top): void
    {
        $this->write($text, $x, $top, 9, true, [0.62, 0.43, 0.035]);
    }

    private function rect(float $x, float $top, float $w, float $h, array $color): void
    {
        $this->commands .= $this->rgb($color) . sprintf(' rg %.2F %.2F %.2F %.2F re f', $x, self::HEIGHT - $top - $h, $w, $h) . "\n";
    }

    private function line(float $x1, float $top1, float $x2, float $top2, array $color, float $width): void
    {
        $this->commands .= $this->rgb($color) . sprintf(' RG %.2F w %.2F %.2F m %.2F %.2F l S', $width, $x1, self::HEIGHT - $top1, $x2, self::HEIGHT - $top2) . "\n";
    }

    private function write(string $text, float $x, float $top, float $fontSize, bool $bold, array $color): void
    {
        $text = $this->winAnsi($text);
        $text = str_replace(['\\', '(', ')', "\r", "\n"], ['\\\\', '\\(', '\\)', ' ', ' '], $text);
        $this->commands .= $this->rgb($color) . sprintf(' rg BT /%s %.2F Tf %.2F %.2F Td (%s) Tj ET', $bold ? 'F2' : 'F1', $fontSize, $x, self::HEIGHT - $top, $text) . "\n";
    }

    private function rgb(array $color): string
    {
        return sprintf('%.3F %.3F %.3F', $color[0], $color[1], $color[2]);
    }

    private function shorten(string $text, int $max): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            return mb_strlen($text) <= $max ? $text : mb_substr($text, 0, $max - 3) . '...';
        }
        return strlen($text) <= $max ? $text : substr($text, 0, $max - 3) . '...';
    }

    private function winAnsi(string $text): string
    {
        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        return $converted === false ? preg_replace('/[^\x20-\x7E]/', '?', $text) : $converted;
    }
}
