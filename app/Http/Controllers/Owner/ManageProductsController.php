<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\MaterialCategory;
use App\Models\MaterialTexture;
use App\Models\MaterialSleeve;
use App\Models\MaterialSize;
use App\Models\Shipping;
use Illuminate\Http\Request;


class ManageProductsController extends Controller
{
    public function index(Request $request)
    {
        $productCategories = ProductCategory::all();
        $materialCategories = MaterialCategory::all();
        $materialTextures = MaterialTexture::all();
        $materialSleeves = MaterialSleeve::all();
        $materialSizes = MaterialSize::all();
        $materialShippings = Shipping::all();

        return view('pages.owner.manage-products', compact(
            'productCategories',
            'materialCategories',
            'materialTextures',
            'materialSleeves',
            'materialSizes',
            'materialShippings'
        ));
    }
}
