<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

 /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="3.0",
     *         title="Car-Comminty List Api",
     *         description="List of Api ya rab nfhm ya 4bab",
     *     )
     * )
     */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
}
