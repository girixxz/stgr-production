<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\MaterialCategory;
use App\Models\MaterialTexture;
use App\Models\MaterialSleeve;
use App\Models\MaterialSize;
use App\Models\Shipping;
use App\Models\Service;


class ManageProductsController extends Controller
{
    public function index(Request $request)
    {
        $productCategories = ProductCategory::all();
        $materialCategories = MaterialCategory::all();
        $materialTextures = MaterialTexture::all();
        $materialSleeves = MaterialSleeve::all();
        $materialSizes = MaterialSize::all();
        $services = Service::all();
        $materialShippings = Shipping::all();

        return view('pages.owner.manage-data.products', compact(
            'productCategories',
            'materialCategories',
            'materialTextures',
            'materialSleeves',
            'materialSizes',
            'services',
            'materialShippings'
        ));
    }
}
