<?php

namespace App\Http\Requests\Auth\Registration;

use App\Http\Requests\BaseRequest;

class UserRegistrationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'last_name' => 'max:50',
            'email' => 'required|email|max:150',
            'password' => 'sometimes|required|min:8',
            // 'uuid' => 'required|uuid|exists:mobile_otp_verifications,id,verified,1',
            'mobile' => 'required',
            'terms_condition' => 'sometimes|required|boolean|in:1',
            'device_token'=>'sometimes|required',
            'login_by'=>'sometimes|required|in:android,ios',
            'oauth_token'=>'sometimes|required',
            'gender' => 'required|in:male,fe-male,non-bi,others',
            'dob' => 'sometimes|date_format:Y-m-d',

            'user_aged_details' => 'sometimes|array',
            'user_aged_details.participant' => 'required_with:user_aged_details|boolean',
            'user_aged_details.number' => 'required_with:user_aged_details|string|max:255',
            'user_aged_details.provider_name' => 'required_with:user_aged_details|string|max:255',
            'user_aged_details.care_package' => 'required_with:user_aged_details|in:LEVEL_1,LEVEL_2,LEVEL_3,LEVEL_4',
            'user_aged_details.case_manager_name' => 'required_with:user_aged_details|string|max:255',
            'user_aged_details.case_manager_phone' => 'required_with:user_aged_details|string|max:20',
            'user_aged_details.case_manager_email' => 'required_with:user_aged_details|email|max:255',
            'user_aged_details.chsp_support' => 'required_with:user_aged_details|boolean',
            'user_aged_details.health_awareness' => 'required_with:user_aged_details|boolean',
            'user_aged_details.health_details' => 'nullable|string',
            'user_aged_details.other' => 'nullable|string',

            'user_ndis_details' => 'sometimes|array',
            'user_ndis_details.participant' => 'required_with:user_ndis_details|boolean',
            'user_ndis_details.number' => 'required_with:user_ndis_details|string|max:255',
            'user_ndis_details.type' => 'required_with:user_ndis_details|in:AGENCY,SELF,PLAN',
            'user_ndis_details.plan_manager_name' => 'required_with:user_ndis_details|string|max:255',
            'user_ndis_details.plan_manager_phone' => 'required_with:user_ndis_details|string|max:20',
            'user_ndis_details.plan_manager_email' => 'required_with:user_ndis_details|email|max:255',
            'user_ndis_details.health_awareness' => 'required_with:user_ndis_details|boolean',
            'user_ndis_details.health_details' => 'nullable|string',
            'user_ndis_details.other' => 'nullable|string',

            'user_niisq_details' => 'sometimes|array',
            'user_niisq_details.participant' => 'required_with:user_niisq_details|boolean',
            'user_niisq_details.number' => 'required_with:user_niisq_details|string|max:255',
            'user_niisq_details.plan_manager_name' => 'required_with:user_niisq_details|string|max:255',
            'user_niisq_details.plan_manager_phone' => 'required_with:user_niisq_details|string|max:20',
            'user_niisq_details.plan_manager_email' => 'required_with:user_niisq_details|email|max:255',
            'user_niisq_details.health_awareness' => 'required_with:user_niisq_details|boolean',
            'user_niisq_details.health_details' => 'nullable|string',
            'user_niisq_details.other' => 'nullable|string',

            'user_private_details' => 'sometimes|array',
            'user_private_details.health_awareness' => 'required_with:user_private_details|boolean',
            'user_private_details.health_details' => 'nullable|string',
            'user_private_details.other' => 'nullable|string',
        ];
    }
}
