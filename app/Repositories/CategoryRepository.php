<?php
namespace App\Repositories;
use App\Models\Category\Category;
use DB;


class CategoryRepository
{
    public function getAllCategories()
    {
//        return DB::table('ss_categories')
//                            ->select('categoryID','name','parent')
//                            ->get();
        return Category::enabled()->select('categoryID','name','parent')->get();

    }

    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function recursiveCategory(array $nodes = [],$parentID = 1,$level = 1)
    {
        if (empty($nodes)) {
            $nodes = $this->getAllCategories()->toArray();
        }
        $options = $this->recursiveTree($nodes,$parentID,$level);
        return collect($options)->prepend('Root', 0)->all();

    }

    public function recursiveTree(array $nodes = [], $parentID = 1, $level = 2)
    {
        $options = [];
        foreach ($nodes as $node) {
            $node = (array)$node;
            if ($node['parent'] == $parentID) {
                $node['name'] = str_repeat('-',$level) . $node['name'];
                $options[$node['categoryID']] = $node['name'];
                $children = $this->recursiveTree($nodes,$node['categoryID'],$level+1);
                if ($children) {
                    $options += $children;
                }
            }
        }
        return $options;
    }

}