<?php

namespace Drupal\ws_code\Controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use Drupal\Core\Controller\ControllerBase;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeController extends ControllerBase
{
    public $qrData;

    public function __construct($qrData = " ")
    {
        $this->qrData = $qrData;
    }
    public function generateQrCode()
    {
        $writer = new PngWriter();

        // Create QR code
        $qrCode = QrCode::create($this->qrData)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);
        $fileName = time() . '.png';
        $result->saveToFile('sites/default/files/' . $fileName);
        return $fileName;
    }
}
