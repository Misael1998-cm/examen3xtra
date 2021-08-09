<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Voto;
use App\Models\Votocandidato;
use App\Models\Candidato;
use App\Models\Casilla;
use App\Models\Eleccion;

class VotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $casillas = Casilla::all();
        $candidatos = Candidato::all();

        return view('voto/create', compact('casillas','candidatos'));
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
        $eleccion = DB::table('eleccion')
                ->orderBy('id','desc')
                ->limit(1)
                ->select('id')
                ->get();

        $acta=""; //declaraciones vacias

        if ($request->hasFile('acta')) {
            $acta = $request->acta->getClientOriginalName();
            $request->acta->move(public_path('uploads'), $acta);
        }
        
        $data = ["casilla_id" => $request->casilla_id, "evidencia" => $acta, "eleccion_id" => $eleccion[0]->id];

        $voto = Voto::create($data);

        $candidatosvotos = array_filter(
            $request->all(),
            function ($f)  {
                return ( str_starts_with($f,"candidato"));
            },
            ARRAY_FILTER_USE_KEY
        );

        foreach ($candidatosvotos as $k=>$v)   {
            $id= intval(substr($k,10));
    
            $votocandidato=[
                "voto_id" => $voto->id,
                "candidato_id" => $id,
                "votos" => $v
            ];

            VotoCandidato::create($votocandidato); //guardamos votos de cada candidato en la otra tabla

            //echo "$id => $v <br>";
           
        } // llave del foreach
        
        return($this-> create());

       

        //select id from eleccion order by id desc limit 1;
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
