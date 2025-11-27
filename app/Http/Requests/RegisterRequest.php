<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $role = $this->input('role');

        $rules = [
            'username' => 'required|string|max:50|unique:dataadmin,username',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,guru,siswa',
        ];

        if ($role === 'guru') {
            $rules['nama_guru'] = 'required|string|max:100';
            $rules['mata_pelajaran'] = 'required|string|max:100';
        } elseif ($role === 'siswa') {
            $rules['nama_siswa'] = 'required|string|max:100';
            $rules['tinggi_badan'] = 'required|numeric|min:1|max:300';
            $rules['berat_badan'] = 'required|numeric|min:1|max:500';
        }

        return $rules;
    }
}


