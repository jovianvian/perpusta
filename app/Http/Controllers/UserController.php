<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private function currentUser()
    {
        return DB::table('users')->where('id', session('id'))->first();
    }

    private function superAdminLevelId()
    {
        return DB::table('levels')
            ->whereRaw('LOWER(nama_level) = ?', ['super admin'])
            ->value('id');
    }

    private function actorIsSuperAdmin(): bool
    {
        $actor = $this->currentUser();
        if (!$actor) {
            return false;
        }

        $superAdminLevelId = $this->superAdminLevelId();
        return $superAdminLevelId && (int) $actor->level_id === (int) $superAdminLevelId;
    }

    // ------------------- DATA USER -------------------
    public function dataUser()
    {
        if (session('id') > 0) {
            $superAdminLevelId = $this->superAdminLevelId();
            $actorIsSuperAdmin = $this->actorIsSuperAdmin();

            $users = DB::table('users')
            ->leftJoin('levels', 'users.level_id', '=', 'levels.id')
            ->select('users.*', 'levels.nama_level')
            ->whereNull('users.deleted_at')
            ->when(!$actorIsSuperAdmin && $superAdminLevelId, function ($query) use ($superAdminLevelId) {
                $query->where('users.level_id', '!=', $superAdminLevelId);
            })
            ->orderBy('users.id', 'desc')
            ->get();

            $levels = DB::table('levels')
                ->when(!$actorIsSuperAdmin && $superAdminLevelId, function ($query) use ($superAdminLevelId) {
                    $query->where('id', '!=', $superAdminLevelId);
                })
                ->get();

            // Additional Data for Super Admin (Trash & History)
            $deletedUsers = [];
            $historyUsers = [];
            if (session('level') == 6 || session('level') == 5) {
                $deletedUsers = DB::table('users')
                    ->leftJoin('levels', 'users.level_id', '=', 'levels.id')
                    ->select('users.*', 'levels.nama_level')
                    ->whereNotNull('users.deleted_at')
                    ->when(!$actorIsSuperAdmin && $superAdminLevelId, function ($query) use ($superAdminLevelId) {
                        $query->where('users.level_id', '!=', $superAdminLevelId);
                    })
                    ->get();

                $historyUsers = DB::table('edit_histories')
                    ->where('table_name', 'users')
                    ->join('users', 'edit_histories.edited_by', '=', 'users.id')
                    ->select('edit_histories.*', 'users.name as editor_name')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return view('datauser', compact('users', 'levels', 'deletedUsers', 'historyUsers'));
        } else {
            return view('404');
        }
    }

    public function resetPassword($id)
    {
        $target = DB::table('users')->where('id', $id)->first();
        if (!$target) {
            return redirect('/datauser')->with('error', 'User tidak ditemukan.');
        }

        $superAdminLevelId = $this->superAdminLevelId();
        if (!$this->actorIsSuperAdmin() && $superAdminLevelId && (int) $target->level_id === (int) $superAdminLevelId) {
            return redirect('/datauser')->with('error', 'Akun Super Admin tidak bisa direset oleh role ini.');
        }

        DB::table('users')->where('id', $id)->update([
            'password' => Hash::make('12345678'),
            'updated_at' => now()
        ]);

        return redirect('/datauser')->with('success', 'Password berhasil direset!');
    }

    public function editUser($id)
    {
        if (!session('id')) {
            return view('404');
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return abort(404);
        }

        return view('form_datauser', compact('user'));
    }

    public function storeUsers(Request $request)
    {
        $superAdminLevelId = $this->superAdminLevelId();
        if (
            !$this->actorIsSuperAdmin() &&
            $superAdminLevelId &&
            (int) $request->level_id === (int) $superAdminLevelId
        ) {
            return redirect('/datauser')->with('error', 'Role Super Admin tidak bisa dibuat oleh role ini.');
        }

        $newUserId = DB::table('users')->insertGetId([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'level_id'   => $request->level_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $levelName = DB::table('levels')->where('id', $request->level_id)->value('nama_level');

        if ($levelName == 'penjaga') {
            DB::table('penjagas')->insert([
                'user_id'    => $newUserId,
                'jabatan_id' => 1,
                'no_hp'      => '-',
                'created_at' => now(),
                'updated_at' => now()
            ]);

        } elseif ($levelName == 'peminjam') {
            DB::table('peminjams')->insert([
                'user_id'       => $newUserId,
                'alamat'        => '-',
                'no_hp'         => '-',
                'jenis_kelamin' => '-',
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }

        DB::table('edit_histories')->insert([
            'table_name' => 'users',
            'row_id' => $newUserId,
            'action_type' => 'create',
            'perubahan' => 'User baru ditambahkan',
            'edited_by' => session('id'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/datauser')->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {

            $currentUser = DB::table('users')->where('id', $id)->first();
            if (!$currentUser) {
                return redirect('/datauser')->with('error', 'User tidak ditemukan.');
            }

            $superAdminLevelId = $this->superAdminLevelId();
            $actorIsSuperAdmin = $this->actorIsSuperAdmin();

            if (!$actorIsSuperAdmin && $superAdminLevelId) {
                if ((int) $currentUser->level_id === (int) $superAdminLevelId) {
                    return redirect('/datauser')->with('error', 'Akun Super Admin tidak bisa diubah oleh role ini.');
                }
                if ((int) $request->level_id === (int) $superAdminLevelId) {
                    return redirect('/datauser')->with('error', 'Role Super Admin tidak bisa diberikan oleh role ini.');
                }
            }

            $oldLevelId = $currentUser ? $currentUser->level_id : null;

            $userData = [
                'name'       => $request->name,
                'email'      => $request->email,
                'level_id'   => $request->level_id,
                'updated_at' => now()
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            DB::table('users')->where('id', $id)->update($userData);

            $newLevelId = $request->level_id;

            if ($oldLevelId != $newLevelId) {
                DB::table('user_level_histories')->insert([
                    'user_id'      => $id,
                    'old_level_id' => $oldLevelId,
                    'new_level_id' => $newLevelId,
                    'updated_by'   => session('id'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $oldValues = $currentUser ? [
                'name' => $currentUser->name,
                'email' => $currentUser->email,
                'level_id' => $currentUser->level_id,
            ] : null;

            $newValues = [
                'name' => $request->name,
                'email' => $request->email,
                'level_id' => $request->level_id,
            ];

            $perubahanText = 'Data user diperbarui';
            if ($oldValues) {
                $detailChanges = [];
                foreach ($newValues as $key => $newVal) {
                    if (!array_key_exists($key, $oldValues)) continue;
                    $oldVal = $oldValues[$key];
                    if ((string)$oldVal === (string)$newVal) continue;

                    if ($key === 'name') {
                        $label = 'Nama';
                    } elseif ($key === 'email') {
                        $label = 'Email';
                    } elseif ($key === 'level_id') {
                        $label = 'Level';
                        $oldVal = $oldVal ? DB::table('levels')->where('id', $oldVal)->value('nama_level') : null;
                        $newVal = $newVal ? DB::table('levels')->where('id', $newVal)->value('nama_level') : null;
                    } else {
                        $label = ucfirst($key);
                    }

                    $oldText = $oldVal === null || $oldVal === '' ? '-' : (string)$oldVal;
                    $newText = $newVal === null || $newVal === '' ? '-' : (string)$newVal;
                    $detailChanges[] = $label . ': ' . $oldText . ' → ' . $newText;
                }
                if (!empty($detailChanges)) $perubahanText = implode('; ', $detailChanges);
            }

            DB::table('edit_histories')->insert([
                'table_name' => 'users',
                'row_id' => $id,
                'action_type' => 'update',
                'perubahan' => $perubahanText,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => json_encode($newValues),
                'edited_by' => session('id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Sync User Roles
            $levelRaw = DB::table('levels')->where('id', $request->level_id)->value('nama_level');
            $levelName = strtolower(trim($levelRaw));
            
            if ($levelName == 'penjaga') {
                $exist = DB::table('penjagas')->where('user_id', $id)->first();
                if (!$exist) {
                    $jabatan = DB::table('jabatans')->first();
                    $jabatanId = $jabatan ? $jabatan->id : 1;
                    DB::table('penjagas')->insert([
                        'user_id'    => $id,
                        'jabatan_id' => $jabatanId,
                        'no_hp'      => '-',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                DB::table('peminjams')->where('user_id', $id)->delete();
            } elseif ($levelName == 'peminjam') {
                $exist = DB::table('peminjams')->where('user_id', $id)->first();
                if (!$exist) {
                    DB::table('peminjams')->insert([
                        'user_id'       => $id,
                        'alamat'        => '-',
                        'no_hp'         => '-',
                        'jenis_kelamin' => '-',
                        'created_at'    => now(),
                        'updated_at'    => now()
                    ]);
                }
                DB::table('penjagas')->where('user_id', $id)->delete();
            } else {
                DB::table('penjagas')->where('user_id', $id)->delete();
                DB::table('peminjams')->where('user_id', $id)->delete();
            }

            return redirect('/datauser')->with('success', 'Data User berhasil diperbarui dan disinkronkan!');
        });
    }

    public function destroy($id)
    {
        if (session('id') > 0) {
            $target = DB::table('users')->where('id', $id)->first();
            if (!$target) {
                return redirect('/datauser')->with('error', 'User tidak ditemukan.');
            }

            $superAdminLevelId = $this->superAdminLevelId();
            if (!$this->actorIsSuperAdmin() && $superAdminLevelId && (int) $target->level_id === (int) $superAdminLevelId) {
                return redirect('/datauser')->with('error', 'Akun Super Admin tidak bisa dihapus oleh role ini.');
            }

            DB::table('users')->where('id', $id)->update([
                'deleted_at' => now(),
                'deleted_by' => session('id')
            ]);

            DB::table('edit_histories')->insert([
                'table_name' => 'users',
                'row_id' => $id,
                'action_type' => 'delete',
                'perubahan' => 'User dihapus (Soft Delete)',
                'edited_by' => session('id'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect('/datauser')->with('success', 'User berhasil dihapus (Soft Delete)!');
        } else {
            return view('404');
        }
    }
}
