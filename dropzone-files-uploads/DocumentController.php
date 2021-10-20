<?php

public function storeDocument(Request $request): JsonResponse
{
    $file = $request->file('file');
    $uniqueId = uniqid() . '_';
    $fileName = 'documents' . DIRECTORY_SEPARATOR .$uniqueId. trim($file->getClientOriginalName());
    $content = file_get_contents($file);
    Storage::disk()->put($fileName, $content);
    Storage::disk()->setVisibility($fileName, 'public');
    //create temp upload
    //TempUpload::create(['file_uri' => $fileName]);
    return response()->json([
        'name'          => $fileName,
        'original_name' => $file->getClientOriginalName(),
    ]);
}

public function storeDocumentRemove(Request $request): JsonResponse
{
    $fileURI = $request->get('file_uri');
    try {
        Storage::disk()->delete($fileURI);
    }catch (\Exception $e) {
        abort(403);
    }
    //delete temp upload
    //TempUpload::where('file_uri',$fileURI)->delete();
    return response()->json([
        'status' => 'success',
    ]);
}

public function store(Request $request): RedirectResponse
{
    foreach ($request->input('documents', []) as $file) {
        //delete temp upload
        TempUpload::where('file_uri',$file)->delete();
    }
    return redirect()->back();
}