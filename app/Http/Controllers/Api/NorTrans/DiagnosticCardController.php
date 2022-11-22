<?php

namespace App\Http\Controllers\Api\NorTrans;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NorTrans\DiagnosticCardItemRequest;
use App\Models\DiagnosticCard;
use App\Services\DiagnosticCard\DiagnosticCardService;
use Illuminate\Http\Request;

class DiagnosticCardController extends Controller
{

    public function saveTab(DiagnosticCardItemRequest $request, $diagnosticCard, DiagnosticCardService $cardService)
    {
        $diagnosticCard = DiagnosticCard::find($diagnosticCard);
        $result = $cardService->saveOnlyItems($request, $diagnosticCard);
        if ($result) {
            return $this->responseMobile('success');
        } else {
            return $this->responseMobile('error');
        }

    }

    public function save(Request $request, $diagnosticCard)
    {
        $diagnosticCard = DiagnosticCard::find($diagnosticCard);
        $diagnosticCard->notes = $request->has('comment') ? $request->get('comment') : null;
        $diagnosticCard->save();

        return $this->responseMobile('success', '', ['diagnostic_card' => $diagnosticCard->load('bus_variable')]);
    }
}
