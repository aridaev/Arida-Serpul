<?php

namespace App\Http\Controllers;

use App\Models\MutasiSaldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $query = MutasiSaldo::where('user_id', Auth::id())
            ->with('currency');

        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        $mutasis = $query->latest()->paginate(20);

        return view('mutasi.index', compact('mutasis'));
    }
}
