<?php

// documents store
Route::post('file/store', [MyController::class,'storeDocument'])->name('document.store');
Route::post('file/store/remove', [MyController::class,'storeDocumentRemove'])->name('document.store.remove');
Route::post('file/store/', [MyController::class,'store'])->name('documents.store');