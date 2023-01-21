<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Volume;
use Illuminate\Http\Request;

class VolumeController extends Controller
{
    public function __construct(Volume $volume){
        $this->volume = $volume;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $volumes = $this->volume->paginate(10);
        return view('volume.index',['volumes' => $volumes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $envios = Envio::all();
        return view('volume.create',['envios' => $envios]);
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
        $request->validate($this->volume->rules($id = null),$this->volume->feedback());
        $volume = $this->volume->create($request->all());
        return redirect()->route('volume.show', ['volume' => $volume->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $volume = $this->volume->find($id);
        $envios = Envio::all();
        return view('volume.show', ['volume' => $volume, 'envios' => $envios]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\Response
     */
    public function edit(Volume $volume)
    {
        //
        $envios = Envio::all();
        return view('volume.edit', ['volume' => $volume, 'envios' => $envios]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate($this->volume->rules($id),$this->volume->feedback());
        $volume = $this->volume->find($id);
        $volume->update($request->all());
        return redirect()->route('volume.show', ['volume' => $volume->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\Response
     */
    public function destroy(Volume $volume)
    {
        //
    }
}
