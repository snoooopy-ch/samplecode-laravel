<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Services\ManualInputService;
use App\Services\ReferralService;
use App\Enums\TreeType;
use Illuminate\Http\Request;

class RealTreeController extends Controller
{
    /**
     * @var ReferralService
     */
    protected $realTree;
    protected $manualInput;

    /**
     * @param ReferralService $realTree
     */
    public function __construct(ReferralService $realTree, ManualInputService $manualInput) {
        $this->realTree = $realTree;
        $this->manualInput = $manualInput;
    }

    /**
     * Display a page
     * 
     * @return null
     */
    public function index() {
        $user = Auth::user();
        $lastData = $this->manualInput->last();
        $childrens = $user->activeChildren()->count();
        $referral = $user->referral;
        $userInfo = $user->userInfo()->type(TreeType::RealTree)->get()->last();
        if ($userInfo) {
            $basic_bonus = $userInfo->basic_bonus;
        } else {
            $basic_bonus = 0;
        }
        return view('realtree')
            ->withChildrenCount($childrens)
            ->withReferral($referral)
            ->withLastData($lastData)
            ->withUserId($user->id)
            ->withBasicBonus($basic_bonus);
    }

    /**
     * Display a trees
     * 
     * @return Response
     */
    public function realTree(Request $request) {
        $current = $request->get('current');

        $list = $this->realTree->list($request);
        $affiliate_id = User::find($current)->affiliate_id;
        $parent_id = User::find($current)->referral->parent_id;
        $account_id = User::find($current)->id;
        $stepsFromTop = $this->realTree->stepFromTop($current); 
        $result = [
            'list' => $list,
            'currentUser' => [
                'affiliate_id'      => $affiliate_id,
                'parent_id'         => $parent_id,
                'stepFromTop'       => $stepsFromTop,
                'account_id'        => $account_id
            ]
        ];

        return response()->json($result);
    }

    public function homeTree() {
        $tree = $this->realTree->homeTree(Auth::user()->id);
        return $tree;
    }

    public function upward(Request $request) {
        $user_id = $request->route('id');
        $parent_id = User::find($user_id)->realTree->parent_id;
        $tree = $this->realTree->tree($parent_id);
        return $tree;
    }

    public function downward(Request $request) {
        $user_id = $request->route('id');
        $tree = $this->realTree->tree($user_id);
        return $tree;
    }
}
