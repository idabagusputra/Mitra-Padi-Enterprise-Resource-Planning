<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;


class JPGR2Controller extends Controller

{
    public function getPresignedUrl(Request $request)
    {
        $fileName = 'receipts/' . uniqid('receipt_') . '.jpg'; // Nama file unik

        $r2Client = new S3Client([
            'version' => 'latest',
            'region' => 'auto',
            'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
            'credentials' => [
                'key' => env('R2_ACCESS_KEY_ID'),
                'secret' => env('R2_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Generate Presigned URL (Berlaku 10 Menit)
        $cmd = $r2Client->getCommand('PutObject', [
            'Bucket' => 'mitra-padi',
            'Key' => $fileName,
            'ContentType' => 'image/jpeg',
            'ACL' => 'public-read',
        ]);

        $request = $r2Client->createPresignedRequest($cmd, '+10 minutes');

        return response()->json([
            'url' => (string) $request->getUri(),
            'fileName' => $fileName
        ]);
    }
}
