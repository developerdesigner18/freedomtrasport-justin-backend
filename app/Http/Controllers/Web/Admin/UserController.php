<?php

namespace App\Http\Controllers\Web\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Country;
use App\Models\Admin\Driver;
use App\Models\Admin\Company;
use App\Base\Constants\Auth\Role;
use App\Models\Admin\UserDetails;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Web\BaseController;
use App\Base\Constants\Auth\Role as RoleSlug;
use App\Base\Filters\Master\CommonMasterFilter;
use App\Http\Requests\Admin\User\CreateUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Base\Libraries\QueryFilter\QueryFilterContract;
use App\Base\Services\ImageUploader\ImageUploaderContract;
use App\Models\Request\Request as RequestRequest;
use App\Base\Filters\Admin\RequestFilter;
use App\Models\Payment\UserWalletHistory;
use App\Models\Payment\UserWallet;
use App\Http\Requests\Admin\User\AddUserMoneyToWalletRequest;
use App\Base\Constants\Setting\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Base\Constants\Masters\WalletRemarks;
use App\Models\Chat;
use App\Models\ChatMessage;
use Kreait\Firebase\Contract\Database;


class UserController extends BaseController
{
    /**
     * The User Details model instance.
     *
     * @var \App\Models\Admin\UserDetails
     */
    protected $user_details;

    /**
     * The User model instance.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * The
     *
     * @var App\Base\Services\ImageUploader\ImageUploaderContract
     */
    protected $imageUploader;


    /**
     * User Details Controller constructor.
     *
     * @param \App\Models\Admin\UserDetails $user_details
     */
    public function __construct(UserDetails $user_details, ImageUploaderContract $imageUploader, User $user, Database $database)
    {
        $this->user_details = $user_details;
        $this->imageUploader = $imageUploader;
        $this->user = $user;
        $this->database = $database;

    }

    /**
     * Get all users
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $page = trans('pages_names.users');

        $main_menu = 'users';
        $sub_menu = 'user_details';


        return view('admin.users.index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllUser(QueryFilterContract $queryFilter)
    {
        $url = request()->fullUrl(); //get full url

//        dd(RoleSlug::USER);
        $query = User::where('is_deleted_at', null)->belongsToRole(RoleSlug::USER);
        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();

        return view('admin.users._user', compact('results'));
    }


    public function indexDeleted()
    {
        $page = trans('pages_names.users');

        $main_menu = 'users';
        $sub_menu = 'user_deleted';

        return view('admin.users.deleted-index', compact('page', 'main_menu', 'sub_menu'));
    }

    public function getAllDeletedUser(QueryFilterContract $queryFilter)
    {

        $query = User::whereNotNull('is_deleted_at')->belongsToRole(RoleSlug::USER);

        $results = $queryFilter->builder($query)->customFilter(new CommonMasterFilter)->paginate();
        return view('admin.users._deleted-user', compact('results'));
    }

    /**
     * Create User View
     *
     */
    public function create()
    {
        $page = trans('pages_names.add_user');

        $countries = Country::active()->get();

        $main_menu = 'users';
        $sub_menu = 'user_details';

        return view('admin.users.create', compact('page', 'countries', 'main_menu', 'sub_menu'));
    }


//    public function store(CreateUserRequest $request)
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $created_params = $request->only(['name', 'surname', 'mobile', 'gender', 'email', 'country']);
            $created_params['mobile_confirmed'] = true;
            $created_params['password'] = bcrypt($request->input('password'));

            $validate_exists_email = $this->user->belongsTorole(Role::USER)->where('email', $request->email)->exists();

            $validate_exists_mobile = $this->user->belongsTorole(Role::USER)->where('mobile', $request->mobile)->exists();

            if ($validate_exists_email) {
                return redirect()->back()->withErrors(['email' => 'Provided email hs already been taken'])->withInput();
            }
            if ($validate_exists_mobile) {
                return redirect()->back()->withErrors(['mobile' => 'Provided mobile hs already been taken'])->withInput();
            }

