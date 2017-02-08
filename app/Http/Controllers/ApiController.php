<?php 

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /* api actions live in App\Http\Controller\Api to avoid bloat in the Controllers namespace */

    use Api\Movies;
    use Api\Actors;
    use Api\Genres;

}