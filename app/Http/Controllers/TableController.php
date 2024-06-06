<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTableRequest;
use App\Http\Resources\TableResource;
use App\Models\Table;
use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;

class TableController extends Controller
{
    use ApiControllerTrait;


    /**
 * @OA\Get(
 *     path="/api/tables",
 *     tags={"Tables"},
 *     summary="Get list of tables",
 *     description="Returns a list of tables",
 *     @OA\Response(response="200", description="Successful operation"),
 * )
 */
    public function index()
    {
        
        try {
            $tables = Table::all();
            $data = $tables->map(function ($table) {
                return [
                    'id' => $table->id,
                    'number' => $table->number,
                    'seating_capacity' => $table->seating_capacity,
                    'status' => $table->is_available ? 'vacant' : 'reserved',
                ];
            });

            return $this->successResponse($data, 'Tables fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    
    public function store(CreateTableRequest $request)
    {
       
   try{
    
            $table = Table::create($request->all());
            return $this->successResponse($table, 'Table Saved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),400);
        }
    }

    public function show(Table $table)
    {
        try {
            $data = $table;
            return $this->successResponse($data, 'Table Fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),400);
        }
    }

    public function update(Request $request,$table)
    {

        try {

            $table=Table::where('id',$table)->first();
            $table->update($request->all());
            return $this->successResponse(new TableResource($table), 'Table updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        
    }

    public function destroy(Table $table)
    {
      
        try {

            $table->delete();
            return $this->successResponse([], 'Table Deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
