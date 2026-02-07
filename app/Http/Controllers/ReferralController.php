<?php

namespace App\Http\Controllers;

use App\Models\ReferralLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $referrals = User::where('referred_by', $user->id)->get();
        $logs = ReferralLog::where('user_id', $user->id)
            ->with(['downline', 'currency'])
            ->latest('created_at')
            ->paginate(20);
        $totalKomisi = ReferralLog::where('user_id', $user->id)->sum('komisi');

        return view('referral.index', compact('user', 'referrals', 'logs', 'totalKomisi'));
    }
}
