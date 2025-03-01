<?php

namespace App\Http\Controllers\Setting;

use App\FileUploadTrait;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $title          = 'Setting';
    private $subtitle       = 'Profil Saya';
    private $indexView      = 'pages.setting.profile.profile';
    private $linkIndex      = 'setting.profil.index';

    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title'         => $this->title,
            'subtitle'      => $this->subtitle,
            'link'          => route($this->linkIndex),
            'profil'        => User::findOrFail(Auth::user()->id)
        ];

        return view($this->indexView, $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateData(Request $request, string $id)
    {
        // Validasi data
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users,username,' . $id,
            'email'         => 'required|email|unique:users,email,' . $id,
            'telp'          => 'required|string|max:15',
            'jk'            => 'required|alpha|uppercase|in:L,P',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // Validasi gambar
        ]);

        try {
            $user = User::findOrFail($id);

            $foto = $this->uploadImage($request, 'foto', $request->old_foto, 'foto_user');

            // Update data user
            $user->name         = $validated['name'];
            $user->username     = $validated['username'];
            $user->email        = $validated['email'];
            $user->jk           = $validated['jk'];
            $user->telp         = $validated['telp'];
            $user->foto         = !empty($foto) ? $foto : $request->old_foto;

            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui profil.' . $e,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
