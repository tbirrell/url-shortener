<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShortUrl;

class ShortUrlsController extends Controller
{
    public function click(Request $request, $shortCode)
    {
        $shortUrl = ShortUrl::ofCode($shortCode);

        if (!$shortUrl) {
            return abort(404);
        }

        return redirect($shortUrl->destination);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'destination' => 'required|url|max:2048'
        ]);

        return response(ShortUrl::create($validatedData)->code, 201);
    }

    public function destroy(Request $request, $shortCode)
    {
        optional(ShortUrl::ofCode($shortCode))->delete();

        return response()->noContent();
    }
}
