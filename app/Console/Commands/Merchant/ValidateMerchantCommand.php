<?php

namespace App\Console\Commands\Merchant;

use App\Domain\Merchant\GoogleFeedGenerator;
use App\Domain\Merchant\Validation\CatalogValidator;
use App\Domain\Merchant\Validation\Issue;
use App\Domain\Merchant\Validation\Severity;
use App\Domain\Merchant\Validation\ValidationReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ValidateMerchantCommand extends Command
{
    protected $signature = 'merchant:validate
        {--severity= : Only show issues of this severity (critical, important, warning)}
        {--group= : Only show one group (PRICE, IMAGE, IDENTIFIER…)}
        {--product= : Only show issues of one product id}
        {--summary : Print the counters only}';

    protected $description = 'Validate every product and the site setup against Google Merchant Center rules (exit 1 when CRITICAL issues exist)';

    private const GROUPS = [
        Issue::PRICE, Issue::AVAILABILITY, Issue::IMAGE, Issue::URL, Issue::IDENTIFIER, Issue::LANGUAGE,
        Issue::SHIPPING, Issue::CATEGORY, Issue::VARIANT, Issue::SCHEMA, Issue::XML,
        Issue::TITLE, Issue::DESCRIPTION, Issue::POLICY, Issue::BUSINESS, Issue::CHECKOUT, Issue::CONSISTENCY,
    ];

    public function handle(CatalogValidator $validator, GoogleFeedGenerator $generator): int
    {
        $report = $validator->run();

        try {
            $generator->rss();
        } catch (\Throwable $e) {
            $report->add(new Issue(Severity::Critical, Issue::XML, 'feed', $e->getMessage(), 'XML bien formado',
                'El flux no se puede generar como XML válido.', 'Corregir el carácter/dato señalado.'));
        }

        $this->printSummary($report);

        if (! $this->option('summary')) {
            $this->printIssues($report);
            $this->printBlocked($report);
        }

        $path = storage_path('app/merchant/validation-report.json');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($report->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->line('Informe JSON: '.$path);

        Log::channel('merchant')->info('merchant:validate', [
            'total' => $report->total(),
            'valid' => $report->validCount(),
            'blocked' => count($report->blocked()),
            'critical' => $report->countBySeverity(Severity::Critical),
            'important' => $report->countBySeverity(Severity::Important),
            'warnings' => $report->countBySeverity(Severity::Warning),
        ]);

        return $report->hasCritical() ? self::FAILURE : self::SUCCESS;
    }

    private function printSummary(ValidationReport $report): void
    {
        $byGroup = $report->countByGroup();

        $this->line('=====================================');
        $this->line('GOOGLE MERCHANT VALIDATION');
        $this->line('=====================================');
        $this->newLine();
        $this->line('TOTAL :           '.$report->total());
        $this->line('VALIDES :         '.$report->validCount());
        $this->line('BLOQUÉS :         '.count($report->blocked()));
        $this->line('AVERTISSEMENTS :  '.$report->countBySeverity(Severity::Warning));
        $this->newLine();
        $this->line('CRITICAL ERRORS   '.$report->countBySeverity(Severity::Critical));
        $this->line('IMPORTANT ERRORS  '.$report->countBySeverity(Severity::Important));
        $this->line('WARNINGS          '.$report->countBySeverity(Severity::Warning));
        $this->newLine();

        foreach (self::GROUPS as $group) {
            $this->line(str_pad($group.' ERRORS', 22).($byGroup[$group] ?? 0));
        }

        $this->newLine();
    }

    private function printIssues(ValidationReport $report): void
    {
        $severity = $this->option('severity') ? strtoupper((string) $this->option('severity')) : null;
        $group = $this->option('group') ? strtoupper((string) $this->option('group')) : null;
        $product = $this->option('product') !== null ? (int) $this->option('product') : null;

        foreach ($report->issues() as $issue) {
            if ($severity && $issue->severity->value !== $severity) {
                continue;
            }
            if ($group && $issue->group !== $group) {
                continue;
            }
            if ($product !== null && $issue->productId !== $product) {
                continue;
            }

            $style = match ($issue->severity) {
                Severity::Critical => 'error',
                Severity::Important => 'comment',
                Severity::Warning => 'info',
            };

            $this->line('<'.$style.'>['.$issue->severity->value.'] '.$issue->group.'</'.$style.'>');
            $this->line('  Product ID:        '.($issue->productId ?? '— (cuenta / sitio)'));
            $this->line('  SKU:               '.($issue->sku ?? '—'));
            $this->line('  Product name:      '.($issue->name ?? '—'));
            $this->line('  Field:             '.$issue->field);
            $this->line('  Current value:     '.$issue->current);
            $this->line('  Expected/required: '.$issue->expected);
            $this->line('  Severity:          '.$issue->severity->value);
            $this->line('  Reason:            '.$issue->reason);
            $this->line('  Correction:        '.$issue->fix);
            $this->newLine();
        }
    }

    private function printBlocked(ValidationReport $report): void
    {
        $blocked = $report->blocked();

        if ($blocked === []) {
            return;
        }

        $this->line('PRODUCTOS EXCLUIDOS DEL FLUX ('.count($blocked).')');

        foreach ($blocked as $id => $reasons) {
            $this->line(sprintf('  #%d %s — %s', $id, $report->productSku($id), $report->productName($id)));

            foreach (array_unique($reasons) as $reason) {
                $this->line('      · '.$reason);
            }
        }

        $this->newLine();
    }
}
