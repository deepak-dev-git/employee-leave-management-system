<?php

namespace App\Http\Requests;

use App\Enums\LeaveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Leave;
use App\Helpers\LeaveOverlapChecker;
use Illuminate\Validation\Rule;
use App\Enums\LeaveType;

class UpdateLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => ['required', 'string', Rule::in(array_column(LeaveStatus::cases(), 'value'))],
            'admin_remarks' => ['nullable', 'string', 'required_if:status,' . LeaveStatus::REJECTED->value],
        ];
    }
    public function messages()
    {
        return [
            'status.required' => 'Please select a leave status.',
            'status.in' => 'Invalid leave status selected.',
            'admin_remarks.required_if' => 'If status is Rejected then admin Remarks is required.',
        ];
    }
}
