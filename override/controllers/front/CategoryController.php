<?php

class CategoryController extends CategoryControllerCore
{
    public function initContent()
    {
        parent::initContent();

        $subcategories = $this->category->getSubCategories($this->context->language->id);

        foreach ($subcategories as &$subcategory) {
            $subcategory['nb_products'] = (int)Product::getCategoryProductsCount($subcategory['id_category']);
        }

        $this->context->smarty->assign([
            'subcategories' => $subcategories,
        ]);
    }
    
    protected function getProductsCount($id_category)
    {
        return (int)Product::getCategoryProductsCount($id_category);
    }
    public function getProductList()
    {
        $originalProductList = parent::getProductList();

        $modifiedProducts = [];

        foreach ($originalProductList['products'] as $product) {
            // Load all combinations for this product
            $combinations = Product::getProductAttributesIds($product['id_product']);
            $combinationImages = Product::getCombinationImages((int)Context::getContext()->language->id);

            if ($combinations) {
                foreach ($combinations as $combination) {
                    $combinationId = $combination['id_product_attribute'];
                    $combData = new Combination($combinationId);
                    $productCopy = $product;

                    // Assign combination image
                    $imageId = null;
                    if (isset($combinationImages[$product['id_product']][$combinationId])) {
                        $imageId = $combinationImages[$product['id_product']][$combinationId][0]['id_image'];
                    }

                    if ($imageId) {
                        $productCopy['id_image'] = $imageId;
                        $productCopy['id_product_attribute'] = $combinationId;
                    }

                    // Optional: append color name to product name
                    $attributes = $combData->getAttributesName((int)Context::getContext()->language->id);
                    $attributeNames = [];
                    foreach ($attributes as $attr) {
                        $attributeNames[] = $attr['name'];
                    }
                    $productCopy['name'] .= ' - ' . implode(' ', $attributeNames);

                    $modifiedProducts[] = $productCopy;
                }
            } else {
                // No combinations: keep the original product
                $modifiedProducts[] = $product;
            }
        }

        // Replace original product list
        $originalProductList['products'] = $modifiedProducts;
        return $originalProductList;
    }
   }

