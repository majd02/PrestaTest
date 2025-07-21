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
   
   }

