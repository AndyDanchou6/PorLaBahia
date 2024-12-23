<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function handleForm(Request $request)
    {
        $validatedData = $request->validate([
            'contact_name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:5',
            'contact_no' => 'required|string|max:11',
            'email' => 'nullable|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create($validatedData);

        return response()->json([
            'message' => 'Form submitted successfully!',
            'data' => $message,
        ]);
    }
}