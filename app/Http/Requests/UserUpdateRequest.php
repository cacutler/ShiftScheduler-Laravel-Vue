<?php
namespace App\Http\Requests;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\UserStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserUpdateRequest extends FormRequest {
    use ProfileValidationRules;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        $userId = $this->route("user")->id;
        return [
            ...$this->profileRules($userId),
            "username" => ["required", "string", "max:255", Rule::unique(User::class)->ignore($userId)],
            "phone_number" => ["nullable", "string", "max:20"],
            "status" => ["nullable", Rule::enum(UserStatus::class)]
        ];
    }
}