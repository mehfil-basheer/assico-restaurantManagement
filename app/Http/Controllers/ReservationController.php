<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Table;
use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    use ApiControllerTrait;
    public function index()
    { 

        try {
            $reservations = Reservation::with('table', 'user')->get();
            return $this->successResponse(ReservationResource::collection($reservations), 'Reservations fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),400);
        }
    }

    public function store(CreateReservationRequest $request)
    {
        $table = Table::find($request->table_id);
        if (!$table->is_available) {
            return $this->errorResponse('Table is not available', 400);
        }

        $reservationData = $request->validated();
        $reservationData['user_id'] = Auth::id(); 

        $reservation = Reservation::create($reservationData);
        $table->update(['is_available' => false]);

        return $this->successResponse(new ReservationResource($reservation), 'Reservation created successfully', 201);

        return $this->successResponse(new ReservationResource($reservation), 'Reservation created successfully', 201);
    }

    public function show(Reservation $reservation)
    {
        return $reservation;
    }

    public function update(UpdateReservationRequest $request,$reservation)
    {
        $data = $request->validated();
        $reservation=Reservation::where('id',$reservation)->first();
        if (isset($data['table_id']) && $data['table_id'] != $reservation->table_id) {
            $table = Table::find($data['table_id']);
            if (!$table->is_available) {
                return $this->errorResponse('New table is not available', 400);
            }

            $reservation->table->update(['is_available' => true]);

            $table->update(['is_available' => false]);
        }

        $reservation->update($data);

        return $this->successResponse(new ReservationResource($reservation), 'Reservation updated successfully', 200);
    }

    public function destroy(Reservation $reservation)
    {
        

        try {

            $table = $reservation->table;
            $reservation->delete();
            $table->update(['is_available' => true]);
            return $this->successResponse([], 'reservation Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
