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
 * @package   Dhl\Shipping\Model
 * @author    Christoph Aßmann <christoph.assmann@netresearch.de>
 * @copyright 2018 Netresearch GmbH & Co. KG
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.netresearch.de/
 */
namespace Dhl\Shipping\Model\Service;

use Dhl\Shipping\Api\Data\ServiceInterface;
use Dhl\Shipping\Api\Data\ShippingInfoInterface;
use Dhl\Shipping\Service\Filter\SelectedFilter;

/**
 * Load services for label request
 *
 * @package  Dhl\Shipping\Model
 * @author   Christoph Aßmann <christoph.assmann@netresearch.de>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.netresearch.de/
 */
class LabelServiceProvider
{
    /**
     * @var ServicePool
     */
    private $servicePool;

    /**
     * CheckoutServiceProvider constructor.
     * @param ServicePool $servicePool
     */
    public function __construct(ServicePool $servicePool)
    {
        $this->servicePool = $servicePool;
    }

    /**
     * @param ShippingInfoInterface $shippingInfo
     * @return ServiceCollection|ServiceInterface[]
     */
    public function getServices(ShippingInfoInterface $shippingInfo)
    {
        // todo(nr): load defaults from shipping info data structure
        $presets = [];
        $serviceCollection = $this->servicePool->getServices($presets);

        // return only services selected by customer or merchant
        $filter = SelectedFilter::create();
        $serviceCollection = $serviceCollection->filter($filter);

        return $serviceCollection;
    }
}