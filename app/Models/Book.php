<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    public function tampil($table)
    {
        return DB::table($table)->get();
    }

    public function checkData($table, $where)
    {
        return DB::table($table)->where($where)->first();
    }

    public function insertData($table, $data)
    {
        return DB::table($table)->insert($data);
    }

    public function getWhere($table, $where)
    {
        return DB::table($table)->where($where)->first();
    }

    public function updateData($table, $where, $data)
    {
        return DB::table($table)->where($where)->update($data);
    }
    
    public function deleteData($table, $where)
    {
        return DB::table($table)->where($where)->delete();
    }

    public function join($table1, $table2, $table3, $on)
    {
        return DB::table($table1)
            ->join($table2, $on[0], '=', $on[1])
            ->join($table3, $on[2], '=', $on[3])
            ->get();
    }
}

