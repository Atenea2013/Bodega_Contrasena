<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credential;
use Illuminate\Support\Facades\Crypt;

class CredentialController extends Controller
{
    // 1. LISTAR (Solo devuelve lo cifrado)
    public function index()
    {
        return response()->json(
            Credential::where('user_id', auth()->id())->get()
        );
    }

    // 2. GUARDAR (Cifra antes de guardar)
    public function store(Request $request)
    {
        $request->validate([
            'site_name' => 'required',
            'account_user' => 'required',
            'password' => 'required',
        ]);

        $credential = Credential::create([
            'site_name' => $request->site_name,
            'account_user' => $request->account_user,
            'password_encrypted' => Crypt::encryptString($request->password),
            'user_id' => auth()->id(),
        ]);

        return response()->json($credential, 201);
    }

    // 3. REVELAR (NUEVO: Desencripta una sola contraseña)
    public function reveal($id)
    {
        $credential = Credential::where('user_id', auth()->id())->findOrFail($id);
        
        // Aquí ocurre la magia inversa: Desencriptar
        return response()->json([
            'plain_password' => Crypt::decryptString($credential->password_encrypted)
        ]);
    }

    // 4. ELIMINAR
    public function destroy($id)
    {
        $credential = Credential::where('user_id', auth()->id())->findOrFail($id);
        $credential->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
