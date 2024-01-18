<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function (){
    // API
    Route::apiResource('/series', ApiController::class);

    // Seasons
    Route::get('/series/{series}/seasons', function (\App\Models\Series $series){
        return $series->seasons;
    });


    // Episodes
    Route::get('/series/{series}/episodes', function (\App\Models\Series $series){
        return $series->episodes;
    });

    // Atualiza apenas os eps daquela serie, temporada e episodio
    Route::patch('/series/{series}/seasons/{seasons}/episodes/{episodes}', function (int $series, int $season, int $episode, Request $request){

        $episodes = \App\Models\Episode::with('season.series')
            ->where('number', $episode)
            ->whereHas('season', function ($query) use ($series, $season) {
                $query->where('number', $season)
                    ->whereHas('series', function ($query) use ($series) {
                        $query->where('id', $series);
                    });
            })
            ->get();

        foreach ($episodes as $episodeDB) {
            $episodeDB->watched = $request->watched;
            $episodeDB->save();
        }

        $serie = \App\Models\Series::with(['seasons' => function ($query) use ($season) {
            $query->where('number', $season)->with('episodes');
        }])->find($series);

        return response()->json($serie);
    });

    // Atualiza todos eps com o id
    Route::patch('/episodes/{episodes}', function (int $episode, Request $request) {

        \App\Models\Episode::query()->where(['number' => $episode])
            ->update(['watched' => $request->watched]);

        return \App\Models\Episode::all();
    });
});

//LOGIN
Route::post('/login', function (Request $request){
    $credential = $request->only(['email', 'password']);

    if (!Auth::attempt($credential))
    {
        return response()->json('Unauthorized', 401);
    }

    $user = Auth::user();
    $user->tokens()->delete();
    //$token = $user->createToken('token');
    $token = $user->createToken('token', ['is_admin']);

    return response()->json($token->plainTextToken);
});

//Route::get('/series', [ApiController::class, 'allSeries']);
//Route::post('/series', [ApiController::class, 'addSeries']);
