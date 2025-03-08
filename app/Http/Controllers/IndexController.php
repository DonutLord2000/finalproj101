<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class IndexController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'student')
        {
            return view('dashboard');
        }
    }
}