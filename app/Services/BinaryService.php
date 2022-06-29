<?php
namespace App\Services;

use App\Models\Master;
use App\Models\User;
use App\Models\BasicRateSetting;
use App\Enums\BinaryStatus;
use App\Enums\TreeType;
use App\Enums\UserBonusStatus;
use App\Repositories\BinaryRepositoryInterface;
use App\Repositories\ReferralRepositoryInterface;
use App\Services\Service;
use Psr\Log\LoggerInterface as Log;


/**
 * Class BinaryService
 * @package App\Services
 */
class BinaryService extends Service {

    /**
     * @var BinaryRepositoryInterface
     */
    protected $binary;

    /**
     * @var Log
     */
    protected $logger;

    /**
     * @param BinaryRepositoryInterface $binary
     * @param Log $logger
     */
    public function __construct(BinaryRepositoryInterface $binary, Log $logger)
    {
        $this->binary = $binary;
        $this->logger = $logger;
    }

    /**
     * Show a tree
     * 
     * @param $user_id
     * @return array
     */
    public function tree( $user_id ) {
        $tree = $this->binaryTree( $user_id, 0, 0 );
        $tree->class = ['rootNode selected_node'];
        $tree->extend = true;

        return $tree;
    }

    /**
     * Create New Binary
     *
     * @param array $postData
     * @return Binary|null
     */
    public function create(array $postData)
    {
        try {
            return $this->binary->create($postData);
        } catch (\Exception $e) {
            $this->logger->error('Binary->create: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Binary Pagination
     *
     * @param array $filter
     * @return collection
     */
    public function paginate(array $filter = [])
    {
        $filter['limit'] = 5;

        return $this->binary->paginate($filter);
    }

    /**
     * Update Binary
     *
     * @param array $post_id
     * @param array $postData
     * @return bool
     */
    public function update($post_id, array $postData)
    {
        try {
            $binary = $this->binary->find($post_id);
            $binary->title = $postData['title'];
            $binary->description = $postData['description'];

            return $binary->save();
        } catch (\Exception $e) {
            $this->logger->error('Binary->update: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Delete Binary
     *
     * @param $post_id
     * @return mixed
     */
    public function delete($post_id)
    {
        try {
            $binary = $this->binary->find($post_id);

            return $binary->delete();
        } catch (\Exception $e) {
            $this->logger->error('Binary->delete: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Get Binary by ID
     *
     * @param $post_id
     * @return Binary
     */
    public function find($post_id)
    {
        try {
            return $this->binary->find($post_id);
        } catch (\Exception $e) {
            $this->logger->error('Binary->find: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Generate a binary tree json representation.
     * 
     * @param $parnet_id
     */
    protected function binaryTree($user_id, $limit = 0, $deep) {
        $children = $this->binary->orderBy('position', 'asc')->filter([
            'parent_id' => $user_id,
            'status'    => BinaryStatus::Valid
        ])->get();
        $parent = User::findOrFail($user_id);

        $result = new \stdClass();
        $result->title = $parent->affiliate_id;
        $result->user_id = $user_id;
        $result->count = '';
        $result->bet = '';
        $result->paid = trans('home.bet_month');
        $result->image_url = $parent->avatar;
        $result->extend = true;
        $result->class = ['rootNode selected_node'];
        $result->selected = 0;

        $self = $this->binary->filter([
            'user_id' => $user_id
        ]);
        $result->direction = $self[0]->left_loss <= $self[0]->right_loss? 1 : 2; 
        $result->text = trans('home.bets', [
            'amount' => _number_format($self[0]->own_bet?? 0, 0)
        ]);
        
        if (count($children) > 0) {
            $result->children = [];
        }

        $rate = BasicRateSetting::where('type', TreeType::BinaryTree)->first()? 
            BasicRateSetting::where('type', TreeType::BinaryTree)->first()->basic_percent : 0;

        foreach ($children as $child) {
            $userInfo = User::findOrFail($child->user_id);
            $node = new \stdClass();
            $node->desc = '';
            $node->image_url = '';
            $node->extend = false;

            if ($child->position == 1) {
                $node->title = trans('home.left_node');
                $node->count = trans('tree.field.child_count', ['count' => _number_format($self[0]->left_count, 0)]);
                $node->bet = trans('tree.field.total_bet', ['amount' => _number_format($self[0]->left_bet, 0)]);
                $node->paid = '';
                $node->user_id = $child->user_id;
                $hp = $self[0]->left_loss? $self[0]->left_loss * $rate / 100 : 0;
                if ($hp < 0) $hp = 0;
                $node->text = trans('home.basic_bonus') . trans('home.loss', [
                    'amount' => _number_format($hp, 0)
                ]);
            } else if ($child->position == 2) {
                $node->title = trans('home.right_node');
                $node->count = trans('tree.field.child_count', ['count' => _number_format($self[0]->right_count, 0)]);
                $node->bet =  trans('tree.field.total_bet', ['amount' => _number_format($self[0]->right_bet, 0)]);
                $node->paid = '';
                $node->user_id = $child->user_id;
                $hp = $self[0]->right_loss? $self[0]->right_loss * $rate / 100 : 0;
                if ($hp < 0) $hp = 0;
                $node->text = trans('home.basic_bonus') . trans('home.loss', [
                    'amount' => _number_format($hp, 0)
                ]);;
            }

            if ($child->position == $result->direction) {
                $node->selected = 1;
                $node->class=["selected_node child_node"];
            } else {
                $node->selected = 0;
                $node->class=["child_node"];
            }
            
            $result->children[] = $node;
        }

        return $result;
    }

    public function homeTree($user_id) {
        $children = $this->binary->orderBy('position', 'asc')->filter([
            'parent_id' => $user_id,
            'status'    => BinaryStatus::Valid
        ])->get();
        $parent = User::findOrFail($user_id);

        $result = new \stdClass();
        $result->title = $parent->affiliate_id;
        $result->desc = trans('home.bet_month');
        $result->hp = '';
        $result->image_url = $parent->avatar;
        $result->extend = true;
        $result->class = ['rootNode selected_node'];
        $result->selected = 0;

        $self = $this->binary->filter([
            'user_id' => $user_id
        ]);
        $result->direction = $self[0]->left_loss <= $self[0]->right_loss? 1 : 2; 
        $result->text = trans('home.bets', [
            'amount' => _number_format($self[0]->own_bet?? 0, 0)
        ]);
        
        if (count($children) > 0) {
            $result->children = [];
        }

        $rate = BasicRateSetting::where('type', TreeType::BinaryTree)->first()? 
            BasicRateSetting::where('type', TreeType::BinaryTree)->first()->basic_percent : 0;
        $guaranty = Master::getValue('MINIMUM_GUARANTY');

        foreach ($children as $child) {
            $userInfo = User::findOrFail($child->user_id);
            $node = new \stdClass();
            $node->desc = trans('home.basic_bonus');
            $node->image_url = '';
            $node->extend = false;

            if ($child->position == 1) {
                $node->title = trans('home.left_node');
                $hp = $self[0]->left_loss? $self[0]->left_loss * $rate / 100 : 0;
                if ($hp < 0) $hp = 0;
                $node->hp = trans('home.loss', [
                    'amount' => _number_format($hp, 0)
                ]);
            } else if ($child->position == 2) {
                $node->title = trans('home.right_node');
                $hp = $self[0]->right_loss? $self[0]->right_loss * $rate / 100 : 0;
                if ($hp < 0) $hp = 0;
                $node->hp = trans('home.loss', [
                    'amount' => _number_format($hp, 0)
                ]);
            }

            if ($child->position == $result->direction) {
                $node->selected = 1;
                $node->class=["selected_node", "child_node"];

                $battleInfo = $parent->userInfo()->type(TreeType::BinaryTree)->get()->last();
                if (isset($battleInfo) && $battleInfo->use_guaranty == 1) {
                    $node->text = trans('home.bonus_loss', [
                        'amount' => _number_format($guaranty, 0)
                    ]);
                    $node->class[] = "use_guaranty";
                }
            } else {
                $node->selected = 0;
                $node->class=["child_node"];
            }

            $result->children[] = $node;
        }

        return $result;
    }


}