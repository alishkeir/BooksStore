<?php

namespace App\Http\Controllers;

use Alomgyar\Settings\SettingsMetaData;
use App\Http\Resources\MetadataResource;
use Illuminate\Http\Request;

class SettingsApiController extends Controller
{
    public function metadata(Request $request, $store)
    {
        $page = $request->get('pageUrl');
        $metadata = SettingsMetaData::where('section', strtolower($store))->where('page', $page)->get();
        $metadataResource = MetadataResource::collection($metadata);

        return response()->json($metadataResource);
    }
}
