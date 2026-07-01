<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Models\Reservasi;

use Carbon\Carbon;

class AdminMemberController extends Controller
{
    public function dataMember(Request $request)
{
    $members = User::with('latestMembership')
        ->whereIn('role', ['member', 'member_private']);

    if ($request->filled('search')) {
        $members->where('name', 'like', '%' . $request->search . '%');
    }

    $members = $members
        ->orderBy('name', 'asc')
        ->get();

    foreach ($members as $m) {

        $membership = $m->latestMembership;

        if (
            $membership &&
            $membership->expired &&
            Carbon::now()->greaterThan($membership->expired)
        ) {
            if ($membership->status !== 'expired') {
                $membership->update([
                    'status' => 'expired',
                    'sesi_tersisa' => 0
                ]);
            }
        }

        $m->membership_aktif = $membership;
    }

    return view('admin.data-member', compact('members'));
}

    public function detailMember($id)
    {
        $member = User::findOrFail($id);

        $membership = Membership::where('user_id', $id)
            ->where('status', 'aktif')
            ->latest()
            ->first();

        $membershipHistory = Membership::where('user_id', $id)
            ->latest()
            ->get();

        $reservasis = Reservasi::where('user_id', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.detail-member', compact(
            'member',
            'membership',
            'membershipHistory',
            'reservasis'
        ));
    }

    public function nonaktifkan($id)
    {
        $membership = Membership::findOrFail($id);

        $membership->update([
            'status' => 'nonaktif'
        ]);

        return back()->with('success', 'Membership berhasil dinonaktifkan');
    }

    public function hapusMember($id)
    {
        $member = User::findOrFail($id);

        $member->update([
            'role' => 'nonaktif'
        ]);

        return back()->with('success', 'Member dinonaktifkan (tidak dihapus)');
    }
}
