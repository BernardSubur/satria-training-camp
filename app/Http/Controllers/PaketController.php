<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;

use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaketController extends Controller
{

    public function index()
    {
        $pakets = Paket::all();

        $membership = Membership::where('user_id', Auth::id())
            ->latest()
            ->first();

        return view('member.paket', compact('pakets', 'membership'));
    }


    public function batal()
    {

        auth()->logout();

        return redirect()->route('welcome');
    }

}
