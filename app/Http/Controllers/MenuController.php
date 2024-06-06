<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use App\Traits\ApiControllerTrait;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use ApiControllerTrait;
    public function index()
    {
     
        try {
            $menuItems = MenuItem::all();
            return $this->successResponse(MenuItemResource::collection($menuItems), 'Menu items fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(),400);
        }
    }

    public function store(CreateMenuRequest $request)
    {
        try {
            $menuItem = MenuItem::create($request->all());
            return $this->successResponse(new MenuItemResource($menuItem), 'Menu item created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show(MenuItem $menuItem)
    {
        try {
            return $this->successResponse(new MenuItemResource($menuItem), 'Menu item fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function update(UpdateMenuRequest $request,$menuItem)
    {
        try {

            $menuItem=MenuItem::where('id',$menuItem)->first();
            $menuItem->update($request->all());
            return $this->successResponse(new MenuItemResource($menuItem), 'Menu item updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy(MenuItem $menuItem)
    {
        try {
            $menuItem->delete();
            return $this->successResponse(null, 'Menu item deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
