<?php

namespace App\Services\Gpt;

use Illuminate\Support\Facades\Log;

class ApiGpt
{

    private function prepare(string $question): string
    {
        $gptMessage = config('gpt.options');
        $gptMessage['messages'] = [[
                    "role" => "user",
                    "content" => $question
                    ]];

        return json_encode($gptMessage);
    }

    private function send(string $jsonMessage): array
    {
        $url = 'https://api.openai.com/v1/chat/completions';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('gpt.apiKey')
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonMessage);
        if (config('gpt.useProxy') === true) {
            curl_setopt($ch, CURLOPT_PROXY, config('gpt.proxy'));
        }
        $out = curl_exec($ch);
        $httpCode = curl_getinfo($ch,  CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        return [
            'code' => $httpCode,
            'message' => $out
        ];
    }

    public function run(string $question): string
    {
        $jsonMessage = $this->prepare($question);
        $gptAnswer = $this->send($jsonMessage);

        $result = json_decode($gptAnswer['message'], true);
        if ($gptAnswer['code'] != 200) {
            $error = $result['error']['message'] ?? null;
            if ($gptAnswer['code'] == 0) {
                $error = 'Не смогли получить ответ от сервера нейросети.';
            }

            Log::alert('Ошибка при запросе к ChatGPT', ['code' => $gptAnswer['code'], 'error' => $error]);
            throw new \Exception('Сервер ответил кодом: ' . $gptAnswer['code'] . "\n" . $error);
        } else {
            $answer = $result['choices'][0]['message']['content'] ?? null;
        }

        return $answer;
    }
}
