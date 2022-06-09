<?php

namespace App\Services;

final class BlockService
{
    protected int $volume = 2; //m^3
    protected int $maxCountForBlocks = 20; //max counts to show if there are no available blocks
    protected int $pricePerBlockAndHour = 20; //currency $
    protected array $rangeForTemperature = [-2, 2]; //max counts to show if there are no available blocks

    public function getCountOfBlocksByWeight($weight, int $temperature): array
    {

        if ($temperature < $this->rangeForTemperature[0] || $temperature > $this->rangeForTemperature[1]) {
            return [
                'status' => false,
                'message' => 'Temperature must be in range from -2 to 2'
            ];
        }

        $count = ceil($weight / $this->volume);

        if ($weight <= 0 || $count > $this->maxCountForBlocks) {
            return [
                'status' => false,
                'message' => 'there are not available blocks for this weight'
            ];
        }

        return [
            'status' => true,
            'count' => $count
        ];
    }

    public function getPriceByBlocksAndCountry(int $blocks, int $hours): array
    {
        if ($hours > 24 || $hours < 0) {
            return [
                'status' => false,
                'message' => 'Hours must be lower then a day (24 hours)'
            ];
        }

        $price = $hours * $blocks;

        return [
            'status' => true,
            'price' => $price
        ];
    }

}
