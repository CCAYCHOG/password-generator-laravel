<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function index()
    {
        $password = null;
        return view('password.index', compact('password'));
    }

    public function generate(Request $request)
    {
        $request->validate(['length' => 'required|integer|min:4|max:64']);
        $length = $request->input('length', 12);
        $includeUpper = $request->boolean('include_upper', true);
        $includeLower = $request->boolean('include_lower', true);
        $includeNumbers = $request->boolean('include_numbers', true);
        $includeSymbols = $request->boolean('include_symbols', false);

        $chars = '';

        if($includeLower){
            $chars .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if($includeUpper){
            $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if($includeNumbers){
            $chars .= '0123456789';
        }
        if($includeSymbols){
            $chars .= '!@#$%^&*()-_=+[]{}|;:,.<>?';
        }

        if($chars === ''){
            return back()->withErrors('Debe seleccionar al menos un tipo de carácter.')->withInput();
        }

        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++){
            $password .= $chars[random_int(0, $maxIndex)];
        }

        return view('password.index', compact('password'))->with('success', 'Contraseña generada con éxito.');
    }
}
