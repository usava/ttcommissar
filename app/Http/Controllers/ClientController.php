<?php

namespace App\Http\Controllers;

use App\Client;
use App\City;
use App\ClientCoordinate;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all()->keyBy('id');
        $cities = City::all()->keyBy('id')->toJson();

        //Get coordinates only for current day
        $_today_coordinates = ClientCoordinate::where('updated_at','>', date('Y-m-d', time()));
        $today_coordinates = $_today_coordinates->get();

        foreach($clients as &$client){
            $client_coordinates = array_values($today_coordinates->where('client_id', $client->id)->toArray());
            $client->today_coordinates = $client_coordinates;
        }
        $filled_clients = json_encode($clients);

        //get client ids from list of this coordinates
        $clients_with_coordinates = $_today_coordinates->groupBy('client_id')->pluck('client_id')->toArray();

        return view('welcome', compact('clients', 'filled_clients', 'cities', 'clients_with_coordinates'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
}
