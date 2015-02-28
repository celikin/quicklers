<?php


class CategorySeed {

    function run()
    {
        $category = new Category;
        $category->alias = "Products";
        $category->save();
    }
}
