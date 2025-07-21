<?php

class Product extends ProductCore
{
    public static function getCategoryProductsCount($id_category)
    {
        $sql = new DbQuery();
        $sql->select('COUNT(p.id_product)');
        $sql->from('product', 'p');
        $sql->innerJoin('category_product', 'cp', 'cp.id_product = p.id_product');
        $sql->where('cp.id_category = '.(int)$id_category);
        $sql->where('p.active = 1');

        return (int)Db::getInstance()->getValue($sql);
    }
}
