<?php
/**
 * 表单特性
 * @author pnpeng
 * @date 2018-03-07
 **/
namespace App\Traits;
use Illuminate\Support\Str;

trait FormTrait
{

    /**
     * 获取Select框中的列表
     * @param bool $is_default 是否启用默认选中文字
     * @return mixed
     */
    public static function getSelectOptions($is_default = true)
    {
        $match_name = null;
        $table_name = (new static)->getTable();
        $columns = (new static)->getFillable();
        foreach ($columns as $key => $column) {
            if ($column == 'name') {  // 如果数据库中含有name字段则直接使用
                $match_name = 'name';
                break;
            } elseif (Str::contains($column,'_name')) { // 匹配出当前表所属的name字段
                $compare_name = str_replace('_name','',$column);
                if (similar_text($compare_name, $table_name) >= 3) {
                    $match_name = $column;
                    break;
                }
            }
        }
        $select_options = self::get()->keyBy('id')->map(function ($item) use($match_name) {
           return $item->$match_name;
        });

        if ($is_default == true) {
            return $select_options->prepend('-','');
        } else {
            return $select_options;
        }
    }
}