            if ($uploadedFile = $this->getValidatedUpload('profile_picture', $request)) {
                $created_params['profile_picture'] = $this->imageUploader->file($uploadedFile)
                    ->saveProfilePicture();
            }

            $created_params['company_key'] = auth()->user()->company_key;

            $created_params['refferal_code'] = str_random(6);

            $user = $this->user->create($created_params);

            $extraDetails = [
                'address' => $request->address,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'emg_name' => $request->emg_name,
                'emg_number' => $request->emg_number,
                'emg_email' => $request->emg_email,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'name' => $request->name,
                'surname' => $request->surname,
                'country_code' => $request->country,
            ];

            $user->userDetails()->create($extraDetails);

            $user->attachRole(RoleSlug::USER);
            $user->userWallet()->create(['amount_added' => 0]);

            $mainUser = $user;

            if ($request->has('user_aged_details') && !empty($request->user_aged_details) && $request->user_aged_details['participant'] == "1") {
                $mainUser->userAgedCare()->updateOrCreate(
                    ['user_id' => $mainUser->id], // Matching criteria
                    $request->user_aged_details // Data to update or create
                );
            }

            if ($request->has('user_ndis_details') && !empty($request->user_ndis_details) && $request->user_ndis_details['participant'] == "1") {
                $mainUser->userNdis()->updateOrCreate(
                    ['user_id' => $mainUser->id], // Matching criteria
                    $request->user_ndis_details // Data to update or create
                );
            }

            if ($request->has('user_niisq_details') && !empty($request->user_niisq_details) && $request->user_niisq_details['participant'] == "1") {
                $mainUser->userNiisq()->updateOrCreate(
                    ['user_id' => $mainUser->id], // Matching criteria
                    $request->user_niisq_details // Data to update or create
                );
            }

            if ($request->has('user_private_details') && !empty($request->user_private_details) && $request->user_private_details['health_awareness'] == "1") {
                $mainUser->userPrivate()->updateOrCreate(
                    ['user_id' => $mainUser->id], // Matching criteria
                    $request->user_private_details // Data to update or create
                );
            }


