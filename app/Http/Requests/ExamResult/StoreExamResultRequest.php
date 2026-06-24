<?php

namespace App\Http\Requests\ExamResult;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\ExamSession;

class StoreExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $session = ExamSession::find($this->exam_session_id);
    
        $max = $session?->module_max_score ?? 100;
    
        return [
    
            'exam_session_id' => [
                'required',
                'exists:exam_sessions,id'
            ],
    
            'table_number' => [
                'required',
                'string',
                'max:50'
            ],
    
            'full_name' => [
                'required',
                'string',
                'max:255'
            ],
    
            'lesen' => [
                'required',
                'numeric',
                'min:0',
                "max:$max"
            ],
    
            'hoeren' => [
                'required',
                'numeric',
                'min:0',
                "max:$max"
            ],
    
            'schreiben' => [
                'required',
                'numeric',
                'min:0',
                "max:$max"
            ],
    
            'sprechen' => [
                'required',
                'numeric',
                'min:0',
                "max:$max"
            ],
    
        ];
    }

    public function messages(): array
    {
        return [

            'exam_session_id.required' =>
                'Veuillez sélectionner une session.',

            'exam_session_id.exists' =>
                'La session sélectionnée est invalide.',

            'table_number.required' =>
                'Le numéro de table est obligatoire.',

            'full_name.required' =>
                'Le nom du candidat est obligatoire.',

            'lesen.max' =>
                'La note de Lesen dépasse la note maximale autorisée.',

            'hoeren.max' =>
                'La note de Hören dépasse la note maximale autorisée.',

            'schreiben.max' =>
                'La note de Schreiben dépasse la note maximale autorisée.',

            'sprechen.max' =>
                'La note de Sprechen dépasse la note maximale autorisée.',
        ];
    }
}