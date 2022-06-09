<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Services\BlockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BookController extends Controller
{

    /**
     * Calculates the price and creates reservation
     *
     * @param BookRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function calculate(BookRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $blockService = new BlockService();

        //counting blocks
        $responseForBlocks = $blockService->getCountOfBlocksByWeight($requestData['weight'], $requestData['temperature']);

        if ($responseForBlocks['status'] === false) {
            return response()->json([
                'status' => false,
                'message' => $responseForBlocks['message']
            ]);
        }

        $blocksCount = $responseForBlocks['count'];

        //getting price by count of blocks
        $responseForPrice = $blockService->getPriceByBlocksAndCountry($blocksCount, $requestData['hours']);

        if ($responseForPrice['status'] === false) {
            return response()->json([
                'status' => false,
                'message' => $responseForPrice['message']
            ]);
        }

        $price = $responseForPrice['price'];

        $randomToken = Str::random(12);
        $id = random_int(1, 1000);

        return response()->json([
            'status' => true,
            'id' => $id,
            'price' => $price,
            'orderToken' => $randomToken,
        ]);
    }
}
