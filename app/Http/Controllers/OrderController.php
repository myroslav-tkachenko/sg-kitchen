<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Order;
use App\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // for waiters there is an individual list
        if ( $user->isWatier() ) {
            return view('home.order.index-waiter')
                        ->with('user', $user);
        }

        // admin and kitchen get their full list
        return view('home.order.index')
                    ->with('user', $user);
    }

    /**
     * Return raw Order's collection
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $user = Auth::user();
        
        if ( empty($user) ) return abort(401);

        // full Order's list is only for an admin
        $orders = Order::with('user', 'status')->get();

        // for waiters there is an individual list
        if ( $user->isWatier() ) {
            $orders = Order::where('user_id', '=', $user->id)
                        ->with('user', 'status')
                        ->get();
        }

        // kitchen sees only orders that was passed to them
        if ( $user->isCook() ) {
            $orders = Order::where('status_id', '=', 2)
                        ->orWhere('status_id', '=', 3)
                        ->with('user', 'status')
                        ->get();
        }

        return $orders;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('home.order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_id' => 'required|integer|min:0',
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('home/order/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = Auth::user();

        $order = Order::create([
            'table_id' => $request['table_id'],
            'name' => $request['name'],
            'user_id' => $user->id,
            'status_id' => 1, // TODO: consider to add as a default value to Scheme
        ]);

        return redirect('home/order');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
