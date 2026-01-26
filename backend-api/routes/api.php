<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CredentialController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ==========================================
// 1. RUTAS PÚBLICAS (Login y Registro)
// ==========================================

// LOGIN
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
});

// REGISTRO
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return response()->json(['message' => 'Usuario registrado']);
});

// ==========================================
// 2. RUTAS PRIVADAS (Requieren Token)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Rutas de Credenciales
    Route::get('/credentials', [CredentialController::class, 'index']);
    Route::post('/credentials', [CredentialController::class, 'store']);
    Route::delete('/credentials/{id}', [CredentialController::class, 'destroy']);
    
    // NUEVA RUTA: REVELAR CONTRASEÑA
    Route::get('/credentials/{id}/reveal', [CredentialController::class, 'reveal']);
    
    // Logout
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    });
});
