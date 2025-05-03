<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;

class OcrController extends AbstractController
{
    #[Route('/ocr/extract-text', name: 'ocr_extract_text', methods: ['POST'])]

    public function extractText(Request $request): JsonResponse
    {
        // Check if file was uploaded
        if (!$request->files->has('image')) {
            return $this->json(['error' => 'No image uploaded'], 400);
        }

        $uploadedFile = $request->files->get('image');

        try {
            // Validate basic file properties
            if (!$uploadedFile->isValid()) {
                throw new \Exception('Uploaded file is not valid');
            }

            // Create temporary file path
            $tempFilePath = sys_get_temp_dir() . '/' . uniqid() . '.png';
            $uploadedFile->move(dirname($tempFilePath), basename($tempFilePath));

            // Run OCR process with timeout
            $process = new Process([
                'tesseract',
                $tempFilePath,
                'stdout',
                '-l', 'eng'  // English language
            ]);
            $process->setTimeout(30);
            $process->run();

            // Clean up temp file
            @unlink($tempFilePath);

            if (!$process->isSuccessful()) {
                throw new \Exception('OCR processing failed: ' . $process->getErrorOutput());
            }

            // Return cleaned text
            $text = trim($process->getOutput());
            return $this->json([
                'text' => $text,
                'message' => 'Text extracted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}