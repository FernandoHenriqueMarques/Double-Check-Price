<?php

namespace DigitalHub\DoubleCheckPrice\Service;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Model\Session as BackendSession;

class EmailTransport
{

     const TEMPLATE_ID = 'digitahub_doublecheckprice_email_template';

    public function __construct(
        protected TransportBuilder $transportBuilder,
        protected Emulation $emulation,
        protected StoreInterface $store,
        protected StoreManagerInterface $storeManager,
        protected BackendSession $backendSession
    ){}

    public function sent() {
        $transport = $this->transportBuilder->setTemplateIdentifier(self::TEMPLATE_ID)
            ->setTemplateOptions([
                'area' => 'frontend',
                'store' => $this->storeManager->getStore()->getId()]
            )->setTemplateVars([])
            ->setFromByScope([
                'name' => "loja teste",
                'email' => 'contato@lojateste.com'
            ])
            ->addTo('cliente@gmail.com', 'Nome Cliente')
            ->getTransport();

        return $transport->sendMessage();
    }
}
