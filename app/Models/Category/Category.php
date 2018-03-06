<?php

namespace App\Models\Category\Category;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'ss_categories';
    protected $primaryKey = 'categoryID';
    protected $fillable = [];

    /**
     * 获取此分类下所有的产品
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product\Product','categoryID');
    }


    /**
     * 限制查询只包括可用的分类。
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enable',1);
    }

}
