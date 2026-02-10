<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate(['q' => 'required']);

        $users = User::where('name', 'like', '%'.$request->q.'%')->get();

        return view('profile.search', compact('users'));
    }
}
