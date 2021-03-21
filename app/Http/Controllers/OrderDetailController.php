<?php

namespace App\Http\Controllers;

use App\Exceptions\DataSaveException;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required | integer | min:1',
            'order_id' => 'required | integer | min:1'
        ]);

        $orderDetail =  new OrderDetail;
        $orderDetail->product_id = $request->product_id;
        $orderDetail->order_id = $request->order_id;

        if($orderDetail->save()) {
             return response()->json([
                'status' => true,
                'message' => 'Order product was added successfully',
                'product' => $orderDetail
             ]);
        }else {
            throw new DataSaveException("Order product could not be added at the moment");
        }
    }

    public function destroy($id) {
        $deleteOderrDetail = OrderDetail::findOrFail($id);

        if($deleteOderrDetail->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Order product was added successfully',
                'product' => $deleteOderrDetail
             ]);
        }else {
            throw new DataSaveException("Order product could not be deleted");
        }
    }
}
