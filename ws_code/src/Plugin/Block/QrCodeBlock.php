<?php

namespace Drupal\ws_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\ws_code\Controller\QrCodeController;
use Drupal\Core\Database\Database;

//TODO
//Pass a parameter to make the QR code dynamic--we don't need it. Wasted time.--done
//TODO 
//Get the Node ID & APP URL here --done
//TODO
//Prepare this module--Block content--done/CT Config/Block Config/Image Style/Comments/Download URL of this module
/**
 * Provides a block 
 *
 * @Block(
 *   id = "qrcode1_block",
 *   admin_label = @Translation("QR Code block 1"),
 * )
 */

class QrCodeBlock extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $data = $this->getNodeData();
        $qrCode = new QrCodeController($data);

        $fileName = $qrCode->generateQrCode($qrCode->qrData);
        return [
            '#markup' => '<img src="/sites/default/files/' . $fileName . '">'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheMaxAge()
    {
        return 0;
    }

    protected function getNodeData()
    {
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node instanceof \Drupal\node\NodeInterface) {
            $nid = $node->id();
        }
        $query = Database::getConnection()
            ->select('node__field_app_purchase_link', 'd')
            ->fields('d', ['field_app_purchase_link_uri'])
            ->condition('entity_id', $nid, '=');
        $results = $query->execute()->fetchAll();
        return $results[0]->field_app_purchase_link_uri;
    }
}
