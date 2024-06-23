<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Tests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TestController extends BaseController
{
    public function index(Request $request)
    {
        $userId = $request->user()->id; // Получение ID текущего пользователя
        $tests = Tests::where('user_id', $userId)->get(); // Предполагается, что у вас есть столбец user_id в таблице tests
        return response()->json($tests);
    }
}
