<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

#[Fillable([
    'action_id',
    'data',
    'logged_in_user_id',
    'related_to_user_id',
    'created_at',
    'updated_at',
])]
/**
 * Log model for tracking system actions.
 *
 * @property int $id
 * @property int $action_id
 * @property array|null $data
 * @property int|null $logged_in_user_id
 * @property int|null $related_to_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read User|null $loggedInUser
 * @property-read User|null $relatedToUser
 */
class Log extends Model
{
    // Action constants
    // Login/Logout
    public const ACTION_LOGIN = 1;
    public const ACTION_LOGOUT = 2;
    public const ACTION_LOGIN_FAILED = 3;
    public const ACTION_LOGIN_PASSWORD_FAILED = 4;
    public const ACTION_LOGIN_EMAIL_FAILED = 5;
    public const ACTION_LOGIN_USERNAME_FAILED = 6;
    public const ACTION_LOGIN_SUCCESS = 7;

    // User Management
    public const ACTION_CREATE_USER = 8;
    public const ACTION_UPDATE_USER = 9;
    public const ACTION_DELETE_USER = 10;
    public const ACTION_SHOW_USER = 11;
    public const ACTION_WELCOME_EMAIL_SENT = 12;
    public const ACTION_CONFIRM_PASSWORD = 13;
    public const ACTION_FORGOT_PASSWORD = 14;
    public const ACTION_REGISTER_USER = 15;
    public const ACTION_RESET_PASSWORD = 16;
    public const ACTION_RESET_EMAIL = 17;
    public const ACTION_RESET_USERNAME = 18;
    public const ACTION_VERIFY_USER = 19;
    public const ACTION_PASSWORD_CHANGED = 20;
    public const ACTION_USER_RESTORED = 21;
    public const ACTION_USER_DELETED = 22;

    // MFA/Settings
    public const ACTION_MFA_ENABLED = 23;
    public const ACTION_MFA_DISABLED = 24;
    public const ACTION_PROFILE_UPDATED = 25;
    public const ACTION_PROFILE_DELETED = 26;
    public const ACTION_EMAIL_UPDATED = 27;

    // Role/Permission Management
    public const ACTION_ROLE_ASSIGNED = 28;
    public const ACTION_PERMISSION_GRANTED = 29;
    public const ACTION_PERMISSION_REVOKED = 30;

    // Errors/Cache
    public const ACTION_GENERAL_ERROR = 31;
    public const ACTION_FOUR_HUNDRED_ERROR = 32;
    public const ACTION_FIVE_HUNDRED_ERRORS = 33;
    public const ACTION_CLEAR_CACHE = 34;

    // New Logging Actions should go here to be reviewed
    // by the development team for future releases.
    // Ensure to update the documentation accordingly.

    // Empty constants
    public const ACTION_NONE = 000;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logs';

    /**
     * Get the user who performed the action.
     *
     * @return BelongsTo<User,Log>
     */
    public function loggedInUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_in_user_id');
    }

    /**
     * Get the user related to the action, if applicable.
     *
     * @return BelongsTo<User,Log>
     */
    public function relatedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'related_to_user_id');
    }

    /**
     * Log an action.
     *
     * @param int $action The action constant.
     * @param array|null $data Additional data related to the action.
     * @param int|null $logged_in_user_id The ID of the user performing
     * the action.
     * @param int|null $related_to_user_id The ID of the user related
     * to the action.
     */
    public static function log(
        $action = 0,
        $data = null,
        $logged_in_user_id = null,
        $related_to_user_id = null
    ) {
        if (isset($action)) {
            $logged_in_user_id = $logged_in_user_id ?? Auth::id();

            if (! is_null($data) && ! is_array($data)) {
                throw new \InvalidArgumentException(
                    'Data must be an array or null.'
                );
            }

            $log = new self();
            $log->logged_in_user_id = $logged_in_user_id;
            $log->action_id = $action;
            $log->related_to_user_id = $related_to_user_id;
            $log->data = $data;
            $log->save();
        }
    }

    /**
     * Scope a query to only include logs of a given action type.
     *
     * @param  Builder<Log> $query The query builder instance.
     * @param  int $action The action constant to filter by.
     *
     * @return Builder<Log> The modified query builder instance.
     */
    public function scopeOfAction(Builder $query, int $action): Builder
    {
        return $query->where('action_id', $action);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
