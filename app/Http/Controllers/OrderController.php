<?php

namespace App\Http\Controllers;

use App\Exceptions\DataSaveException;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Get orders in pages of 10
    public function index()
    {
        return Order::with('orderDetails.product')->latest()->paginate(10);
    }

    // Get Order products

    public function show($id) {
        return Order::with('orderDetails.product')->findOrFail($id);
    }

    //    Create Order
    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'product_id' => 'required | integer | min:0'
        ]);

        $product_id = $request->product_id;

        $product = Product::findOrFail($product_id);

        $latestId = 0;
        $latestOrder = Order::orderBy('created_at', 'DESC')->first();

        if ($latestOrder != null) {
            $latestId = $latestOrder->id;
        }

        // Prepare unique order_number

        $orderNumber = 'OD' . str_pad($latestId + 1, 8, "0", STR_PAD_LEFT);

        $order = new Order;
        $order->order_number = $orderNumber;

        if ($order->save()) {
            $orderDetail = new OrderDetail;

            $orderDetail->product()->associate($product);
            $orderDetail->order()->associate($order);

            if ($orderDetail->save()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Order was created successfully',
                    'order' => $order
                ]);
            }else {
                throw new DataSaveException("Order could not be created at the moment");
            }
        }else {
            throw new DataSaveException("Order could not be created at the moment");
        }
    }

    // Update product

    public function update(Request $request, $id)
    {
        // Validation
        $this->validate($request, [
            'product_id' => 'required | integer | min:0',
            'order_detail_id' => 'required | integer | min:0'
        ]);

        $product_id = $request->product_id;
        $orderDetail_id = $request->order_detail_id;

        $product = Product::findOrFail($product_id);


        $order = Order::with('orderDetails.product')->findOrFail($id);

        $orderDetail = OrderDetail::where([['order_id', $id],['id', $orderDetail_id]])->firstOrFail();

        $orderDetail->product()->associate($product);
        $orderDetail->order()->associate($order);

        if ($orderDetail->save()) {

            $order->refresh();

            return response()->json([
                'status' => true,
                'message' => 'Order was updated successfully',
                'order' => $order
            ]);
        }else {
            throw new DataSaveException("Order could not be updated at the moment");
        }
    }

    // Delete Order
    public function destroy($id)
    {
        $deleteOrder = Order::findOrFail($id);

        if ($deleteOrder->delete()) {
            return response()->json([
                'status' => true,
                'message' => 'Order was deleted successfully',
                'supplier' => $deleteOrder
            ]);
        }else {
            throw new DataSaveException("Order could not be deleted at the moment");
        }
    }
}
