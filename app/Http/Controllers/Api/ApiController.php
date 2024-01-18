<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Series;
use App\Repositories\SeriesRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(private SeriesRepository $seriesRepository)
    {
    }

    public function index(Request $request)
    {
        $query = Series::query();

        if ($request->has('nome'))
        {
            $query->where('nome', $request->nome);
        }

        return $query->get();
        //return $query->paginate(5);
    }

    public function store(SeriesFormRequest $request)
    {
        return response()
            ->json($this->seriesRepository->addSerie($request), 201);
    }

    public function update(Series $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();

        return $series;

        // Series::where(‘id’, $series)->update($request->all());
        // retorno de uma resposta que não contenha a série, já que não fizemos um `SELECT`
    }

    public function destroy(int $series, Authenticatable $user)
    {
        //if ($user->tokenCan('is_admin')){};

        Series::destroy($series);
        return response()->noContent();
    }

    public function show(int $series)
    {
        $seriesModel = Series::with('seasons.episodes')->find($series);
        if ($seriesModel === null){
            return response()->json(['message' => 'Série não encontrada.'], 404);
        }

        return $seriesModel;
    }

    /*public function destroy(Series $series)
    {
        $series = $series->delete();
        return response()->json($series, 200);
    }*/

    /*public function show(int $series)
    {
        $series = Series::whereId($series)
            ->with('seasons.episodes')
            ->first();
        return $series;
    }*/

}
