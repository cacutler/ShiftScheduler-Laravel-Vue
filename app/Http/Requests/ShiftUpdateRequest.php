<?php
namespace App\Http\Requests;
use App\Concerns\ShiftValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
class ShiftUpdateRequest extends FormRequest {
    use ShiftValidationRules;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        $shift = $this->route('shift');
        return $this->user()->can('update', $shift);
    }
    /**
     * Get the validation rules that apply to the request.
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [$this->shiftUpdateRules()];
    }
}