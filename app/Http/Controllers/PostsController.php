<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    // Validasi Accept Header
    private function getAcceptFormat(Request $request)
    {
        $accept = $request->header('Accept');

        if (!$accept) {
            return null;
        }

        $format = strtolower(trim(explode(',', $accept)[0]));

        if (in_array($format, ['application/json', 'application/xml'])) {
            return $format;
        }

        return null;
    }

    // Validasi Content-Type Header
    private function validateContentType(Request $request)
    {
        $contentType = strtolower(
            trim(explode(';', $request->header('Content-Type', ''))[0])
        );

        return $contentType === 'application/json';
    }

    // Mengubah array menjadi XML
    private function arrayToXml($data, \SimpleXMLElement $xml)
    {
        foreach ($data as $key => $value) {
            $key = is_numeric($key)
                ? 'item'
                : preg_replace('/[^a-zA-Z0-9_.-]/', '_', $key);

            if (is_array($value)) {
                $child = $xml->addChild($key);
                $this->arrayToXml($value, $child);
            } else {
                $xml->addChild(
                    $key,
                    htmlspecialchars((string) ($value ?? ''), ENT_XML1, 'UTF-8')
                );
            }
        }
    }

    // Membuat response JSON atau XML
    private function respond($data, $status, $format)
    {
        if ($format === 'application/json') {
            return response()->json($data, $status);
        }

        $data = json_decode(json_encode($data), true);

        $xml = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?><response/>'
        );

        $this->arrayToXml($data, $xml);

        return response($xml->asXML(), $status)
            ->header('Content-Type', 'application/xml');
    }

    // Read All
    public function index(Request $request)
    {
        $format = $this->getAcceptFormat($request);

        if (!$format) {
            return response('Not Acceptable!', 406);
        }

        $data = [
            'message' => 'Daftar posts',
            'results' => Post::orderBy('id', 'DESC')->paginate(10)
        ];

        return $this->respond($data, 200, $format);
    }

    // Create
    public function store(Request $request)
    {
        $format = $this->getAcceptFormat($request);

        if (!$format) {
            return response('Not Acceptable!', 406);
        }

        if (!$this->validateContentType($request)) {
            return response('Unsupported Media Type!', 415);
        }

        $post = Post::create($request->only([
            'title',
            'content',
            'status',
            'user_id'
        ]));

        return $this->respond([
            'message' => 'Post berhasil ditambahkan',
            'results' => $post
        ], 201, $format);
    }

    // Read Detail
    public function show(Request $request, $postId)
    {
        $format = $this->getAcceptFormat($request);

        if (!$format) {
            return response('Not Acceptable!', 406);
        }

        $post = Post::find($postId);

        if (!$post) {
            return $this->respond([
                'message' => 'Post tidak ditemukan'
            ], 404, $format);
        }

        return $this->respond([
            'message' => 'Detail post',
            'results' => $post
        ], 200, $format);
    }

    // Update
    public function update(Request $request, $postId)
    {
        $format = $this->getAcceptFormat($request);

        if (!$format) {
            return response('Not Acceptable!', 406);
        }

        if (!$this->validateContentType($request)) {
            return response('Unsupported Media Type!', 415);
        }

        $post = Post::find($postId);

        if (!$post) {
            return $this->respond([
                'message' => 'Post tidak ditemukan'
            ], 404, $format);
        }

        $post->update($request->only([
            'title',
            'content',
            'status',
            'user_id'
        ]));

        return $this->respond([
            'message' => 'Post berhasil diubah',
            'results' => $post
        ], 200, $format);
    }

    // Delete
    public function destroy(Request $request, $postId)
    {
        $format = $this->getAcceptFormat($request);

        if (!$format) {
            return response('Not Acceptable!', 406);
        }

        $post = Post::find($postId);

        if (!$post) {
            return $this->respond([
                'message' => 'Post tidak ditemukan'
            ], 404, $format);
        }

        $post->delete();

        return $this->respond([
            'message' => 'Post berhasil dihapus'
        ], 200, $format);
    }
}
