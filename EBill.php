<?php
class EBill
{
    public string $accountNumber;
    public int $lastMeterReading;
    public int $previousMeterReading;
    public string $lastMeterReadingDate;
    public string $previousMeterReadingDate;
    public float $totalFirstRange = 0.0;
    public float $totalSecondRange = 0.0;
    public float $totalThirdRange = 0.0;

    private const FIXED_CHARGES_FIRST = 500.0;
    private const FIXED_CHARGES_SECOND = 1000.0;
    private const FIXED_CHARGES_THIRD = 1500.0;
    private const PRICE_FIRST_RANGE = 20.0;
    private const PRICE_SECOND_RANGE = 35.0;
    private const PRICE_THIRD_RANGE = 40.0;

    public $units;

    public function __construct(
        string $accountNumber, 
        int $lastMeterReading, 
        string $lastMeterReadingDate = '', 
        int $previousMeterReading, 
        string $previousMeterReadingDate = '')
    {
        $this->accountNumber = $accountNumber;
        $this->lastMeterReading = $lastMeterReading;
        $this->lastMeterReadingDate = $lastMeterReadingDate;
        $this->previousMeterReading = $previousMeterReading;
        $this->previousMeterReadingDate = $previousMeterReadingDate;
        $this->units = $lastMeterReading - $previousMeterReading;
        $this->calculateBill();
    }

    function getTotalPriceForUnits()
    {
        return $this->totalFirstRange + $this->totalSecondRange + $this->totalThirdRange;
    }

    function getTotalPriceForMonth()
    {
        return $this->getTotalPriceForUnits() + $this->getFixedCharges();
    }

    function getFixedCharges()
    {
        if ($this->units < 25) {
            return self::FIXED_CHARGES_FIRST;
        } elseif (25 < $this->units && $this->units <= 75) {
            return self::FIXED_CHARGES_SECOND;
        } elseif ($this->units > 75) {
            return self::FIXED_CHARGES_THIRD;
        }
    }

    private function calculateBill()
    {
        $units = $this->units;
        $priceThirdRangeTmp = self::PRICE_THIRD_RANGE;

        while ($units > 0) {

            if ($units > 25) {
                // units <= 25
                $this->totalFirstRange = 25 * self::PRICE_FIRST_RANGE;
                $units -= 25;

                if ($units > 75) {
                    // units > 75, units <= 75
                    $this->totalSecondRange = 75 * self::PRICE_SECOND_RANGE;
                    $units -= 75;

                    // units > 75 (for all)
                    for ($i = 1; $i <= $units; $i++) {
                        $this->totalThirdRange += $priceThirdRangeTmp;
                        $priceThirdRangeTmp++;
                    }
                    break;
                } else {
                    $this->totalSecondRange = $units * self::PRICE_SECOND_RANGE;
                    break;
                }
            } else {
                $this->totalFirstRange = $units * self::PRICE_FIRST_RANGE;
                break;
            }
        }
    }
}


?>