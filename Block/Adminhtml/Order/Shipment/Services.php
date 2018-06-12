<?php
/**
 * Dhl Shipping
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to
 * newer versions in the future.
 *
 * PHP version 7
 *
 * @package   Dhl\Shipping\Block
 * @author    Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @copyright 2018 Netresearch GmbH & Co. KG
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.netresearch.de/
 */

namespace Dhl\Shipping\Block\Adminhtml\Order\Shipment;

use Dhl\Shipping\Api\Data\Service\ServiceInputInterface;
use Dhl\Shipping\Api\Data\ServiceInterface;
use Dhl\Shipping\Model\ResourceModel\ServiceSelectionRepository;
use Dhl\Shipping\Model\Service\PackagingServiceProvider;
use Magento\Shipping\Block\Adminhtml\Order\Packaging;

/**
 * Services
 *
 * @package Dhl\Shipping\Block
 * @author  Sebastian Ertner <sebastian.ertner@netresearch.de>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link    http://www.netresearch.de/
 */
class Services extends Packaging
{
    /**
     * @var PackagingServiceProvider
     */
    private $serviceProvider;

    /**
     * @var ServiceSelectionRepository
     */
    private $serviceSelectionRepository;

    /**
     * Services constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Shipping\Model\Carrier\Source\GenericInterface $sourceSizeModel
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Shipping\Model\CarrierFactory $carrierFactory
     * @param PackagingServiceProvider $serviceProvider
     * @param ServiceSelectionRepository $serviceSelectionRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Shipping\Model\Carrier\Source\GenericInterface $sourceSizeModel,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        PackagingServiceProvider $serviceProvider,
        ServiceSelectionRepository $serviceSelectionRepository,
        array $data = []
    ) {
        $this->serviceProvider = $serviceProvider;
        $this->serviceSelectionRepository = $serviceSelectionRepository;

        parent::__construct($context, $jsonEncoder, $sourceSizeModel, $coreRegistry, $carrierFactory, $data);
    }

    /**
     * @return \Dhl\Shipping\Model\Service\ServiceCollection
     */
    public function getServices()
    {
        $shipment = $this->getShipment();

        return $this->serviceProvider->getServices($shipment);
    }

    /**
     * @return ServiceSelectionCollectionInterface
     */
    public function getServiceSelection()
    {
        $shipment = $this->getShipment();

        return $this->serviceSelectionRepository->getByOrderAddressId($shipment->getShippingAddressId());
    }

    /**
     * @param ServiceInterface $service
     * @return string
     */
    public function getServiceHtml($service): string
    {
        $html = '';
        /** @var ServiceInputInterface $input */
        foreach ($service->getInputs() as $input) {
            // set block template according to service input type
            $template = sprintf(
                'Dhl_Shipping::order/packaging/popup_service_%s.phtml',
                $input->getInputType()
            );

            /** @var \Magento\Backend\Block\Template $serviceBlock */
            $serviceBlock = $this->getChildBlock('dhl_shipment_packaging_service');
            $serviceBlock->setTemplate($template);
            $serviceBlock->setData('service', $service);
            $serviceBlock->setData('input', $input);
            $html .= $serviceBlock->toHtml();
        }
        return $html;
    }
}
