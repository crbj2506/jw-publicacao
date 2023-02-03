<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Volume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

class VolumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        if(App::environment() == 'local'){
            $volumes = Volume::paginate(10);
        }else{
            $volumes = Volume::paginate(100);
        }
        return view('volume.crud',['volumes' => $volumes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        //
        $envios = Envio::orderByDesc('data')->get();
        foreach ($envios as $key => $e) {
            $envios[$key]->text = $e->nota . ($e->data ? ' de '. $e->data : null);
            $envios[$key]->value = $e->id;
        }
        return view('volume.crud',['envios' => $envios]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $request->validate(Volume::rules($id = null),Volume::feedback());
        $volume = Volume::create($request->all());
        return redirect()->route('volume.show', ['volume' => $volume]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Contracts\View\View
     */
    public function show($volume)
    {
        //
        $volume = Volume::find($volume);
        if(Route::current()->action['as'] == "volume.show"){
            $volume->show = true;
        };
        $envios = Envio::all();
        return view('volume.crud', ['volume' => $volume, 'envios' => $envios]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Volume $volume)
    {
        //
        if(Route::current()->action['as'] == "volume.edit"){
            $volume->edit = true;
        };
        $envios = Envio::orderByDesc('data')->get();
        foreach ($envios as $key => $e) {
            $envios[$key]->text = $e->nota . ($e->data ? ' de '. $e->data : null);
            $envios[$key]->value = $e->id;
        }
        return view('volume.crud', ['volume' => $volume, 'envios' => $envios]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Volume  $volume
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $volume)
    {
        //
        $request->validate(Volume::rules($volume),Volume::feedback());
        $volume = Volume::find($volume);
        $volume->update($request->all());
        return redirect()->route('volume.show', ['volume' => $volume]);
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
