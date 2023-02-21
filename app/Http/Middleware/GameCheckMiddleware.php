<?php

namespace App\Http\Middleware;

use App\Models\Game;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $game = $request->game;

        if (is_string($game)) {
            $game = Game::find($game);
        }

        if ($game ===  null) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $nowDate = Carbon::now();

        if ($nowDate >= $game->expired_at) {
            $game->delete();
            abort(Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
