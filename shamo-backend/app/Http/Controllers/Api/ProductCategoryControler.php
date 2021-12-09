<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryControler extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if ($id) {
            $category = ProductCategory::with(['products'])->find($id);
            if ($category) {
                return ResponseFormatter::success($category, 'Data Category Berhasil Diambil');
            } else {
                return ResponseFormatter::success(null, 'Data Category Gagal Diambil', 404);
            }
        }

        $category = ProductCategory::query();
        
        if($name){
            $category->where('name', 'like' , '%' . $name . '%');
        }

        if($show_product){
            $category->with('products');
        }

        return ResponseFormatter::success($category->paginate($limit),'Data Category Berhasil Diambil');


    }
}
