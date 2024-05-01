<?php
namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RevAiService
{
    private $client;
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.rev.ai/speechtotext/v1/',
            'timeout'  => 10,
        ]);
        $this->apiKey = $apiKey;
    }

    public function transcribeAudio(UploadedFile $audioFile): ?string
    {
        try {
            $response = $this->client->request('POST', 'jobs', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($audioFile->getPathname(), 'r'),
                        'filename' => $audioFile->getClientOriginalName(),
                    ],
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $jobData = json_decode($response->getBody()->getContents(), true);
                $jobId = $jobData['id'];

                // Wait for the transcription to complete (you may need to implement polling logic)
                sleep(10); // Example: wait for 10 seconds

                // Get transcription result
                $transcriptionResponse = $this->client->request('GET', "jobs/$jobId/transcript", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                    ],
                ]);

                if ($transcriptionResponse->getStatusCode() === 200) {
                    $transcriptionData = json_decode($transcriptionResponse->getBody()->getContents(), true);
                    return $transcriptionData['monologues'][0]['elements'][0]['value'] ?? null;
                }
            }
        } catch (GuzzleException $e) {
            // Handle Guzzle exceptions
            throw new \Exception('Error communicating with Rev.ai API: ' );
        }
        

        return null;
    }
    public function transcribePartialAudio(UploadedFile $audioFile): ?string
    {
        try {
            $response = $this->client->request('POST', 'jobs', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($audioFile->getPathname(), 'r'),
                        'filename' => $audioFile->getClientOriginalName(),
                    ],
                    [
                        'name' => 'metadata',
                        'contents' => json_encode(['partial_transcript' => true]),
                    ],
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $jobData = json_decode($response->getBody()->getContents(), true);
                $jobId = $jobData['id'];

                // Wait for the transcription to complete (you may need to implement polling logic)
                sleep(5); // Example: wait for 5 seconds

                // Get partial transcription result
                $transcriptionResponse = $this->client->request('GET', "jobs/$jobId/transcript", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                    ],
                ]);

                if ($transcriptionResponse->getStatusCode() === 200) {
                    $transcriptionData = json_decode($transcriptionResponse->getBody()->getContents(), true);
                    return $transcriptionData['monologues'][0]['elements'][0]['value'] ?? null;
                }
            }
        } catch (GuzzleException $e) {
            // Handle Guzzle exceptions
            throw new \Exception('Error communicating with Rev.ai API: ' );
        }
        
        return null;
    }

    public function transcribeFinalAudio(UploadedFile $audioFile): ?string
    {
        try {
            $response = $this->client->request('POST', 'jobs', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($audioFile->getPathname(), 'r'),
                        'filename' => $audioFile->getClientOriginalName(),
                    ],
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $jobData = json_decode($response->getBody()->getContents(), true);
                $jobId = $jobData['id'];

                // Wait for the transcription to complete (you may need to implement polling logic)
                sleep(10); // Example: wait for 10 seconds

                // Get final transcription result
                $transcriptionResponse = $this->client->request('GET', "jobs/$jobId/transcript", [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                    ],
                ]);

                if ($transcriptionResponse->getStatusCode() === 200) {
                    $transcriptionData = json_decode($transcriptionResponse->getBody()->getContents(), true);
                    return $transcriptionData['monologues'][0]['elements'][0]['value'] ?? null;
                }
            }
        } catch (GuzzleException $e) {
            // Handle Guzzle exceptions
            throw new \Exception('Error communicating with Rev.ai API: ' );
        }
        
        return null;
    }

}
