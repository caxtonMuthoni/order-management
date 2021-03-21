<?php

namespace App\Http\Controllers;

use App\Exceptions\DataSaveException;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    //    List of products in pagination of 10 items per request
    public function index()
    {
        return Product::latest()->paginate(10);
    }

    // Adding new product
    public function store(Request $request)
    {
        //validation

        $this->validate($request, [
            'name' => 'required | string | max:45 | unique:products',
            'description' => 'required | string | max:45',
            'quantity' => 'required | integer | min:1 | max:100000',
        ]);

        // add product

        $product = new Product;

        $product->name = $request->name;
        $product->description = $request->description;
        $product->quantity = $request->quantity;

        if ($product->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Product was added successfully',
                'product' => $product
            ]);
        } else {
            throw new DataSaveException("Product could not be created at the moment");
        }
    }

    // Search

    public function search(Request $request)
    {

        // validation
        $this->validate($request, [
            'search' => 'required | string | min:3'
        ]);

        $search = Product::where('name', 'like', '%' . $request->search . '%')
            ->latest()->get();
        return $search;
    }

    // GetUser
    public function users() {
        return User::get()->count();
    }


    // Update product{Error handling done globally}

    public function update(Request $request, $id)
    {
        //validation

        $this->validate($request, [
            'name' => 'required | string | max:45 | unique:products,name,'.$id,
            'description' => 'required | string | max:45',
            'quantity' => 'required | integer | min:1 | max:100000',
        ]);

        // update product

        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->quantity = $request->quantity;

        if ($product->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Product was updated successfully',
                'product' => $product
            ]);
        } else {
            throw new DataSaveException("Product could not be updated at the moment");
        }
    }

    // Delete Product{Error handling done globally}
    public function destroy($id)
    {
        $deleteProduct = Product::findOrFail($id);
        if ($deleteProduct->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Product was deleted successfully',
                'product' => $deleteProduct
            ]);
        } else {
            throw new DataSaveException("Product could not be delePted at the moment");
        }
    }
}
