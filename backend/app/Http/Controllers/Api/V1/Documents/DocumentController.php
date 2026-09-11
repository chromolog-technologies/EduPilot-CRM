<?php

namespace App\Http\Controllers\Api\V1\Documents;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DocumentResource;
use App\Models\DocumentRequirement;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Services\Document\DocumentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct(private DocumentService $documentService) {}

    public function requirements(Request $request)
    {
        return ApiResponse::success($this->documentService->requirements($request->user()), 'Document requirements retrieved successfully.');
    }

    public function storeRequirement(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
        ]);

        return ApiResponse::created($this->documentService->createRequirement($request->user(), $data), 'Document requirement created successfully.');
    }

    public function updateRequirement(Request $request, DocumentRequirement $requirement)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_required' => ['nullable', 'boolean'],
            'status' => ['nullable', 'string'],
        ]);

        return ApiResponse::success($this->documentService->updateRequirement($requirement, $data), 'Document requirement updated successfully.');
    }

    public function destroyRequirement(DocumentRequirement $requirement)
    {
        $this->documentService->deleteRequirement($requirement);

        return ApiResponse::success(null, 'Document requirement deleted successfully.');
    }

    public function store(Request $request, Student $student)
    {
        $this->authorize('create', StudentDocument::class);
        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'document_requirement_id' => ['nullable', 'integer'],
        ]);

        $document = $this->documentService->upload(
            $request->user(),
            $student,
            $request->file('file'),
            $request->integer('document_requirement_id') ?: null
        );

        return ApiResponse::created((new DocumentResource($document))->resolve(), 'Document uploaded successfully.');
    }

    public function show(Student $student, StudentDocument $document)
    {
        $this->authorize('view', $document);

        return ApiResponse::success((new DocumentResource($document->load('requirement')))->resolve(), 'Document retrieved successfully.');
    }

    public function destroy(Request $request, Student $student, StudentDocument $document)
    {
        $this->authorize('delete', $document);
        $this->documentService->delete($request->user(), $document);

        return ApiResponse::success(null, 'Document deleted successfully.');
    }

    public function download(Request $request, StudentDocument $document)
    {
        if (! $request->hasValidSignature()) {
            return ApiResponse::error('Invalid or expired download link.', 403);
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }
}
