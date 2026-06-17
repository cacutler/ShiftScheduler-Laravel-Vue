<?php
namespace App\Http\Requests;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\UserStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserStoreRequest extends FormRequest {
    use PasswordValidationRules, ProfileValidationRules;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return $this->user()->can("create", User::class);
    }
    /**
     * Get the validation rules that apply to the request.
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            ...$this->profileRules(),
            "username" => ["required", "string", "max:255", Rule::unique(User::class)],
            "phone_number" => ["nullable", "string", "max:20"],
            "password" => $this->passwordRules(),
            "status" => ["nullable", Rule::enum(UserStatus::class)]
        ];
    }
}