            $message = trans('succes_messages.user_added_succesfully');
            DB::commit();
            return redirect('users')->with('success', $message);
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withErrors(['exception' => $exception->getMessage()])->withInput();
        }
    }

    public function getById(User $user)
    {

        $page = trans('pages_names.edit_user');


        $countries = Country::all();
        $results = $user->userDetails ?? $user;
        $main_menu = 'users';
        $sub_menu = 'user_details';

        return view('admin.users.update', compact('page', 'countries', 'main_menu', 'results', 'sub_menu'));
    }


    public function update(Request $request, UserDetails $user)
    {


        $updated_params = $request->only(['name', 'surname', 'mobile', 'gender', 'email', 'country', 'line_item_base', 'line_item_time', 'line_item_km', 'dob', 'address']);
        $userObject = User::find($user->user_id);
        if ($uploadedFile = $this->getValidatedUpload('profile_picture', $request)) {
            $profile_picture = $this->imageUploader->file($uploadedFile)
                ->saveProfilePicture();


            $userObject->update(['profile_picture' => $profile_picture,]);
        }


//        dd($this->user,$user);
        $validate_exists_email = $this->user->belongsTorole(Role::USER)->where('email', $request->email)->where('id', '!=', $user->user_id)->exists();

        $validate_exists_mobile = $this->user->belongsTorole(Role::USER)->where('mobile', $request->mobile)->where('id', '!=', $user->user_id)->exists();

        if ($validate_exists_email) {
            return redirect()->back()->withErrors(['email' => 'Provided email hs already been taken'])->withInput();
        }
        if ($validate_exists_mobile) {
            return redirect()->back()->withErrors(['mobile' => 'Provided mobile hs already been taken'])->withInput();
        }

        $user->update($updated_params);

        $mainUser = User::find($user->user_id);
        $mainUser->update(['name' => $request->name, 'surname' => $request->surname]);

        if ($request->has('user_aged_details') && !empty($request->user_aged_details) && $request->user_aged_details['number'] != null) {
            $mainUser->userAgedCare()->updateOrCreate(
                ['user_id' => $mainUser->id], // Matching criteria
                $request->user_aged_details // Data to update or create
            );
        }

        if ($request->has('user_ndis_details') && !empty($request->user_ndis_details) && $request->user_ndis_details['number'] != null) {
            $mainUser->userNdis()->updateOrCreate(
                ['user_id' => $mainUser->id], // Matching criteria
                $request->user_ndis_details // Data to update or create
            );
        }

        if ($request->has('user_niisq_details') && !empty($request->user_niisq_details) && $request->user_niisq_details['number'] != null) {
            $mainUser->userNiisq()->updateOrCreate(
                ['user_id' => $mainUser->id], // Matching criteria
                $request->user_niisq_details // Data to update or create
            );
        }

        if ($request->has('user_private_details') && !empty($request->user_private_details) && $request->user_private_details['health_details'] != null) {
            $mainUser->userPrivate()->updateOrCreate(
                ['user_id' => $mainUser->id], // Matching criteria
                $request->user_private_details // Data to update or create
            );
        }

//        if(!$user->userWallet)
//        {
//            $user->userWallet()->create(['amount_added'=>0]);
//        }

        $message = trans('succes_messages.user_updated_succesfully');

        return redirect('users')->with('success', $message);
    }

    public function toggleStatus(User $user)
    {
        $status = $user->active == 1 ? 0 : 1;
        $user->update([
            'active' => $status
        ]);

        $message = trans('succes_messages.user_status_changed_succesfully');
        return redirect('users')->with('success', $message);
    }

    public function delete(User $user)
    {
        if (env('APP_FOR') == 'demo') {

            $message = 'you cannot delete the user. this is demo version';

            return $message;

        }

        try {
            DB::beginTransaction();

            // Delete related chat messages
            ChatMessage::whereIn('chat_id', function ($query) use ($user) {
                $query->select('id')
                    ->from('chats')
                    ->where('user_id', $user->id);
            })->delete();

            // Delete the chats
            Chat::where('user_id', $user->id)->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception or log the error
            throw $e;
        }
        // $chat_exists = Chat::join("chat_messages", "chat.id", "chat_messages.chat_id")->where('user_id', $user->id)->delete();

        $user->delete();

        $message = trans('succes_messages.user_deleted_succesfully');

        return $message;
    }

