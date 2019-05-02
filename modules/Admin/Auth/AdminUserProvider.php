<?php 

namespace Modules\Admin\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;
use App\Hash\Hasher;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\Models\Users\User;

class AdminUserProvider extends EloquentUserProvider implements UserProvider {
    /**
     * Create a new database user provider.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(Hasher $hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        return new User;
    }
}