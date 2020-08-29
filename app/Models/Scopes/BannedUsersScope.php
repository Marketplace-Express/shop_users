<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/29
 * Time: 10:25
 */

namespace App\Models\Scopes;


use App\Models\BannedUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class BannedUsersScope
 * @package App\Models\Scopes
 */
class BannedUsersScope implements Scope
{
    /**
     * All of the extensions to be added to the builder.
     *
     * @var array
     */
    protected $extensions = ['WithBanned', 'WithoutBanned', 'OnlyBanned', 'Ban', 'UnBan'];

    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) { // if model uses softDeletes
            return;
        }

        $builder->where($model->getQualifiedIsBannedColumn(), 0);
    }

    /**
     * @param Builder $builder
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    /**
     * @param Builder $builder
     */
    public function addWithBanned(Builder $builder)
    {
        $builder->macro('withBanned', function (Builder $builder, $withBanned = true) {
            if (! $withBanned) {
                return $builder->withoutBanned();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    /**
     * @param Builder $builder
     */
    public function addWithoutBanned(Builder $builder)
    {
        $builder->macro('withoutBanned', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedIsBannedColumn(), 0
            );

            return $builder;
        });
    }

    /**
     * @param Builder $builder
     */
    protected function addOnlyBanned(Builder $builder)
    {
        $builder->macro('onlyBanned', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedIsBannedColumn(), 1
            );

            return $builder;
        });
    }

    /**
     * @param Builder $builder
     */
    protected function addBan(Builder $builder)
    {
        $builder->macro('ban', function (Builder $builder, $reason = null, $description = null) {
            $builder->withoutBanned();

            $model = $builder->getModel();
            $bannedUserRelation = $model->hasOne(BannedUser::class, $model->primaryKey, 'user_id');
            $bannedUserRelation->create([
                'reason' => $reason,
                'description' => $description
            ]);

            $builder->where($model->primaryKey, $model->getKey());

            return $builder->update([$builder->getModel()->getIsBannedColumn() => true]);
        });
    }

    /**
     * @param Builder $builder
     */
    protected function addUnBan(Builder $builder)
    {
        $builder->macro('unBan', function (Builder $builder) {
            $builder->withBanned();

            $model = $builder->getModel();
            $bannedUserRelation = $model->hasOne(BannedUser::class, $model->primaryKey, 'user_id');
            $bannedUserRelation->delete();

            $builder->where($model->primaryKey, $model->getKey());

            return $builder->update([$builder->getModel()->getIsBannedColumn() => false]);
        });
    }
}