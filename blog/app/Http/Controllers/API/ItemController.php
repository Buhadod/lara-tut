<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::all();
        return response([ 'items' => $items, 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        $item = Item::create($input);

        return response(['item' => $item, 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return response(['item' => $item, 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        $item->update($request->all());

        return response(['item' => $item, 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response(['message' => 'Deleted']);
    }

    public function search($query)
    {
        //ref https://freek.dev/1182-searching-models-using-a-where-like-query-in-laravel
        return Item::query()
        ->where('name', 'LIKE', "%{$query}%") 
        ->orWhere('description', 'LIKE', "%{$query}%") 
        ->get();
        
    }

    public function filter($filter,$query)
    {        
        $comparison ='=';

        switch($filter){
            case 1:  $comparison = '>'; break;
            case 2:  $comparison = '<'; break;
        }

        // ->where('price','>',100) 
        return Item::query()
        ->where('price', $comparison, $query)
        ->get();

    }

    public function paginate()
    {        
        $items = Item::paginate(20);
        //simplePaginate 
        //cursorPaginate 
        return response([ 'items' => $items, 'message' => 'Retrieved successfully'], 200);

    }
}
