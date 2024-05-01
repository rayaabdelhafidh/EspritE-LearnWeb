<?php

namespace App\Service;

use BaconQrCode\Encoder\QrCode;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
class QrCodeGenerator
{
    private $kernelProjectDir;

    public function __construct(string $kernelProjectDir)
    {
        $this->kernelProjectDir = $kernelProjectDir;
    }

    public function generateQrCodeForPdf(string $pdfContent): string
    {
        // Encode the PDF content into the QR code
        $qrCode = QrCode::encode($pdfContent);
    
        // Create QR code writer
        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd(),
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400)
        );
        $writer = new Writer($renderer);
    
        // Generate QR code image file path
        $qrCodeImagePath = $this->kernelProjectDir . '/public/qrcodes/qrcode_' . uniqid() . '.svg';
    
        // Write the QR code to the image file
        $writer->writeFile($qrCode, $qrCodeImagePath);
    
        return $qrCodeImagePath;
    }
    

}
