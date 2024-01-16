<?php

namespace App\Repositories;

use App\Http\Requests\SeriesFormRequest;
use App\Models\Series;

interface SeriesRepository
{
    public function addSerie(SeriesFormRequest $request): Series;
}
