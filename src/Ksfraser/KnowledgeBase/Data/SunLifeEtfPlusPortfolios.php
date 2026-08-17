<?php

declare(strict_types=1);

namespace Ksfraser\KnowledgeBase\Data;

/**
 * Canonical reference dataset for the Sun Life ETF+ Portfolios fund family.
 *
 * Source documents:
 *  - "Sun Life ETF+ Portfolios (English)" fund facts
 *  - "Advisor Guide - Sun Life ETF+ Portfolios (English)"
 *  - SLGI announcement email (Clive Rozdeba / Nima Mehrvarz, 2025-12-08):
 *    the Sun Life Tactical ETF Portfolios were renamed the Sun Life ETF+ Portfolios,
 *    adopted exposure to physical commodities (gold + private fixed income), and
 *    their management fees were reduced.
 *
 * This is the single source of truth consumed by the KB article, the FA/WP modules,
 * and the stockmarket app seg-fund seed. Keep it in sync with
 * ~/.hermes/skills/ksf_stockmarket/references/sun_life_etf_plus.json.
 */
final class SunLifeEtfPlusPortfolios
{
    public const FUND_PREFIX = 'SUN';
    public const MANAGER = 'SLGI Asset Management Inc.';
    public const BRAND = 'Sun Life Global Investments';
    public const SEG_FUND_ISSUER = 'Sun Life Assurance Company of Canada';
    public const PORTFOLIO_MANAGER = 'Anthony Wu, CFA';
    public const EFFECTIVE_DATE = '2025-12-08';
    public const PREDECESSOR_NAME = 'Sun Life Tactical ETF Portfolios';

    /** @return array<string,string|array<string>> */
    public static function meta(): array
    {
        return [
            'fund_family' => 'Sun Life ETF+ Portfolios',
            'brand' => self::BRAND,
            'manager' => self::MANAGER,
            'seg_fund_issuer' => self::SEG_FUND_ISSUER,
            'fund_code_prefix' => self::FUND_PREFIX,
            'portfolio_manager' => self::PORTFOLIO_MANAGER,
            'effective_date' => self::EFFECTIVE_DATE,
            'predecessor' => self::PREDECESSOR_NAME,
            'available_as' => ['Mutual Fund', 'Segregated Fund'],
            'etf_providers' => ['Vanguard', 'iShares', 'State Street'],
            'private_fixed_income_vehicle' => 'SLC Management Private Fixed Income Plus Fund',
            'gold_exposure' => 'Underlying ETFs that seek to replicate the performance of the price of gold bullion',
            'us_sector_rotation' => 'Proprietary systematic U.S. sector rotation strategy (U.S. equity sleeve)',
        ];
    }

