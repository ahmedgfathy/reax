<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;

class AIDebugController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        return view('ai.debug');
    }

    public function analyze(Request $request)
    {
        $analysisType = $request->input('type');
        $result = '';

        switch($analysisType) {
            case 'code':
                $result = $this->aiService->analyzeCode($request->input('path'));
                break;
            case 'database':
                $result = $this->aiService->analyzeDatabase();
                break;
        }

        return response()->json(['analysis' => $result]);
    }
}
