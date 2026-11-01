<?php

namespace app\components;

use Yii;
use app\models\CmsPage;
use app\models\SiteSettings;

/**
 * Interior cost estimation engine.
 * The same formula is mirrored client-side (web/js/iconic.js) for live preview,
 * but the server-side calculation is authoritative and is what gets stored.
 */
class Estimator
{
    public const TIERS = ['Essential', 'Luxury Gold', 'Bespoke Signature'];

    public static function propertyTypes(): array
    {
        return Yii::$app->params['propertyTypes'];
    }

    public static function breakdownShares(): array
    {
        $page = CmsPage::findBySlug('estimator');
        $rows = $page ? ($page->getExtra()['breakdown'] ?? []) : [];
        if (!$rows) {
            $rows = [
                ['label' => 'Modular Kitchen & Wardrobes', 'pct' => 34],
                ['label' => 'Living, Dining & Loose Furniture', 'pct' => 22],
                ['label' => 'False Ceiling & Lighting', 'pct' => 14],
                ['label' => 'Wall Finishes, Panelling & Paint', 'pct' => 12],
                ['label' => 'Flooring, Décor & Soft Furnishings', 'pct' => 10],
                ['label' => 'Design, Supervision & Project Mgmt.', 'pct' => 8],
            ];
        }
        return $rows;
    }

    /**
     * @return array{total:int,low:int,high:int,rate:int,factor:float,items:array,label:string}
     */
    public static function calculate(string $propertyType, int $area, string $tier): array
    {
        $types = self::propertyTypes();
        $factor = (float) ($types[$propertyType]['factor'] ?? 1.0);
        $rates = SiteSettings::get()->getEstimatorRates();
        $rate = (int) ($rates[$tier] ?? $rates['Luxury Gold']);

        $total = (int) round($area * $rate * $factor);
        $items = [];
        foreach (self::breakdownShares() as $row) {
            $items[] = [
                'label' => $row['label'],
                'pct' => (float) $row['pct'],
                'amount' => (int) round($total * ((float) $row['pct']) / 100),
            ];
        }
        $low = (int) round($total * 0.9);
        $high = (int) round($total * 1.12);

        return [
            'total' => $total,
            'low' => $low,
            'high' => $high,
            'rate' => $rate,
            'factor' => $factor,
            'items' => $items,
            'label' => self::inr($low, true) . ' – ' . self::inr($high, true),
        ];
    }

    /** Indian-format currency. Compact mode renders Lakh / Crore. */
    public static function inr(float|int $amount, bool $compact = false): string
    {
        if ($compact) {
            if ($amount >= 10000000) {
                return '₹' . rtrim(rtrim(number_format($amount / 10000000, 2), '0'), '.') . ' Cr';
            }
            if ($amount >= 100000) {
                return '₹' . rtrim(rtrim(number_format($amount / 100000, 2), '0'), '.') . ' L';
            }
        }
        $amount = (int) round($amount);
        $s = (string) abs($amount);
        if (strlen($s) > 3) {
            $last3 = substr($s, -3);
            $rest = substr($s, 0, -3);
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $s = $rest . ',' . $last3;
        }
        return '₹' . ($amount < 0 ? '-' : '') . $s;
    }
}