    /**
     * @return array<int,array{key:string,name:string,category:string,risk_profile:string,focus:string,distribution:string,currency_hedging:string,asset_mix:array<string,?float>,asset_mix_summary:array<string,float>,fees:array<string,?float>,fund_codes:array<string,array{ISC:?string,NL:?string}>,trailing_commission_isc_pct:?float,sales_commission_fe:string,predecessor_tactical_etf_series_f_returns:array<string,float>}>
     */
    public static function portfolios(): array
    {
        return [
            [
                'key' => 'fixed_income',
                'name' => 'Sun Life Fixed Income ETF+ Portfolio',
                'category' => 'Global Core Plus Fixed Income',
                'risk_profile' => 'Low',
                'focus' => 'Diversified income focused on capital preservation',
                'distribution' => 'Monthly fixed',
                'currency_hedging' => 'Fixed income exposure to U.S. dollars is strategically hedged',
                'asset_mix' => ['canadian_equity' => null, 'us_equity' => null, 'us_sector_rotation_fund' => null, 'international_equity' => null, 'emerging_market_equity' => null, 'canadian_bonds' => 60.0, 'us_bonds' => 28.0, 'private_fixed_income' => 10.0, 'gold' => 2.0],
                'asset_mix_summary' => ['fixed_income' => 98.0, 'equity' => 0.0, 'alternatives' => 2.0],
                'fees' => ['series_a' => 0.875, 'series_f' => 0.375, 'series_f5' => null, 'series_t5' => null],
                'fund_codes' => ['A' => ['ISC' => '2100', 'NL' => null], 'F' => ['ISC' => null, 'NL' => '2400'], 'F5' => ['ISC' => null, 'NL' => null], 'T5' => ['ISC' => null, 'NL' => null]],
                'trailing_commission_isc_pct' => 0.50,
                'sales_commission_fe' => 'Up to 5%',
                'predecessor_tactical_etf_series_f_returns' => ['ytd' => 4.01, '1yr' => 2.71, '3yr' => 3.18],
            ],
            [
                'key' => 'conservative',
                'name' => 'Sun Life Conservative ETF+ Portfolio',
                'category' => 'Global Fixed Income Balanced',
                'risk_profile' => 'Low to medium',
                'focus' => 'Balanced with a higher allocation to fixed income',
                'distribution' => 'Annual',
                'currency_hedging' => 'Fixed income exposure to U.S. dollars is strategically hedged',
                'asset_mix' => ['canadian_equity' => 8.75, 'us_equity' => 11.5, 'us_sector_rotation_fund' => 6.0, 'international_equity' => 7.0, 'emerging_market_equity' => 1.75, 'canadian_bonds' => 37.0, 'us_bonds' => 18.0, 'private_fixed_income' => 8.0, 'gold' => 2.0],
                'asset_mix_summary' => ['fixed_income' => 63.0, 'equity' => 35.0, 'alternatives' => 2.0],
                'fees' => ['series_a' => 1.125, 'series_f' => 0.375, 'series_f5' => 0.375, 'series_t5' => 1.125],
                'fund_codes' => ['A' => ['ISC' => '6100', 'NL' => null], 'F' => ['ISC' => null, 'NL' => '6400'], 'F5' => ['ISC' => null, 'NL' => '6410'], 'T5' => ['ISC' => '6110', 'NL' => null]],
                'trailing_commission_isc_pct' => 0.75,
                'sales_commission_fe' => 'Up to 5%',
                'predecessor_tactical_etf_series_f_returns' => ['ytd' => 9.36, '1yr' => 7.61, '3yr' => 7.92],
            ],
            [
                'key' => 'balanced',
                'name' => 'Sun Life Balanced ETF+ Portfolio',
                'category' => 'Global Neutral Balanced',
                'risk_profile' => 'Low to medium',
                'focus' => 'Balanced with a tilt towards growth',
                'distribution' => 'Annual',
                'currency_hedging' => 'Fixed income exposure to U.S. dollars is strategically hedged',
                'asset_mix' => ['canadian_equity' => 15.0, 'us_equity' => 20.0, 'us_sector_rotation_fund' => 10.0, 'international_equity' => 12.0, 'emerging_market_equity' => 3.0, 'canadian_bonds' => 21.0, 'us_bonds' => 12.0, 'private_fixed_income' => 5.0, 'gold' => 2.0],
                'asset_mix_summary' => ['fixed_income' => 38.0, 'equity' => 60.0, 'alternatives' => 2.0],
                'fees' => ['series_a' => 1.400, 'series_f' => 0.400, 'series_f5' => 0.400, 'series_t5' => 1.400],
                'fund_codes' => ['A' => ['ISC' => '7100', 'NL' => null], 'F' => ['ISC' => null, 'NL' => '7400'], 'F5' => ['ISC' => null, 'NL' => '7410'], 'T5' => ['ISC' => '7110', 'NL' => null]],
                'trailing_commission_isc_pct' => 1.00,
                'sales_commission_fe' => 'Up to 5%',
                'predecessor_tactical_etf_series_f_returns' => ['ytd' => 13.82, '1yr' => 11.69, '3yr' => 11.52],
            ],
            [
                'key' => 'growth',
                'name' => 'Sun Life Growth ETF+ Portfolio',
                'category' => 'Global Equity Balanced',
                'risk_profile' => 'Low to medium',
                'focus' => 'Growth-oriented with fixed income and alternative components',
                'distribution' => 'Annual',
                'currency_hedging' => 'Fixed income exposure to U.S. dollars is strategically hedged',
                'asset_mix' => ['canadian_equity' => 20.0, 'us_equity' => 27.5, 'us_sector_rotation_fund' => 12.5, 'international_equity' => 16.0, 'emerging_market_equity' => 4.0, 'canadian_bonds' => 10.5, 'us_bonds' => 5.0, 'private_fixed_income' => 2.5, 'gold' => 2.0],
                'asset_mix_summary' => ['fixed_income' => 18.0, 'equity' => 80.0, 'alternatives' => 2.0],
                'fees' => ['series_a' => 1.450, 'series_f' => 0.450, 'series_f5' => null, 'series_t5' => null],
                'fund_codes' => ['A' => ['ISC' => '3100', 'NL' => null], 'F' => ['ISC' => null, 'NL' => '3400'], 'F5' => ['ISC' => null, 'NL' => null], 'T5' => ['ISC' => null, 'NL' => null]],
                'trailing_commission_isc_pct' => 1.00,
                'sales_commission_fe' => 'Up to 5%',
                'predecessor_tactical_etf_series_f_returns' => ['ytd' => 17.49, '1yr' => 15.07, '3yr' => 14.42],
            ],
            [
                'key' => 'equity',
                'name' => 'Sun Life Equity ETF+ Portfolio',
                'category' => 'Global Equity',
                'risk_profile' => 'Medium',
                'focus' => 'Equity and alternative components, focused on long-term growth',
                'distribution' => 'Annual',
                'currency_hedging' => 'n/a',
                'asset_mix' => ['canadian_equity' => 23.5, 'us_equity' => 36.0, 'us_sector_rotation_fund' => 15.0, 'international_equity' => 18.5, 'emerging_market_equity' => 5.0, 'canadian_bonds' => null, 'us_bonds' => null, 'private_fixed_income' => null, 'gold' => 2.0],
                'asset_mix_summary' => ['fixed_income' => 0.0, 'equity' => 98.0, 'alternatives' => 2.0],
                'fees' => ['series_a' => 1.450, 'series_f' => 0.450, 'series_f5' => null, 'series_t5' => null],
                'fund_codes' => ['A' => ['ISC' => '4100', 'NL' => null], 'F' => ['ISC' => null, 'NL' => '4400'], 'F5' => ['ISC' => null, 'NL' => null], 'T5' => ['ISC' => null, 'NL' => null]],
                'trailing_commission_isc_pct' => 1.00,
                'sales_commission_fe' => 'Up to 5%',
                'predecessor_tactical_etf_series_f_returns' => ['ytd' => 21.16, '1yr' => 18.41, '3yr' => 17.56],
            ],
        ];
    }

    /** @return string[] */
    public static function keys(): array
    {
        return array_map(static fn(array $p): string => $p['key'], self::portfolios());
    }

    /** @return array{key:string,name:string,category:string,risk_profile:string,focus:string,distribution:string,currency_hedging:string,asset_mix:array<string,?float>,asset_mix_summary:array<string,float>,fees:array<string,?float>,fund_codes:array<string,array{ISC:?string,NL:?string}>,trailing_commission_isc_pct:?float,sales_commission_fe:string,predecessor_tactical_etf_series_f_returns:array<string,float>} */
    public static function byKey(string $key): ?array
    {
        foreach (self::portfolios() as $p) {
            if ($p['key'] === $key) {
                return $p;
            }
        }
        return null;
    }
}
