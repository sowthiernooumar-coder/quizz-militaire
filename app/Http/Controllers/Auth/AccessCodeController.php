<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccessCodeController extends Controller
{
    /**
     * Vérifie le code d'accès saisi dans la modale d'inscription.
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $accessCodes = config('access_codes');

        $code = $request->string('code')->toString();

        if (! array_key_exists($code, $accessCodes)) {
            return response()->json([
                'valid' => false,
                'message' => "Code d'accès invalide.",
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'role' => $accessCodes[$code],
        ]);
    }
}
