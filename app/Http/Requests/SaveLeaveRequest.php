<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Leave;
use App\Helpers\LeaveOverlapChecker;
use Illuminate\Validation\Rule;
use App\Enums\LeaveType;
use Carbon\Carbon;

class SaveLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(array_column(LeaveType::cases(), 'value')),
            ],
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'request_reason' => ['nullable', 'string', 'required_if:request_reason,' . LeaveType::OTHERS->value],
            'is_one_day' => 'sometimes',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $userId = auth()->id();
            $startDate = $this->input('start_date');
            $isOneDay = $this->input('is_one_day') ?? false;
            $endDate = $isOneDay ? $startDate : $this->input('end_date');
            if (!$isOneDay && !$endDate) {
                $validator->errors()->add('end_date', 'End date is required for more than 1day leave.');
                return;
            }
            if (LeaveOverlapChecker::checkOverLapping($userId, $startDate, $endDate)) {
                $validator->errors()->add(
                    'start_date',
                    'The selected date range overlaping with a existing leave. Please choose different date.'
                );
            }
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $days = $start->diffInDays($end) + 1;

            if ($days > 30) {
                $validator->errors()->add('end_date', 'Leave duration cannot exceed 30 days.');
            }
        });
    }
    public function messages()
    {
        return [
            'type.required' => 'Please select a leave type.',
            'type.in' => 'Invalid leave type selected.',
            'request_reason.required_if' => 'If Leave type is Others then Request Reason is required.',
        ];
    }
}
