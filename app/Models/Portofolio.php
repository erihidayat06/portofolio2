<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Mengubah string JSON menjadi array secara otomatis
    protected $casts = [
        'bahasa_id' => 'array',
        'framework_id' => 'array',
    ];

    // Relasi untuk mengambil data Bahasa
    public function bahasas()
    {
        // Kita menggunakan whereIn karena data disimpan sebagai array ID
        return Bahasa::whereIn('id', $this->bahasa_id ?? [])->get();
    }

    // Relasi untuk mengambil data Framework
    public function frameworks()
    {
        return Framework::whereIn('id', $this->framework_id ?? [])->get();
    }
}
