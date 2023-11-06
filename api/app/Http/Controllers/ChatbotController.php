<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;


class ChatbotController extends Controller
{
    public function askQuestion(Request $request)
    {
        //get api key from .env
        $client = $this->generateClient();

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $request->question],
            ],
        ]);

        $response->id; // 'chatcmpl-6pMyfj1HF4QXnfvjtfzvufZSQq6Eq'
        $response->object; // 'chat.completion'
        $response->created; // 1677701073
        $response->model; // 'gpt-3.5-turbo-0301'

        foreach ($response->choices as $result) {
            $result->index; // 0
            $result->message->role; // 'assistant'
            $result->message->content; // '\n\nHello there! How can I assist you today?'
            $result->finishReason; // 'stop'
        }

        $response->usage->promptTokens; // 9,
        $response->usage->completionTokens; // 12,
        $response->usage->totalTokens; // 21

        $response->toArray();
        //return $result; as json
        return response()->json($result);
    }

    public function trainRequestedData(Request $request)
    {
        //get api key from .env
        $client = $this->generateClient();

        $response = $client->files()->upload([
            'purpose' => 'fine-tune',
            'file' => fopen('gf_faq.jsonl', 'r'),
        ]);

        $response->id;
        $response->object;
        $response->bytes;
        $response->createdAt;
        $response->filename;
        $response->purpose;
        $response->status;
        $response->status_details;

        return response()->json($response);
    }

    public function listFiles()
    {
        $client = $this->generateClient();

        $response = $client->fineTunes()->create([
            'training_file' => 'RCIplJ1TctLv9CVYKV67ifae',
            'model' => 'curie',
            'n_epochs' => 4,
            'batch_size' => null,
            'learning_rate_multiplier' => null,
            'prompt_loss_weight' => 0.01,
            'compute_classification_metrics' => false,
            'classification_n_classes' => null,
            'classification_positive_class' => null,
            'classification_betas' => [],
            'suffix' => null,
        ]);
        
        $response->id; // "ft-AF1WoRqd3aJAHsqc9NY7iL8F"
        $response->object; // "fine-tune"
        // ...
        
        return response()->json($response);
    }

    public function saveRequestData(Request $request)
    {
        $data = $request->data;

        $file = fopen('request_data.jsonl', 'w');
        fwrite($file, $data);
        fclose($file);
    }

    public function generateClient()
    {
        $yourApiKey = env('GPT_KEY');

        $client = OpenAI::factory()
            ->withApiKey($yourApiKey)
            ->withOrganization('org-7PLUqhV7eSdhavAogorJddgP') // default: null
            ->withBaseUri('api.openai.com/v1') // default: api.openai.com/v1
            ->withHttpClient($client = new \GuzzleHttp\Client([])) // default: HTTP client found using PSR-18 HTTP Client Discovery
            ->make();
        return $client;
    }
}
