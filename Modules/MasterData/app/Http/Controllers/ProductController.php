<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends CrudController
{
    /**
     * Initialize CRUD configuration
     */
    protected function initializeCrudConfig()
    {
        $this->setModel(Product::class)
             ->setViewPath('masterdata::products')
             ->setRoutePrefix('master-data.products')
             ->setRelationships(['brand', 'unit', 'category'])
             ->setValidationRules([
                 'name' => 'required|string|max:255',
                 'sku' => 'required|string|max:100|unique:products,sku',
                 'brand_id' => 'required|exists:brands,id',
                 'unit_id' => 'required|exists:units,id',
                 'product_category_id' => 'required|exists:product_categories,id',
                 'price' => 'required|numeric|min:0',
                 'cost' => 'required|numeric|min:0',
             ]);
    }

    /**
     * Get validation rules with unique check for updates
     */
    protected function getValidationRules($item = null)
    {
        $rules = $this->validationRules;

        if ($item) {
            $rules['sku'] = 'required|string|max:100|unique:products,sku,' . $item->id;
        }

        return $rules;
    }

    /**
     * Get additional view data (brands, units, categories)
     */
    protected function getAdditionalViewData()
    {
        return [
            'brands' => Brand::where('is_active', true)->get(),
            'units' => Unit::where('is_active', true)->get(),
            'categories' => ProductCategory::where('is_active', true)->get(),
        ];
    }

    /**
     * Customize DataTables response
     */
    protected function customizeDataTablesResponse($datatables)
    {
        return $datatables
            ->editColumn('price', function ($product) {
                return 'Rp ' . number_format($product->price ?? 0, 0, ',', '.');
            })
            ->addColumn('brand_name', function ($product) {
                return $product->brand ? $product->brand->name : '-';
            })
            ->addColumn('unit_name', function ($product) {
                return $product->unit ? $product->unit->name : '-';
            })
            ->addColumn('category_name', function ($product) {
                return $product->category ? $product->category->name : '-';
            });
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
                'label' => 'Product Name',
                'type' => 'text',
                'required' => true,
                'col_class' => 'col-md-6',
                'placeholder' => 'Enter product name'
            ],
            [
                'name' => 'sku',
                'label' => 'SKU',
                'type' => 'text',
                'required' => true,
                'col_class' => 'col-md-6',
                'placeholder' => 'Enter SKU'
            ],
            [
                'name' => 'brand_id',
                'label' => 'Brand',
                'type' => 'select',
                'required' => true,
                'col_class' => 'col-md-4',
                'options' => $data['brands']
            ],
            [
                'name' => 'unit_id',
                'label' => 'Unit',
                'type' => 'select',
                'required' => true,
                'col_class' => 'col-md-4',
                'options' => $data['units']
            ],
            [
                'name' => 'product_category_id',
                'label' => 'Category',
                'type' => 'select',
                'required' => true,
                'col_class' => 'col-md-4',
                'options' => $data['categories']
            ],
            [
                'name' => 'price',
                'label' => 'Price',
                'type' => 'number',
                'required' => true,
                'col_class' => 'col-md-6',
                'attributes' => ['step' => '0.01', 'min' => '0'],
                'placeholder' => 'Enter price'
            ],
            [
                'name' => 'cost',
                'label' => 'Cost',
                'type' => 'number',
                'required' => true,
                'col_class' => 'col-md-6',
                'attributes' => ['step' => '0.01', 'min' => '0'],
                'placeholder' => 'Enter cost'
            ],
            [
                'name' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => false,
                'col_class' => 'col-md-12',
                'rows' => 3,
                'placeholder' => 'Enter product description (optional)'
            ]
        ];
    }
}
