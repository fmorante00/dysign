<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Dependency-free, read-only A4 PDF for department-scoped participation data.
 * Uses only PDF core fonts and does not persist reports or change attendance.
 */
final class StaffParticipationPdf
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

    public static function render(string $department, Collection $students, int $completedEvents, array $filters): string
    {
        $report = new self();
        $report->cover($department, $students, $completedEvents, $filters);
        $report->records($students);
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

    private function cover(string $department, Collection $students, int $completedEvents, array $filters): void
    {
        $this->nextPage();
        $this->rect(0, 0, self::WIDTH, 126, self::NAVY);
        $this->rect(0, 123, self::WIDTH, 4, self::GOLD);
        $this->write('DYSIGN  /  DEPARTMENT STAFF PORTAL', 40, 39, 10, true, self::GOLD);
        $this->write('Participation Progress Report', 40, 75, 20, true, [1, 1, 1]);
        $this->write('Student participation across completed department events', 40, 99, 9, false, [0.87, 0.9, 0.98]);

        $this->label('DEPARTMENT AND REPORT DETAILS', 40, 159);
        $this->write($this->shorten($department, 65), 40, 182, 15, true, self::NAVY);
        $this->write('Generated: ' . now('Asia/Manila')->format('M d, Y  g:i A') . ' (PH)', 40, 203, 9, false, self::SLATE);
        $this->write('Scope: active, eligible students  /  completed department events', 40, 220, 9, false, self::SLATE);
        $this->line(40, 238, 555, 238, self::BORDER, 1);

        $count = $students->count();
        $average = $count > 0 ? round((float) $students->avg('progress_percentage'), 1) : 0;
        $attended = (int) $students->sum('attended_count');
        $metrics = [
            ['MATCHING STUDENTS', (string) $count],
            ['COMPLETED EVENTS', (string) $completedEvents],
            ['TOTAL ATTENDANCES', (string) $attended],
            ['AVERAGE RATE', number_format($average, 1) . '%'],
        ];
        $this->label('PARTICIPATION SUMMARY', 40, 264);
        foreach ($metrics as $i => [$label, $value]) {
            $x = 40 + $i * 130;
            $this->rect($x, 280, 123, 77, self::LIGHT);
            $this->write($label, $x + 9, 303, 7.4, true, self::SLATE);
            $this->write($value, $x + 9, 338, 18, true, $i === 3 ? [0.65, 0.45, 0.03] : self::NAVY);
        }
        $this->write('Average of each matched student\'s attended / eligible rate.', 40, 379, 9, false, self::SLATE);
        $this->rect(40, 394, 515, 12, [0.91, 0.93, 0.96]);
        $this->rect(40, 394, 515 * min(100, max(0, $average)) / 100, 12, self::GOLD);

        $high = $students->filter(fn ($s) => (float) $s->progress_percentage >= 80)->count();
        $moderate = $students->filter(fn ($s) => (float) $s->progress_percentage >= 50 && (float) $s->progress_percentage < 80)->count();
        $low = $students->filter(fn ($s) => (float) $s->progress_percentage < 50)->count();
        $this->label('PROGRESS DISTRIBUTION', 40, 446);
        foreach ([['HIGH  /  80%+', $high], ['MODERATE  /  50-79.9%', $moderate], ['LOW  /  BELOW 50%', $low]] as $i => [$label, $value]) {
            $x = 40 + $i * 175;
            $this->rect($x, 464, 165, 70, self::LIGHT);
            $this->write($label, $x + 11, 486, 8, true, self::SLATE);
            $this->write((string) $value, $x + 11, 515, 18, true, self::NAVY);
        }

        $this->label('APPLIED FILTERS', 40, 570);
        $filtersText = 'Search: ' . ($filters['search'] !== '' ? $filters['search'] : 'All') .
            '  |  Program: ' . ($filters['program'] !== '' ? $filters['program'] : 'All') .
            '  |  Year: ' . ($filters['year'] ?? 'All');
        $this->write($this->shorten($filtersText, 93), 40, 590, 9, false, self::SLATE);
        $this->write('Progress: ' . ($filters['progress'] !== '' ? ucfirst($filters['progress']) : 'All') .
            '  |  Sort: ' . str_replace('_', ' ', $filters['sort']), 40, 610, 9, false, self::SLATE);

        $this->label('REPORT NOTES', 40, 657);
        $this->write('Attendance counted: Present or Late in completed eligible events.', 40, 680, 9, false, self::SLATE);
        $this->write('Student details and individual progress records begin on the next page.', 40, 699, 9, false, self::SLATE);
        $this->write('Activity points are intentionally excluded from this report.', 40, 718, 9, false, self::SLATE);
        $this->write('System-generated monitoring report, not a signed certification.', 40, 737, 8, false, self::SLATE);
    }

    private function records(Collection $students): void
    {
        $this->nextPage();
        $this->tableHeading('STUDENT PARTICIPATION RECORDS', 'All matching records across every page of the on-screen table');
        $y = 176;
        $n = 0;
        foreach ($students as $student) {
            if ($y + 45 > 760) {
                $this->nextPage();
                $this->tableHeading('PARTICIPATION RECORDS (CONTINUED)', 'Complete department report - continued');
                $y = 176;
            }
            $this->rect(40, $y, 515, 44, $n % 2 === 0 ? [1, 1, 1] : self::LIGHT);
            $name = trim((string) $student->last_name . ', ' . (string) $student->first_name);
            $rate = (float) $student->progress_percentage;
            $level = $rate >= 80 ? 'High' : ($rate >= 50 ? 'Moderate' : 'Low');
            $this->write($this->shorten($name, 29), 48, $y + 18, 9, true, self::NAVY);
            $this->write($this->shorten((string) $student->student_number, 26), 48, $y + 33, 7, false, self::SLATE);
            $program = (string) ($student->program_code ?: 'N/A') . ' / Y' . (string) ($student->year_level ?: '-');
            $this->write($this->shorten($program, 15), 227, $y + 26, 8, false, self::SLATE);
            $this->write((string) (int) $student->eligible_count, 344, $y + 26, 9, false, self::SLATE);
            $this->write((string) (int) $student->attended_count, 389, $y + 26, 9, false, self::SLATE);
            $this->write(number_format($rate, 1) . '%', 435, $y + 26, 9, true, self::NAVY);
            $this->write($level, 502, $y + 26, 8, true, self::NAVY);
            $this->line(40, $y + 44, 555, $y + 44, self::BORDER, .6);
            $y += 46;
            $n++;
        }
        if ($students->isEmpty()) {
            $this->write('No eligible student participation records match these filters.', 49, 207, 10, false, self::SLATE);
        }
    }

    private function tableHeading(string $title, string $subtitle): void
    {
        $this->rect(0, 0, self::WIDTH, 96, self::NAVY);
        $this->rect(0, 93, self::WIDTH, 4, self::GOLD);
        $this->write('DYSIGN  /  DEPARTMENT PARTICIPATION REPORT', 40, 35, 9, true, self::GOLD);
        $this->write($title, 40, 68, 15, true, [1, 1, 1]);
        $this->write($subtitle, 40, 121, 9, false, self::SLATE);
        $this->rect(40, 139, 515, 34, self::LIGHT);
        foreach ([['STUDENT', 48], ['PROGRAM', 227], ['ELIG.', 331], ['ATT.', 382], ['RATE', 430], ['LEVEL', 498]] as [$label, $x]) {
            $this->write($label, $x, 161, 8, true, self::NAVY);
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
        $this->write('DySign - Department participation report  /  Authorized staff use only', 40, 808, 8, false, self::SLATE);
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
