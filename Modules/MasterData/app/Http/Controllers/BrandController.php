<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends CrudController
{
    /**
     * Initialize CRUD configuration
     */
    protected function initializeCrudConfig()
    {
        $this->setModel(Brand::class)
             ->setViewPath('masterdata::brands')
             ->setRoutePrefix('masterdata.brands')
             ->setRelationships([])
             ->setValidationRules([]);
    }

    /**
     * Get additional view data
     */
    protected function getAdditionalViewData()
    {
        return [];
    }

    /**
     * Get form fields configuration
     */
    public function getFormFields()
    {
        $data = $this->getAdditionalViewData();

        return [
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Enter brand name'
            ],
            [
                'name' => 'logo_url',
                'label' => 'Logo URL',
                'type' => 'url',
                'required' => false,
                'placeholder' => 'https://example.com/logo.png'
            ],
            [
                'name' => 'is_active',
                'label' => 'Active Status',
                'type' => 'checkbox',
                'required' => false,
                'default' => true,
                'help' => 'Check to activate this brand'
            ]
        ];
    }

    /**
     * Customize DataTables response
     */
    protected function customizeDataTablesResponse($datatables)
    {
        return $datatables;
    }
}
