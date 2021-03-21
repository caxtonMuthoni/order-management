<?php

namespace App\Http\Controllers;

use App\Exceptions\DataSaveException;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierProductController extends Controller
{
    // Get supplier products
    public function index()
    {
        return SupplierProduct::with(['product', 'supplier'])->latest()->paginate(10);
    }

    // Add supplier products
    public function store(Request $request)
    {
        //validation
        $this->validate($request, [
            'supplier_id' => 'required | integer | min:1',
            'products_id' => 'required'
        ]);

        $productID = $request->products_id;
        $supplierID = $request->supplier_id;

        $supplierProductCheck = SupplierProduct::where([['product_id', $productID], ['supply_id', $supplierID]])->first();
        if ($supplierProductCheck != null) {
            return response()->json([
                'status' => false,
                'message' => 'Supplier Product aleady exists',
                'supplier' => $supplierProductCheck
            ]);
        }

        $supplierProduct = new SupplierProduct;

        $product = Product::findOrFail($productID);
        $supplier = Supplier::findOrFail($supplierID);

        $supplierProduct->product()->associate($product);
        $supplierProduct->supplier()->associate($supplier);

        if ($supplierProduct->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Supplier Product was created successfully',
                'supplier' => $supplierProduct
            ]);
        } else {
            throw new DataSaveException("Supplier Product could not be created.");
        }
    }

    // update supplier product
    public function update(Request $request, $id)
    {
        ////validation
        $this->validate($request, [
            'supplier_id' => 'required | integer | min:1',
            'products_id' => 'required'
        ]);

        $productID = $request->products_id;
        $supplierID = $request->supplier_id;

        $supplierProductCheck = SupplierProduct::where([['product_id', $productID], ['supply_id', $supplierID]])->first();
        if ($supplierProductCheck != null) {
            return response()->json([
                'status' => false,
                'message' => 'Supplier Product aleady exists',
                'supplier' => $supplierProductCheck
            ]);
        }

        $supplierProduct = SupplierProduct::findOrFail($id);

        $product = Product::findOrFail($productID);
        $supplier = Supplier::findOrFail($supplierID);

        $supplierProduct->product()->associate($product);
        $supplierProduct->supplier()->associate($supplier);

        if ($supplierProduct->save()) {

            $supplierProduct->refresh();

            return response()->json([
                'status' => true,
                'message' => 'Supplier Product was updated successfully',
                'supplier' => $supplierProduct
            ]);
        } else {
            throw new DataSaveException("Supplier Product could not be updated.");
        }
    }

     // Get Supplier proucts

     public function getSupplierProucts() {
          $suppliers = SupplierProduct::with('product', 'supplier')
          ->select('supply_id', DB::raw('count(*) as total'))
          ->groupBy('supply_id')
          ->latest()
          ->take(10)
          ->get();


          return $suppliers;
    }


    public function destroy($id)
    {
        $deleteSupplyProduct = SupplierProduct::findOrFail($id);

        if ($deleteSupplyProduct->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Supplier Product was deleted successfully',
                'supplier' => $deleteSupplyProduct
            ]);
        } else {
            throw new DataSaveException("Supplier Product could not be deleted.");
        }
    }
}
