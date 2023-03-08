<?php

namespace DigitalHub\DoubleCheckPrice\Observer;

use DigitalHub\DoubleCheckPrice\Service\EmailTransport;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Backend\Model\Auth\Session as AuthSession;

class BeforeProductSave implements ObserverInterface
{
    public function __construct(
        protected EmailTransport $emailTransport,
        protected RequestInterface $request,
        protected AuthSession $session
    ) {
    }

    public function execute(Observer $observer)
    {

        $controller = $observer->getController();
        $product = $observer->getProduct();

        $request = $controller->getRequest();

        $origPrice = $product->getOrigData('price');
        $productRequest = $request->getPostValue('product');

        $newPrice = $productRequest['price'] ?? null;

        if ((float)$origPrice !== (float)$newPrice) {
            $product->setPrice($origPrice)->save();
            $this->emailTransport->sent();
        }
    }
}
