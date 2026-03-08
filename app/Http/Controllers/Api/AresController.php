<?php

namespace App\Http\Controllers\Api;

use Curl\Curl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AresController
{
    private const SEARCH_COUNT = 10;
    private const START = 0;

    public function search(Request $request): JsonResponse
    {
        $searchText = $request->get('search');

        if (is_numeric($searchText)) {
            return $this->loadByIc($searchText);
        } else {
            return $this->searchByName($searchText);
        }
    }

    public function searchByName(string $searchText): JsonResponse
    {
        $data = [
            'start' => self::START,
            'pocet' => self::SEARCH_COUNT,
            'razeni' => ['obchodniJmeno'],
            'obchodniJmeno' => $searchText,
        ];

        $curl = new Curl();
        $curl->setHeader('Content-Type', 'application/json');
        $resp = $curl->post('https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/vyhledat', json_encode($data));
        $responseData = json_decode($resp->response, true);

        return response()->json($responseData);
    }

    public function loadByIc(string $ic): JsonResponse
    {
        if (!$ic || strlen($ic) < 8) {
            return response()->json([
                'error' => 'Invalid IC number'
            ]);
        }

        $curl = new Curl();
        $resp = $curl->get('https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty/' . $ic);
        $responseData = json_decode($resp->response, true);

        if (isset($responseData['kod'])) {
            return response()->json([
                'error' => 'Invalid IC number or not found'
            ]);
        }

        return response()->json([
            'ic' => $responseData['ico'],
            'name' => $responseData['obchodniJmeno'],
            'addressStreet' => $responseData['sidlo']['nazevUlice'] . ' ' . $responseData['sidlo']['cisloDomovni'] . '/' . $responseData['sidlo']['cisloOrientacni'],
            'addressCity' => $responseData['sidlo']['nazevObce'],
            'addressZipcode' => $responseData['sidlo']['psc']
        ]);
    }
}
