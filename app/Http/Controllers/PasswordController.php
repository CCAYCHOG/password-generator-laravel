<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function index()
    {
        //Ya no usamos $password en la vista,
        //así que puedes enviarlo como null o quitarlo si quieres.
        $password = null;
        return view('password.index', compact('password'));
    }

    public function generate(Request $request)
    {
        //Como el slider ya limita entre 4 y 64, podemos mantener la validación,
        //pero si falla es mejor devolver JSON.
        $request->validate([
            'length' => 'required|integer|min:4|max:64'
        ]);

        $length         = (int) $request->input('length', 12);
        $includeUpper   = $request->boolean('include_upper', false);
        $includeLower   = $request->boolean('include_lower', false);
        $includeNumbers = $request->boolean('include_numbers', false);
        $includeSymbols = $request->boolean('include_symbols', false);

        $chars = '';

        if ($includeLower) {
            $chars .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($includeUpper) {
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($includeNumbers) {
            $chars .= '0123456789';
        }
        if ($includeSymbols) {
            $chars .= '!@#$%^&*()-_=+[]{}|;:,.<>?';
        }

        //Aquí es donde antes usabas back()->withErrors(...)
        if ($chars === '') {
            return response()->json([
                'message' => 'Debe seleccionar al menos un tipo de carácter.'
            ], 422); // 422 = error de validación
        }

        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }

        //Respuesta para AJAX
        return response()->json([
            'password' => $password
        ]);
    }
}

