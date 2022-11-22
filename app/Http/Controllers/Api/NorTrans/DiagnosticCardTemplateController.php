<?php

namespace App\Http\Controllers\Api\NorTrans;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticCardTemplate;

class DiagnosticCardTemplateController extends Controller
{
    public function index()
    {
        $templates = DiagnosticCardTemplate::whereStatus('active')->get();

        return $this->responseMobile('success', '', ['templates' => $templates]);
    }

    public function show($diagnosticCardTemplateId)
    {
        if ($template = DiagnosticCardTemplate::find($diagnosticCardTemplateId)) {
            $template->load(['items.items']);

            return $this->responseMobile('success','',['template' => $template]);
        } else {
            return $this->responseMobile('error', 'Нет такого шаблона');
        }
    }

}
