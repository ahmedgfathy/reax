<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class AIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function analyzeCode($path)
    {
        $code = File::get($path);
        $codeContext = "Analyze this code from a real estate CRM:\n\n{$code}\n\n";
        
        return $this->ask($codeContext . "Please identify:\n1. Missing validations\n2. Security concerns\n3. Business logic gaps\n4. Potential improvements");
    }

    public function analyzeDatabase()
    {
        $migrations = File::glob(database_path('migrations/*.php'));
        $schema = '';
        
        foreach ($migrations as $migration) {
            $schema .= File::get($migration) . "\n\n";
        }
        
        return $this->ask("Here are the database migrations:\n\n{$schema}\n\nAnalyze the schema and suggest:\n1. Missing relationships\n2. Important indexes\n3. Additional fields needed for real estate CRM");
    }

    protected function ask($prompt)
    {
        $response = Http::withToken($this->apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'You are a senior Laravel developer specializing in real estate CRM systems. Analyze code and provide detailed, actionable improvements.'
                    ],
                    [
                        'role' => 'user', 
                        'content' => $prompt
                    ],
                ],
                'temperature' => 0.7
            ]);

        return $response->json()['choices'][0]['message']['content'] ?? 'No response';
    }
}
