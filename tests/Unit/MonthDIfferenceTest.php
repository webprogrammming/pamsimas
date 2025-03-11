<?php

namespace Tests\Unit;

use Tests\TestCase;
use DateTime;

class MonthDifferenceTest extends TestCase
{
    // Define the method we want to test within the test class
    private function calculateMonthDifference($date1, $date2)
    {
        if ($date1 instanceof DateTime) {
            $start = $date1;
        } else {
            $start = new DateTime($date1);
        }

        if ($date2 instanceof DateTime) {
            $end = $date2;
        } else {
            $end = new DateTime($date2);
        }

        $interval = $start->diff($end);
        $years = $interval->y;
        $months = $interval->m;

        if ($interval->d > 0) {
            $months += 1;
        }

        return $years * 12 + $months;
    }

    /** @test */
    // Mengecek bahwa antara dua tanggal masih berada did alam 1 bulan yang sama
    public function test_calculate_month_difference_same_month()
    {
        $date1 = '2024-01-01';
        $date2 = '2024-01-31';

        $difference = $this->calculateMonthDifference($date1, $date2);

        // Since it's within the same month, the difference should be 1
        $this->assertEquals(1, $difference);
    }

    /** @test */
    // Menguji selisih antara dua tanggal dengan rentang satu tahun penuh, hasilnya harus 12 bulan.
    public function test_calculate_month_difference_one_full_year()
    {
        $date1 = '2023-01-01';
        $date2 = '2024-01-01';

        $difference = $this->calculateMonthDifference($date1, $date2);

        // Full year should return 12 months
        $this->assertEquals(12, $difference);
    }

    /** @test */
    //  Menguji dua tanggal dengan selisih lebih dari satu tahun (dalam contoh 2 tahun dan 2 bulan), hasilnya harus 26 bulan.
    public function test_calculate_month_difference_more_than_year()
    {
        $date1 = '2022-05-15';
        $date2 = '2024-07-10';

        $difference = $this->calculateMonthDifference($date1, $date2);

        // The difference here is 2 years and 2 months
        $this->assertEquals(26, $difference); // 2 years * 12 + 2 months
    }

    /** @test */
    // Menguji apakah tambahan hari dalam selisih dua bulan dihitung sebagai bulan penuh jika ada sisa hari.
    public function test_calculate_month_difference_with_days_included()
    {
        $date1 = '2023-01-15';
        $date2 = '2023-03-05';

        $difference = $this->calculateMonthDifference($date1, $date2);

        // Although the difference is less than two full months,
        // since there are more than 0 days, it counts as 2 months
        $this->assertEquals(2, $difference);
    }
}
