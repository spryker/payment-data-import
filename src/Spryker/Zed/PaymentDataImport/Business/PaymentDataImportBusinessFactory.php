<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethod\Writer\Step\PaymentMethodWriterStep;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethod\Writer\Step\PaymentProviderWriterStep;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\Step\PaymentMethodKeyToIdPaymentMethodStep;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\Step\PaymentMethodStoreWriterStep;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\Step\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\PaymentDataImport\PaymentDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class PaymentDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getPaymentMethodDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getPaymentMethodDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createPaymentProviderWriterStep())
            ->addStep($this->createPaymentMethodWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getPaymentMethodStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getPaymentMethodStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createPaymentMethodKeyToIdPaymentMethodStep())
            ->addStep($this->createPaymentMethodStoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPaymentProviderWriterStep(): DataImportStepInterface
    {
        return new PaymentProviderWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPaymentMethodWriterStep(): DataImportStepInterface
    {
        return new PaymentMethodWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPaymentMethodKeyToIdPaymentMethodStep(): DataImportStepInterface
    {
        return new PaymentMethodKeyToIdPaymentMethodStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createPaymentMethodStoreWriterStep(): DataImportStepInterface
    {
        return new PaymentMethodStoreWriterStep();
    }
}
