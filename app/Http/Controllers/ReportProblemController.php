<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Helpers\DiscordHelper;

class ReportProblemController extends Controller
{
    // List for Admin
    public function index()
    {
        if (session('id') && (session('level') == 6 || session('level') == 5)) {
            $reports = DB::table('problem_reports')
                ->join('users', 'problem_reports.user_id', '=', 'users.id')
                ->select('problem_reports.*', 'users.name as reporter_name')
                ->orderByDesc('created_at')
                ->get();
            return view('report_problem.index', compact('reports'));
        }
        return view('404');
    }

    // Form for User
    public function create()
    {
        if (session('id') > 0) {
            return view('report_problem.create');
        }
        return redirect('/login');
    }

    // Store Report
    public function store(Request $request)
    {
        if (session('id') > 0) {
            $request->validate([
                'description' => 'required|string',
                'photo_proof' => 'nullable|image|max:2048'
            ]);

            $photoPath = null;
            if ($request->hasFile('photo_proof')) {
                $photoPath = $request->file('photo_proof')->store('problem_reports', 'public');
            }

            DB::table('problem_reports')->insert([
                'user_id' => session('id'),
                'description' => $request->description,
                'photo_proof' => $photoPath,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DiscordHelper::sendNotification("User **" . session('name') . "** melaporkan masalah: " . $request->description, "Laporan Masalah Baru", 15158332); // Red

            return redirect('/home')->with('success', 'Laporan masalah berhasil dikirim. Terima kasih!');
        }
        return redirect('/login');
    }

    // Update Status (Admin)
    public function update(Request $request, $id)
    {
        if (session('id') && (session('level') == 6 || session('level') == 5)) {
            DB::table('problem_reports')->where('id', $id)->update([
                'status' => $request->status,
                'admin_note' => $request->admin_note,
                'updated_at' => now()
            ]);

            return back()->with('success', 'Status laporan diperbarui!');
        }
        return view('404');
    }
}
