<?php

namespace App\Services\Document;

use App\Enums\DocumentStatus;
use App\Models\DocumentRequirement;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\User;
use App\Services\Audit\AuditLogger;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function __construct(private AuditLogger $auditLogger) {}

    public function requirements(User $user)
    {
        return DocumentRequirement::query()->latest()->get();
    }

    public function createRequirement(User $user, array $data): DocumentRequirement
    {
        return DocumentRequirement::create([
            'organization_id' => $user->organization_id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_required' => $data['is_required'] ?? true,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    public function updateRequirement(DocumentRequirement $requirement, array $data): DocumentRequirement
    {
        $requirement->fill($data)->save();

        return $requirement;
    }

    public function deleteRequirement(DocumentRequirement $requirement): void
    {
        $requirement->delete();
    }

    public function upload(User $user, Student $student, UploadedFile $file, ?int $requirementId = null): StudentDocument
    {
        $path = $file->store(
            "organizations/{$user->organization_id}/students/{$student->id}/documents",
            'local'
        );

        $document = StudentDocument::create([
            'organization_id' => $user->organization_id,
            'student_id' => $student->id,
            'document_requirement_id' => $requirementId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'status' => DocumentStatus::Uploaded,
            'uploaded_by' => $user->id,
            'uploaded_at' => now(),
        ]);

        $this->auditLogger->log('document.uploaded', $document, null, $document->toArray(), $user);

        return $document->load('requirement');
    }

    public function delete(User $user, StudentDocument $document): void
    {
        $this->auditLogger->log('document.deleted', $document, $document->toArray(), null, $user);
        Storage::disk('local')->delete($document->file_path);
        $document->delete();
    }
}
