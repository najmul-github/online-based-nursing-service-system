<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userPendingRequest =   User::where([
            'approved_at' => NULL,
            'role_id'     => 1,
        ]);
        $nursePendingRequest =   User::where([
            'approved_at' => NULL,
            'role_id'     => 2,
        ]);
        $userPendingRequestCount =   $userPendingRequest->count();
        $nursePendingRequestCount =   $nursePendingRequest->count();

        return view('admin.index')->with([
            'userPendingRequestCount'    =>   $userPendingRequestCount,
            'nursePendingRequestCount'   =>   $nursePendingRequestCount,
        ]);
    }
    public function approvalPending()
    {
        return view('frontend.approval-pending');
    }
    public function approved()
    {
        $id    =   auth()->user()->id;
        $user    =   User::find($id);
        if ($user->role_id == 1) {
            return view('frontend.user-profile')->with([
                'user'   =>   $user,
            ]);
        } else {
            return view('frontend.nurse-profile')->with([
                'user'   =>   $user,
            ]);
        }
    }
    public function userRequestList()
    {
        $userRequestLists = User::where([
            'role_id'   =>  1,
            'approved_at'  =>  Null,
            ])->get();

        return view('admin.user-request-pending-list')->with([
            'userRequestLists'  =>  $userRequestLists,
        ]);;
    }
    public function nurseRequestList()
    {
        $nurseRequestLists = User::where([
            'role_id'   =>  2,
            'approved_at'  =>  Null,
            ])->get();

        return view('admin.nurse-request-pending-list')->with([
            'nurseRequestLists'  =>  $nurseRequestLists,
        ]);
    }
    public function adminRequestApprove($id)
    {
        $approveUser    =   User::findOrFail($id)->update(['approved_at' => now()]);

        return back()->with('message','Request Approved successfully');
    }
}