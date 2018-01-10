<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace H1\OroMultiSafepayBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class H1OroMultiSafepayBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createH1MultiSafepayShortLabelTable($schema);
        $this->createH1MultiSafepayTransLabelTable($schema);
        $this->updateOroIntegrationTransportTable($schema);

        /** Foreign keys generation **/
        $this->addH1MultiSafepayShortLabelForeignKeys($schema);
        $this->addH1MultiSafepayTransLabelForeignKeys($schema);
    }

    /**
     * Update oro_integration_transport table
     *
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    protected function updateOroIntegrationTransportTable(Schema $schema)
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('msp_test_mode', 'boolean', ['default' => '0', 'notnull' => false]);
        $table->addColumn('msp_api_key', 'string', ['default' => '', 'notnull' => false, 'length' => 255]);
        $table->addColumn('msp_gateway', 'string', ['default' => '', 'notnull' => false, 'length' => 255]);
        $table->addColumn('msp_issuers', 'array', ['notnull' => false, 'comment' => '(DC2Type:array)']);
        $table->addColumn('msp_all_issuers', 'json_array', ['notnull' => false, 'comment' => '(DC2Type:json_array)']);
    }

    /**
     * Create h1_multi_safepay_short_label table
     *
     * @param Schema $schema
     */
    protected function createH1MultiSafepayShortLabelTable(Schema $schema)
    {
        $table = $schema->createTable('h1_multi_safepay_short_label');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_8A1042A2EB576E89');
        $table->addIndex(['transport_id'], 'IDX_8A1042A29909C13F', []);
    }

    /**
     * Create h1_multi_safepay_trans_label table
     *
     * @param Schema $schema
     */
    protected function createH1MultiSafepayTransLabelTable(Schema $schema)
    {
        $table = $schema->createTable('h1_multi_safepay_trans_label');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_B5D68778EB576E89');
        $table->addIndex(['transport_id'], 'IDX_B5D687789909C13F', []);
    }

    /**
     * Add h1_multi_safepay_short_label foreign keys.
     *
     * @param Schema $schema
     */
    protected function addH1MultiSafepayShortLabelForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('h1_multi_safepay_short_label');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add h1_multi_safepay_trans_label foreign keys.
     *
     * @param Schema $schema
     */
    protected function addH1MultiSafepayTransLabelForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('h1_multi_safepay_trans_label');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
