<?php

declare(strict_types=1);

namespace ARKEcosystem\UserInterface\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

final class WysiwygControlller extends Controller
{
    public function getTwitterEmbedCode(Request $request): string
    {
        $id = $request->get('id');

        if (!$id) {
            abort('400', 'Missing id');
        }

        $url = 'https://twitter.com/'.$request->get('id');

        return Cache::rememberForever(md5($url), fn () => Http::get('https://publish.twitter.com/oembed', [
            'url'         => $url,
            'hide_thread' => 1,
            'hide_media'  => 0,
            'omit_script' => true,
            'dnt'         => true,
            'limit'       => 20,
            'chrome'      => 'nofooter',
        ])->json()['html']);
    }

    public function uploadImage(Request $request): array
    {
        $this->validate($request, [
            'image' => 'required|image',
        ]);

        $file = $request->file('image');

        $path = $file->storePubliclyAs(
            'wysiwyg',
            $file->hashName(),
            'public'
        );

        return ['url' => asset($path)];
    }
}
