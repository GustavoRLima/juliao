<?php 

namespace App\Services;

use App\Repository\CompetidoresRepository;

class CompetidoresService 
{
    protected CompetidoresRepository $competidoresRepository;

    public function __construct()
    {
        $this->competidoresRepository = new CompetidoresRepository();
    }

    public function getCompetidores($dados)
    {
        return $this->competidoresRepository->getCompetidores($dados)->paginate(15);
    }
}