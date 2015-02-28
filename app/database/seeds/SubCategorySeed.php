<?php


class SubCategorySeed {

    function run()
    {
        $subcategory = new SubCategory;
        $subcategory->alias = "Pivo";
        $subcategory->category_id = "1";
        $subcategory->save();
    }
}
