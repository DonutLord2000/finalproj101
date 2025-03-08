<?php
namespace App\Http\Controllers\Students;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class StudentController extends Controller
{
    public function index()
    {
        if(Auth::id())
        {
            
        }
    }
}