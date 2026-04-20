<?php

namespace App\Services\V1\Endorsement;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class EndorsementService
{
    protected $request;
    protected $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    protected $randomPart;
    protected $year;
    protected $secureCode;
    protected $randomNumber;

    public function generateUniqueSecureCodeForEntity($request)
    {
        $this->request = $request;
        $this->year = date('y');

        $entity = preg_replace('/[&_\(\)\-]/', '', $request?->entity?->name); // Remove specified characters
        $entity = explode(' ', $entity);

        $firstChars = array();

        foreach ($entity as $word) {
            $firstChars[] = substr($word, 0, 1);
        }

        while (count($firstChars) < 4) {
            $firstChars[] = 'A';
        }

        $firstCharsString = implode('', $firstChars);
        $firstCharsString = strtoupper($firstCharsString);

        while (strlen($this->randomPart) < (20 - strlen($firstCharsString))) {
            $this->randomPart .= $this->characters[rand(0, strlen($this->characters) - 1)];
        }

        // Combine the year, entity code, and random part
        $this->secureCode = $this->year . $firstCharsString . $this->randomPart;
        $this->randomNumber = rand(11111111111,99999999999);

        return $this;
    }

    public function generateEndorsement()
    {
        $data = [
            'entityEn' => $this->request?->entity?->name ?? NULL,
            'entityAr' => $this->request?->entity?->nameAr ?? NULL,
            'currentDate' => now()->format('d/m/Y') ?? NULL,
            'applicantNameEn' => $this->request?->user?->name ?? NULL,
            'applicantNameAr' => $this->request?->user?->nameArabic ?? NULL,
            'passportNumber' => $this->request?->personalInfo?->passportDetails?->number ?? NULL,
            'nationalityEn' => $this->request?->personalInfo?->applicantInfo?->nationality ?? NULL,
            'nationalityAr' => $this->request?->personalInfo?->applicantInfo?->nationality ?? NULL,
            'sectorEn' => $this->request?->sector?->name ?? NULL,
            'sectorAr' => $this->request?->sector?->nameAr ?? NULL,
            'secureCode' => $this->secureCode ?? NULL,
            'address' => $this->request?->personalInfo?->contactInfo?->permanentAddress ?? NULL,
            'qid' => $this->request?->personalInfo?->applicantInfo?->qidNumber ?? NULL,
        ];

        $pdf = SnappyPdf::loadView('documents.endorsement-letter', compact('data'));

        // Generate a unique file name for the PDF
        $fileName = $this->request->reqReferenceNumber . '-letter.pdf';
        $path = 'requests/endorsementLetters';

        // Generate PDF as string (raw binary)
        $pdfContent = $pdf->output();

        $storagePath = "{$fileName}.enc";
        $encryptedContent = Crypt::encrypt($pdfContent);
        Storage::disk('public')->put($path.'/'.$storagePath, $encryptedContent);
        $expiryDate = now()->addMonths(6);

        $requestData = [
            'reqId'=>$this->request?->id,
            'key'=>$this->randomNumber,
            'secureCode'=>$this->secureCode,
            'expiryDate'=>$expiryDate,
            'documentName'=>$storagePath,
        ];
        return $requestData;
    }
}
