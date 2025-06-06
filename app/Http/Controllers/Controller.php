<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function retornoErrorBack($e, $mensagem = 'Ocorreu um erro inesperado.')
    {
        $error = $mensagem;
        if($e->getCode() == 400){
            $error = $e->getMessage();
        }
        return redirect()->back()->withErrors(['error' => $error, 'detalhes' => $e->getMessage()])->withInput();
    }
}
