@extends('admin.layouts.app')
@section('title', 'Main page')

@section('content')

    <!-- Start Page content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header with-border">
                            Register At : {{ $results->created_at->setTimezone('Australia/Sydney')->format('Y-m-d H:i:s') }}
                            <a href="{{ url('users') }}">
                                <button class="btn btn-danger btn-sm pull-right" type="submit">
                                    <i class="mdi mdi-keyboard-backspace mr-2"></i>
                                    @lang('view_pages.back')
                                </button>
                            </a>
                        </div>

                        <div class="col-sm-12">

                            <form method="post" class="form-horizontal"
                                  action="{{ route('admin.update.user',['user' => $results->id]) }}"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">@lang('view_pages.name') <span
                                                        class="text-danger">*</span></label>
                                            <input class="form-control" type="text" id="name" name="name"
                                                   value="{{ $results->name, old('name') }}" required=""
                                                   placeholder="@lang('view_pages.enter_name')">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>

                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Surname <span
                                                        class="text-danger">*</span></label>
                                            <input class="form-control" type="text" id="name" name="surname"
                                                   value="{{ $results->surname, old('surname') }}" required=""
                                                   placeholder="@lang('view_pages.last_name')">
                                            <span class="text-danger">{{ $errors->first('surname') }}</span>

                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Dob <span
                                                        class="text-danger">*</span></label>
                                            <input class="form-control" type="text" id="name" name="dob"
                                                   value="{{ $results->dob, old('dob') }}" required=""
                                                   placeholder="">
                                            <span class="text-danger">{{ $errors->first('dob') }}</span>

                                        </div>
                                    </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">@lang('view_pages.gender') <span
                                                            class="text-danger">*</span></label>
                                                <select name="gender" id="gender" class="form-control" required>
                                                    <option value="" selected
                                                            disabled>@lang('view_pages.select')</option>
                                                    <option value="male" {{ $results->gender == 'male' ? 'selected' : '' }}>@lang('view_pages.male')</option>
                                                    <option value="fe-male" {{ $results->gender == 'fe-male'  ? 'selected' : '' }}>@lang('view_pages.female')</option>
                                                    <option value="others" {{ $results->gender == 'others' ? 'selected' : '' }}>@lang('view_pages.others')</option>
                                                </select>
                                                <span class="text-danger">{{ $errors->first('gender') }}</span>
                                            </div>
                                        </div>

                                    <div class="col-sm-6">
                                        @if(env('APP_FOR')=='demo')
                                            <div class="form-group">
                                                <label for="email">@lang('view_pages.email') <span
                                                            class="text-danger">*</span></label>
                                                <input class="form-control" type="email" id="email" name="email"
                                                       value="{{ old('email', "******************") }}" required=""
                                                       placeholder="@lang('view_pages.enter_email')">
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="email">@lang('view_pages.email') <span
                                                            class="text-danger">*</span></label>
                                                <input class="form-control" type="email" id="email" name="email"
                                                       value="{{ old('email', $results->email) }}" required=""
                                                       placeholder="@lang('view_pages.enter_email')">
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="address">@lang('view_pages.address')</label>
                                            <input class="form-control" type="text" id="address" name="address"
                                                value="{{ old('address', $results->address) }}" required=""
                                                placeholder="@lang('view_pages.enter_address')">
                                            <span class="text-danger">{{ $errors->first('address') }}</span>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="gender">@lang('view_pages.gender')
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="gender" id="gender" class="form-control" required>
                                                <option value="">@lang('view_pages.select_gender')</option>
                                                <option value="{{ $results->gender }}"
                                                    {{ old('gender', $results->gender) == $results->gender ? 'selected' : '' }}>
                                                    {{ $results->gender }}</option>
                                                <option value='male' {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                    @lang('view_pages.male')</option>
                                                <option value='female' {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    @lang('view_pages.female')</option>
                                                <option value='others' {{ old('gender') == 'others' ? 'selected' : '' }}>
                                                    @lang('view_pages.others')</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('gender') }}</span>

                                        </div>
                                    </div>
                                </div> --}}

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="country">@lang('view_pages.select_country')
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="country" id="country" class="form-control">
                                                <option value="">@lang('view_pages.select_country')</option>
                                                @foreach ($countries as $key => $country)
                                                    <option value="{{ $country->id }}"
                                                            {{ old('country', $results->country) == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger">{{ $errors->first('country') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        @if(env('APP_FOR')=='demo')
                                            <div class="form-group">
                                                <label for="name">@lang('view_pages.mobile') <span
                                                            class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="mobile" name="mobile"
                                                       value="{{ old('mobile', "********") }}" required=""
                                                       placeholder="@lang('view_pages.enter_mobile')">
                                                <span class="text-danger">{{ $errors->first('mobile') }}</span>

                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="name">@lang('view_pages.mobile') <span
                                                            class="text-danger">*</span></label>
                                                <input class="form-control" type="text" id="mobile" name="mobile"
                                                       value="{{ old('mobile', $results->mobile) }}" required=""
                                                       placeholder="@lang('view_pages.enter_mobile')">
                                                <span class="text-danger">{{ $errors->first('mobile') }}</span>

                                            </div>
                                        @endif
                                    </div>



                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="profile_picture">@lang('view_pages.profile')</label><br>
                                            <img class="user-image" id="blah"
                                                 src="{{asset( $results->user?->profile_picture) }}" height="100px" alt=" "><br>
                                            <input type="file" id="profile" onchange="readURL(this)"
                                                   name="profile_picture"
                                                   style="display:none">
                                            <button class="btn btn-primary btn-sm" type="button"
                                                    onclick="$('#profile').click()"
                                                    id="upload">@lang('view_pages.browse')</button>
                                            <button class="btn btn-danger btn-sm" type="button" id="remove_img"
                                                    style="display: none;">@lang('view_pages.remove')</button>
                                            <br>
                                            <span class="text-danger">{{ $errors->first('profile_picture') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="name">Address <span
                                                        class="text-danger">*</span></label>
                                            <input class="form-control" type="text" id="mobile" name="address"
                                                   value="{{ $results->address ?? old('address','') }}" required=""
                                                   placeholder="address">
                                            <span class="text-danger">{{ $errors->first('address') }}</span>

                                        </div>
                                    </div>
                                </div>


                                <div class="row g-3">
                                    <!-- Line Item for Base Item -->
                                    <div class="col-12">
                                        <label for="line_item_base" class="form-label">
                                            Line Item for Base Item
                                        </label>
                                        <input
                                                type="text"
                                                id="line_item_base"
                                                name="line_item_base"
                                                value="{{ $results->line_item_base }}"
                                                class="form-control"
                                                required
                                        />
                                    </div>

                                    <!-- Line Item for Time -->
                                    <div class="col-12">
                                        <label for="line_item_time" class="form-label">
                                            Line Item for Time
                                        </label>
                                        <input
                                                type="text"
                                                id="line_item_time"
                                                name="line_item_time"
                                                value="{{ $results->line_item_time }}"
                                                class="form-control"
                                                required
                                        />
                                    </div>

                                    <!-- Line Item for KM -->
                                    <div class="col-12">
                                        <label for="line_item_km" class="form-label">
                                            Line Item for KM
                                        </label>
                                        <input
                                                type="text"
                                                id="line_item_km"
                                                name="line_item_km"
                                                value="{{ $results->line_item_km }}"
                                                class="form-control"
                                                required
                                        />
                                    </div>
                                </div>

                                <div class="container-lg">
                                    <!-- NDIS Section -->
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <h3>NDIS</h3>
                                            <div class="mb-3">
                                                <label for="ndis-participant" class="form-label">Participant</label>
                                                <select id="ndis-participant" name="user_ndis_details[participant]"
                                                        class="form-select">
                                                    <option value="1" {{ $results->user?->userNdis?->participant == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userNdis?->participant == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-number" class="form-label">NDIS Number</label>
                                                <input type="text" id="ndis-number" name="user_ndis_details[number]"
                                                       class="form-control" maxlength="255"
                                                       value="{{ $results->user?->userNdis?->number }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-type" class="form-label">Type</label>
                                                <select id="ndis-type" name="user_ndis_details[type]"
                                                        class="form-select">
                                                    <option value="AGENCY" {{ $results->user?->userNdis?->type == 'AGENCY' ? 'selected' : '' }}>
                                                        Agency
                                                    </option>
                                                    <option value="SELF" {{ $results->user?->userNdis?->type == 'SELF' ? 'selected' : '' }}>
                                                        Self
                                                    </option>
                                                    <option value="PLAN" {{ $results->user?->userNdis?->type == 'PLAN' ? 'selected' : '' }}>
                                                        Plan
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-plan-manager-name" class="form-label">Plan Manager
                                                    Name</label>
                                                <input type="text" id="ndis-plan-manager-name"
                                                       name="user_ndis_details[plan_manager_name]" class="form-control"
                                                       maxlength="255"
                                                       value="{{ $results->user?->userNdis?->plan_manager_name }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-plan-manager-phone" class="form-label">Plan Manager
                                                    Phone</label>
                                                <input type="text" id="ndis-plan-manager-phone"
                                                       name="user_ndis_details[plan_manager_phone]" class="form-control"
                                                       maxlength="20"
                                                       value="{{ $results->user?->userNdis?->plan_manager_phone }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-plan-manager-email" class="form-label">Plan Manager
                                                    Email</label>
                                                <input type="email" id="ndis-plan-manager-email"
                                                       name="user_ndis_details[plan_manager_email]" class="form-control"
                                                       maxlength="255"
                                                       value="{{ $results->user?->userNdis?->plan_manager_email }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-health-awareness" class="form-label">Health
                                                    Awareness</label>
                                                <select id="ndis-health-awareness"
                                                        name="user_ndis_details[health_awareness]" class="form-select">
                                                    <option value="1" {{ $results->user?->userNdis?->health_awareness == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userNdis?->health_awareness == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-health-details" class="form-label">Health
                                                    Details</label>
                                                <textarea id="ndis-health-details"
                                                          name="user_ndis_details[health_details]"
                                                          class="form-control">{{ $results->user?->userNdis?->health_details }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="ndis-other" class="form-label">Other</label>
                                                <textarea id="ndis-other" name="user_ndis_details[other]"
                                                          class="form-control">{{ $results->user?->userNdis?->other }}</textarea>
                                            </div>
                                        </div>

                                        <!-- NIISQ Section -->
                                        <div class="col-6">
                                            <h3>NIISQ</h3>
                                            <div class="mb-3">
                                                <label for="niisq-participant" class="form-label">Participant</label>
                                                <select id="niisq-participant" name="user_niisq_details[participant]"
                                                        class="form-select">
                                                    <option value="1" {{ $results->user?->userNiisq?->participant == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userNiisq?->participant == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="niisq-number" class="form-label">NIISQ Number</label>
                                                <input type="text" id="niisq-number" name="user_niisq_details[number]"
                                                       class="form-control" maxlength="255"
                                                       value="{{ $results->user?->userNiisq?->number }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="niisq-plan-manager-name" class="form-label">Plan Manager
                                                    Name</label>
                                                <input type="text" id="niisq-plan-manager-name"
                                                       name="user_niisq_details[plan_manager_name]" class="form-control"
                                                       maxlength="255"
                                                       value="{{ $results->user?->userNiisq?->plan_manager_name }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="niisq-plan-manager-phone" class="form-label">Plan Manager
                                                    Phone</label>
                                                <input type="text" id="niisq-plan-manager-phone"
                                                       name="user_niisq_details[plan_manager_phone]"
                                                       class="form-control" maxlength="20"
                                                       value="{{ $results->user?->userNiisq?->plan_manager_phone }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="niisq-plan-manager-email" class="form-label">Plan Manager
                                                    Email</label>
                                                <input type="email" id="niisq-plan-manager-email"
                                                       name="user_niisq_details[plan_manager_email]"
                                                       class="form-control" maxlength="255"
                                                       value="{{ $results->user?->userNiisq?->plan_manager_email }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="niisq-health-awareness" class="form-label">Health
                                                    Awareness</label>
                                                <select id="niisq-health-awareness"
                                                        name="user_niisq_details[health_awareness]" class="form-select">
                                                    <option value="1" {{ $results->user?->userNiisq?->health_awareness == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userNiisq?->health_awareness == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="niisq-health-details" class="form-label">Health
                                                    Details</label>
                                                <textarea id="niisq-health-details"
                                                          name="user_niisq_details[health_details]"
                                                          class="form-control">{{ $results->user?->userNiisq?->health_details }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="niisq-other" class="form-label">Other</label>
                                                <textarea id="niisq-other" name="user_niisq_details[other]"
                                                          class="form-control">{{ $results->user?->userNiisq?->other }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <h3>Aged Care</h3>
                                            <div class="mb-3">
                                                <label for="aged-participant" class="form-label">Participant</label>
                                                <select id="aged-participant" name="user_aged_details[participant]"
                                                        class="form-select">
                                                    <option value="1" {{ $results->user?->userAgedCare?->participant == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userAgedCare?->participant == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-number" class="form-label">Aged Care Number</label>
                                                <input type="text" id="aged-number" name="user_aged_details[number]"
                                                       class="form-control" maxlength="255"
                                                       value="{{ $results->user?->userAgedCare?->number ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-provider-name" class="form-label">Provider Name</label>
                                                <input type="text" id="aged-provider-name"
                                                       name="user_aged_details[provider_name]" class="form-control"
                                                       maxlength="255"
                                                       value="{{ $results->user?->userAgedCare?->provider_name ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-care-package" class="form-label">Care Package</label>
                                                <select id="aged-care-package" name="user_aged_details[care_package]"
                                                        class="form-select">
                                                    <option value="LEVEL_1" {{ $results->user?->userAgedCare?->care_package == 'LEVEL_1' ? 'selected' : '' }}>
                                                        Level 1
                                                    </option>
                                                    <option value="LEVEL_2" {{ $results->user?->userAgedCare?->care_package == 'LEVEL_2' ? 'selected' : '' }}>
                                                        Level 2
                                                    </option>
                                                    <option value="LEVEL_3" {{ $results->user?->userAgedCare?->care_package == 'LEVEL_3' ? 'selected' : '' }}>
                                                        Level 3
                                                    </option>
                                                    <option value="LEVEL_4" {{ $results->user?->userAgedCare?->care_package == 'LEVEL_4' ? 'selected' : '' }}>
                                                        Level 4
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-case-manager-name" class="form-label">Case Manager
                                                    Name</label>
                                                <input type="text" id="aged-case-manager-name"
                                                       name="user_aged_details[case_manager_name]" class="form-control"
                                                       maxlength="255"
                                                       value="{{ $results->user?->userAgedCare?->case_manager_name ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-case-manager-phone" class="form-label">Case Manager
                                                    Phone</label>
                                                <input type="text" id="aged-case-manager-phone"
                                                       name="user_aged_details[case_manager_phone]" class="form-control"
                                                       maxlength="20"
                                                       value="{{ $results->user?->userAgedCare?->case_manager_phone ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-case-manager-email" class="form-label">Case Manager
                                                    Email</label>
                                                <input type="email" id="aged-case-manager-email"
                                                       name="user_aged_details[case_manager_email]" class="form-control"
                                                       maxlength="255"
                                                       value="{{ $results->user?->userAgedCare?->case_manager_email ?? '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-chsp-support" class="form-label">CHSP Support</label>
                                                <select id="aged-chsp-support" name="user_aged_details[chsp_support]"
                                                        class="form-select">
                                                    <option value="1" {{ $results->user?->userAgedCare?->chsp_support == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userAgedCare?->chsp_support == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-health-awareness" class="form-label">Health
                                                    Awareness</label>
                                                <select id="aged-health-awareness"
                                                        name="user_aged_details[health_awareness]" class="form-select">
                                                    <option value="1" {{ $results->user?->userAgedCare?->health_awareness == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userAgedCare?->health_awareness == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-health-details" class="form-label">Health
                                                    Details</label>
                                                <textarea id="aged-health-details"
                                                          name="user_aged_details[health_details]" class="form-control">
                                                    {{ $results->user?->userAgedCare?->health_details ?? '' }}
                                                </textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="aged-other" class="form-label">Other</label>
                                                <textarea id="aged-other" name="user_aged_details[other]"
                                                          class="form-control">
                                                    {{ $results->user?->userAgedCare?->other ?? '' }}
                                                </textarea>
                                            </div>
                                        </div>

                                        <!-- Private Info Section -->
                                        <div class="col-6">
                                            <h3>Private Info</h3>
                                            <div class="mb-3">
                                                <label for="private-health-awareness" class="form-label">Health
                                                    Awareness</label>
                                                <select id="private-health-awareness"
                                                        name="user_private_details[health_awareness]"
                                                        class="form-select">
                                                    <option value="1" {{ $results->user?->userPrivate?->health_awareness == 1 ? 'selected' : '' }}>
                                                        Yes
                                                    </option>
                                                    <option value="0" {{ $results->user?->userPrivate?->health_awareness == 0 ? 'selected' : '' }}>
                                                        No
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="private-health-details" class="form-label">Health
                                                    Details</label>
                                                <textarea id="private-health-details"
                                                          name="user_private_details[health_details]"
                                                          class="form-control">
                                                    {{ $results->user?->userPrivate?->health_details ?? '' }}
                                                </textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="private-other" class="form-label">Other</label>
                                                <textarea id="private-other" name="user_private_details[other]"
                                                          class="form-control">
                                                    {{ $results->user?->userPrivate?->other ?? '' }}
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <div class="form-group">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-sm pull-right m-5" type="submit">
                                            @lang('view_pages.update')
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
    <!-- container -->

    </div>
    <!-- content -->

@endsection
