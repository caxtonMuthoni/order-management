<?php

namespace App\Http\Controllers;

use App\Exceptions\DataSaveException;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // Get Suppliers in pagination of 10
    public function index()
    {
        return Supplier::with('supplierProducts.product')->latest()->paginate(10);
    }

//    Add Suppier
    public function store(Request $request)
    {
        //validation

        $this->validate($request,[
            'name' => 'required | string | min:5 | max:45 | unique:suppliers'
        ]);

        // Add supplier
        $supplier = new Supplier;
        $supplier->name = $request->name;

        if($supplier->save()){
            return response()->json([
                'status' => true,
                'message' => 'Supplier was added successfully',
                'supplier' => $supplier
            ]);
        } else{
            throw new DataSaveException("Supplier could not be created at the moment");
        }
    }

    // Show Supplier details

    public function show($id) {
        return Supplier::with('supplierProducts.product')->findOrFail($id);
    }

    // Update supplier
    public function update(Request $request, $id)
    {
         //validation

         $this->validate($request,[
            'name' => 'required | string | min:5 | max:45 | unique:suppliers,name,'.$id
        ]);

        // update supplier
        $supplier = Supplier::findOrFail($id);
        $supplier->name = $request->name;

        if($supplier->save()){
            return response()->json([
                'status' => true,
                'message' => 'Supplier was updated successfully',
                'supplier' => $supplier
            ]);
        } else {
            throw new DataSaveException("Supplier could not be updated at the moment");
        }
    }


    // Delete supplier
    public function destroy($id)
    {
        $deleteSupplier = Supplier::findOrFail($id);

        if($deleteSupplier->delete()){
            return response()->json([
                'status' => true,
                'message' => 'Supplier was deleted successfully',
                'supplier' => $deleteSupplier
            ]);
        }else {
            throw new DataSaveException("Supplier could not be deleted at the moment");
        }
    }
}
