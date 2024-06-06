<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiControllerTrait;
    public function index()
    { 
        try {
            $order = Order::with('orderItems', 'reservation')->get();
            return $this->successResponse(OrderResource::collection($order), 'orders fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),400);
        }
    }

    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $orderData=$request->all();
      

            $order=Order::create([
                'reservation_id'=>$request->reservation_id,
                'status'=>$request->status,
                'createdBy'=> Auth::id()

            ]);

            $totalCost=0;
            foreach ($request->orderItems as $key => $orderItem) {
             
                $menuItem = MenuItem::findOrFail($orderItem['menu_id']);

                $unitPrice = $menuItem->price;
            
                $total = $unitPrice * $orderItem['quandity'];

               OrderItem::create([
                'order_id'=>$order->id,
                'menu_item_id'=>$orderItem['menu_id'],
                'quantity'=>$orderItem['quandity'],
                'price'=>$total
               ]);
               $totalCost=$totalCost+$total;
               $order->update(['total_cost'=>$totalCost]);
            }
            DB::commit();
            return $this->successResponse(new OrderResource($order), 'Reservation created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(Order $order)
    {
        return $order;
    }

    public function update(Request $request, Order $order)
    {
        DB::beginTransaction();

        $order->update([
            'reservation_id' => $request->reservation_id,
            'status' => $request->status,
            'createdBy' => Auth::id(),
        ]);

        $totalCost = 0;

        $order->orderItems()->delete();

        foreach ($request->orderItems as $orderItem) {

            $menuItem = MenuItem::findOrFail($orderItem['menu_id']);

            $unitPrice = $menuItem->price;

            $total = $unitPrice * $orderItem['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $orderItem['menu_id'],
                'quantity' => $orderItem['quantity'],
                'price' => $total,
            ]);

            $totalCost += $total;
        }

        $order->update(['total_cost' => $totalCost]);

        DB::commit();

        return response()->json($order, 200);
    }

    public function destroy(Order $order)
    {
       
       
        try {

            $order->delete();
            return $this->successResponse([], 'order Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function changeOrderStatus(Request $request){
        try {
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'status' => 'required|in:pending,completed,canceled',
            ]);

            $order = Order::findOrFail($request->order_id);
    
            $order->update([
                'status' => $request->status,
            ]);
    
            return $this->successResponse([], 'Order status changes successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
