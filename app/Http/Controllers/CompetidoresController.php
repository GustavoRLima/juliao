<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetidoresRequest\CriarCompetidorRequest;
use App\Repository\CompetidoresRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompetidoresController extends Controller
{

    protected CompetidoresRepository $competidoresRepository;

    public function __construct()
    {
        $this->competidoresRepository = new CompetidoresRepository();
    }
    
    function index(Request $request)
    {
        $competidores = $this->competidoresRepository->getCompetidores($request->input());

        return Inertia::render('Competidores/Index', [
            'competidores' => $competidores
        ]);
    }
    
    function create()
    {
        return Inertia::render('Competidores/Form');
    }

    function store(CriarCompetidorRequest $request)
    {
            
    }
}
