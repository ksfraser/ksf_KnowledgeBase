<?php

declare(strict_types=1);

namespace Ksfraser\KnowledgeBase\Tests\Unit\Data;

use Ksfraser\KnowledgeBase\Data\SunLifeEtfPlusPortfolios;
use PHPUnit\Framework\TestCase;

class SunLifeEtfPlusPortfoliosTest extends TestCase
{
    public function testExactlyFivePortfolios(): void
    {
        $this->assertCount(5, SunLifeEtfPlusPortfolios::portfolios());
        $this->assertSame(
            ['fixed_income', 'conservative', 'balanced', 'growth', 'equity'],
            SunLifeEtfPlusPortfolios::keys()
        );
    }

    public function testAssetMixSumsTo100Percent(): void
    {
        foreach (SunLifeEtfPlusPortfolios::portfolios() as $p) {
            $sum = 0.0;
            foreach ($p['asset_mix'] as $value) {
                $sum += (float) $value;
            }
            $this->assertEqualsWithDelta(
                100.0,
                $sum,
                0.001,
                "Asset mix for {$p['name']} must sum to 100% (got {$sum})"
            );
        }
    }

    public function testAssetMixSummarySumsTo100Percent(): void
    {
        foreach (SunLifeEtfPlusPortfolios::portfolios() as $p) {
            $sum = array_sum($p['asset_mix_summary']);
            $this->assertEqualsWithDelta(
                100.0,
                $sum,
                0.001,
                "Asset mix summary for {$p['name']} must sum to 100%"
            );
        }
    }

    public function testSeriesFFeesMatchAnnouncement(): void
    {
        $this->assertSame(0.375, SunLifeEtfPlusPortfolios::byKey('fixed_income')['fees']['series_f']);
        $this->assertSame(0.375, SunLifeEtfPlusPortfolios::byKey('conservative')['fees']['series_f']);
        $this->assertSame(0.400, SunLifeEtfPlusPortfolios::byKey('balanced')['fees']['series_f']);
        $this->assertSame(0.450, SunLifeEtfPlusPortfolios::byKey('growth')['fees']['series_f']);
        $this->assertSame(0.450, SunLifeEtfPlusPortfolios::byKey('equity')['fees']['series_f']);
    }

    public function testFundCodesUseSunPrefix(): void
    {
        $this->assertSame('SUN', SunLifeEtfPlusPortfolios::FUND_PREFIX);
        $balanced = SunLifeEtfPlusPortfolios::byKey('balanced');
        $this->assertSame('7100', $balanced['fund_codes']['A']['ISC']);
        $this->assertSame('7400', $balanced['fund_codes']['F']['NL']);
    }

    public function testPredecessorReturnsPresentAndLabeled(): void
    {
        foreach (SunLifeEtfPlusPortfolios::portfolios() as $p) {
            $this->assertArrayHasKey('predecessor_tactical_etf_series_f_returns', $p);
            $this->assertArrayHasKey('1yr', $p['predecessor_tactical_etf_series_f_returns']);
            $this->assertArrayHasKey('3yr', $p['predecessor_tactical_etf_series_f_returns']);
        }
    }

    public function testMetaCarrierAndManager(): void
    {
        $meta = SunLifeEtfPlusPortfolios::meta();
        $this->assertSame('SLGI Asset Management Inc.', $meta['manager']);
        $this->assertSame('Sun Life Assurance Company of Canada', $meta['seg_fund_issuer']);
        $this->assertSame('2025-12-08', $meta['effective_date']);
    }
}
