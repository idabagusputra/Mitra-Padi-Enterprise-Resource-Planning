<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class JPGR2Controller extends Controller
{
    private $r2Client;
    private $bucketName = 'mitra-padi'; // Sesuaikan dengan nama bucket kamu

    public function __construct()
    {
        $this->r2Client = new S3Client([
            'version' => 'latest',
            'region' => 'auto',
            'endpoint' => 'https://c9961806b72189a4d763edfd8dc0e55f.r2.cloudflarestorage.com',
            'credentials' => [
                'key' => env('R2_ACCESS_KEY'),
                'secret' => env('R2_SECRET_KEY'),
            ]
        ]);
    }

    /**
     * 1️⃣ Endpoint untuk mendapatkan Presigned URL (digunakan di Frontend)
     */
    public function getPresignedUrl(Request $request)
    {
        try {
            $fileName = 'receipts/receipt_' . uniqid() . '.jpg'; // Nama file unik
            $command = $this->r2Client->getCommand('PutObject', [
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
                'ContentType' => 'image/jpeg',
                'ACL' => 'public-read', // Izinkan akses publik
            ]);

            $presignedUrl = $this->r2Client->createPresignedRequest($command, '+10 minutes');

            return response()->json([
                'url' => (string) $presignedUrl->getUri(),
                'fileName' => $fileName
            ]);
        } catch (S3Exception $e) {
            return response()->json(['error' => 'Failed to generate presigned URL'], 500);
        }
    }

    /**
     * 2️⃣ Upload ke R2 langsung (Opsional, jika tidak pakai presigned URL)
     */
    public function uploadToR2(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        try {
            $file = $request->file('image');
            $fileName = 'receipts/' . uniqid() . '.' . $file->getClientOriginalExtension();

            $result = $this->r2Client->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
                'Body' => fopen($file->getPathname(), 'r'),
                'ContentType' => $file->getMimeType(),
                'ACL' => 'public-read'
            ]);

            return response()->json([
                'url' => "https://pub-b2576acededb43e08e7292257cd6a4c8.r2.dev/{$fileName}"
            ]);
        } catch (S3Exception $e) {
            return response()->json(['error' => 'Failed to upload file'], 500);
        }
    }
}