// revertById
    public function revertById(User $user)
    {
        $user->update(['is_deleted_at' => null]);

        $message = trans('succes_messages.user_reverted_succesfully');

        return redirect('users/deleted')->with('success', $message);

    }

    public function UserTripRequest(QueryFilterContract $queryFilter, User $user)
    {

        $completedTrips = RequestRequest::where('user_id', $user->id)->companyKey()->whereIsCompleted(true)->count();
        $cancelledTrips = RequestRequest::where('user_id', $user->id)->companyKey()->whereIsCancelled(true)->count();
        $upcomingTrips = RequestRequest::where('user_id', $user->id)->companyKey()->whereIsLater(true)->whereIsCompleted(false)->whereIsCancelled(false)->whereIsDriverStarted(false)->count();

        $card = [];
        $card['completed_trip'] = ['name' => 'trips_completed', 'display_name' => 'Completed Rides', 'count' => $completedTrips, 'icon' => 'fa fa-flag-checkered text-green'];
        $card['cancelled_trip'] = ['name' => 'trips_cancelled', 'display_name' => 'Cancelled Rides', 'count' => $cancelledTrips, 'icon' => 'fa fa-ban text-red'];
        $card['upcoiming_trip'] = ['name' => 'trips_cancelled', 'display_name' => 'Upcoming Rides', 'count' => $upcomingTrips, 'icon' => 'fa fa-calendar'];

        $main_menu = 'users';
        $sub_menu = 'user_details';


        $query = RequestRequest::where('user_id', $user->id);
        $results = $queryFilter->builder($query)->customFilter(new RequestFilter)->defaultSort('-created_at')->paginate();


        return view('admin.users.user-request-list', compact('results', 'card', 'main_menu', 'sub_menu'));
    }

    public function userPaymentHistory(User $user)
    {
        $main_menu = 'users';
        $sub_menu = 'user_details';
        $item = $user;

        $amount = UserWallet::where('user_id', $user->id)->first();

        if ($amount == null) {
            $card = [];
            $card['total_amount'] = ['name' => 'total_amount', 'display_name' => 'Total Amount ', 'count' => "0", 'icon' => 'fa fa-flag-checkered text-green'];
            $card['amount_spent'] = ['name' => 'amount_spent', 'display_name' => 'Spend Amount ', 'count' => "0", 'icon' => 'fa fa-ban text-red'];
            $card['balance_amount'] = ['name' => 'balance_amount', 'display_name' => 'Balance Amount', 'count' => "0", 'icon' => 'fa fa-ban text-red'];

            $history = UserWalletHistory::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        } else {

            $card = [];
            $card['total_amount'] = ['name' => 'total_amount', 'display_name' => 'Total Amount ', 'count' => $amount->amount_added, 'icon' => 'fa fa-flag-checkered text-green'];
            $card['amount_spent'] = ['name' => 'amount_spent', 'display_name' => 'Spend Amount ', 'count' => $amount->amount_spent, 'icon' => 'fa fa-ban text-red'];
            $card['balance_amount'] = ['name' => 'balance_amount', 'display_name' => 'Balance Amount', 'count' => $amount->amount_balance, 'icon' => 'fa fa-ban text-red'];

            $history = UserWalletHistory::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);

            // dd($history);
        }
        return view('admin.users.user-payment-wallet', compact('card', 'main_menu', 'sub_menu', 'item', 'history'));
    }

    public function StoreUserPaymentHistory(AddUserMoneyToWalletRequest $request, User $user)
    {
// dd($request);

        $currency = get_settings(Settings::CURRENCY);

        // $converted_amount_array =  convert_currency_to_usd($user_currency_code, $request->input('amount'));

        // $converted_amount = $converted_amount_array['converted_amount'];
        // $converted_type = $converted_amount_array['converted_type'];
        // $conversion = $converted_type.':'.$request->amount.'-'.$converted_amount;
        $transaction_id = Str::random(6);


        $wallet_model = new UserWallet();
        $wallet_add_history_model = new UserWalletHistory();
        $user_id = $user->id;


        $user_wallet = $wallet_model::firstOrCreate([
            'user_id' => $user_id]);
        $user_wallet->amount_added += $request->amount;
        $user_wallet->amount_balance += $request->amount;
        $user_wallet->save();

        $wallet_add_history_model::create([
            'user_id' => $user_id,
            'card_id' => null,
            'amount' => $request->amount,
            'transaction_id' => $transaction_id,
            'merchant' => null,
            'remarks' => WalletRemarks::MONEY_DEPOSITED_TO_E_WALLET_FROM_ADMIN,
            'is_credit' => true]);


        $message = "money_added_successfully";
        return redirect()->back()->with('success', $message);


    }

    public function getUserExtraDetails(Request $request)
    {
        // Fetch user data with relations
        $userData = User::with(['userAgedCare', 'userNdis', 'userNiisq', 'userPrivate'])
            ->where('id', $request->user_id)
            ->first();

        $html = '';

        // Based on the requested type, create the table HTML
        switch ($request->type) {
            case 'private':
                if ($userData && $userData->userPrivate) {
                    $private = $userData->userPrivate;
                    $html .= '<table class="table table-bordered">';
                    $html .= '<tr><th>Title</th><th>Value</th></tr>';
                    $html .= '<tr><td>Health Awareness</td><td>' . ($private->health_awareness ? 'Yes' : 'No') . '</td></tr>';
                    $html .= '<tr><td>Health Details</td><td>' . $private->health_details . '</td></tr>';
                    $html .= '<tr><td>Other</td><td>' . $private->other . '</td></tr>';
                    $html .= '</table>';
                }
                break;
            case 'aged_care':
                if ($userData && $userData->userAgedCare) {
                    $agedCare = $userData->userAgedCare;
                    $html .= '<table class="table table-bordered">';
                    $html .= '<tr><th>Title</th><th>Value</th></tr>';
                    $html .= '<tr><td>Provider Name</td><td>' . $agedCare->provider_name . '</td></tr>';
                    $html .= '<tr><td>Care Package</td><td>' . $agedCare->care_package . '</td></tr>';
                    $html .= '<tr><td>Case Manager Name</td><td>' . $agedCare->case_manager_name . '</td></tr>';
                    $html .= '<tr><td>Case Manager Phone</td><td>' . $agedCare->case_manager_phone . '</td></tr>';
                    $html .= '<tr><td>Case Manager Email</td><td>' . $agedCare->case_manager_email . '</td></tr>';
                    $html .= '<tr><td>CHSP Support</td><td>' . ($agedCare->chsp_support ? 'Yes' : 'No') . '</td></tr>';
                    $html .= '<tr><td>Health Awareness</td><td>' . ($agedCare->health_awareness ? 'Yes' : 'No') . '</td></tr>';
                    $html .= '<tr><td>Health Details</td><td>' . $agedCare->health_details . '</td></tr>';
                    $html .= '<tr><td>Other</td><td>' . $agedCare->other . '</td></tr>';
                    $html .= '</table>';
                }
                break;
            case 'ndis':
                if ($userData && $userData->userNdis) {
                    $ndis = $userData->userNdis;
                    $html .= '<table class="table table-bordered">';
                    $html .= '<tr><th>Title</th><th>Value</th></tr>';
                    $html .= '<tr><td>Participant</td><td>' . ($ndis->participant ? 'Yes' : 'No') . '</td></tr>';
                    $html .= '<tr><td>NDIS Number</td><td>' . $ndis->number . '</td></tr>';
                    $html .= '<tr><td>Type</td><td>' . $ndis->type . '</td></tr>';
                    $html .= '<tr><td>Plan Manager Name</td><td>' . $ndis->plan_manager_name . '</td></tr>';
                    $html .= '<tr><td>Plan Manager Phone</td><td>' . $ndis->plan_manager_phone . '</td></tr>';
                    $html .= '<tr><td>Plan Manager Email</td><td>' . $ndis->plan_manager_email . '</td></tr>';
                    $html .= '<tr><td>Health Awareness</td><td>' . ($ndis->health_awareness ? 'Yes' : 'No') . '</td></tr>';
                    $html .= '<tr><td>Health Details</td><td>' . $ndis->health_details . '</td></tr>';
                    $html .= '<tr><td>Other</td><td>' . $ndis->other . '</td></tr>';
                    $html .= '</table>';
                }
                break;
            case 'niisq':
                if ($userData && $userData->userNiisq) {
                    $niisq = $userData->userNiisq;
                    $html .= '<table class="table table-bordered">';
                    $html .= '<tr><th>Title</th><th>Value</th></tr>';
                    $html .= '<tr><td>Participant</td><td>' . ($niisq->participant ? 'Yes' : 'No') . '</td></tr>';
                    $html .= '<tr><td>NIISQ Number</td><td>' . $niisq->number . '</td></tr>';
                    $html .= '<tr><td>Plan Manager Name</td><td>' . $niisq->plan_manager_name . '</td></tr>';
                    $html .= '<tr><td>Plan Manager Phone</td><td>' . $niisq->plan_manager_phone . '</td></tr>';
                    $html .= '<tr><td>Plan Manager Email</td><td>' . $niisq->plan_manager_email . '</td></tr>';
                    $html .= '<tr><td>Health Awareness</td><td>' . ($niisq->health_awareness ? 'Yes' : 'No') . '</td></tr>';
                    $html .= '<tr><td>Health Details</td><td>' . $niisq->health_details . '</td></tr>';
                    $html .= '<tr><td>Other</td><td>' . $niisq->other . '</td></tr>';
                    $html .= '</table>';
                }
                break;
            default:
                break;
        }

        return $html;
    }

}
