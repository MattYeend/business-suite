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
    public const ACTION_FORCE_DELETE_USER = 12;
    public const ACTION_WELCOME_EMAIL_SENT = 13;
    public const ACTION_CONFIRM_PASSWORD = 14;
    public const ACTION_FORGOT_PASSWORD = 15;
    public const ACTION_REGISTER_USER = 16;
    public const ACTION_RESET_PASSWORD = 17;
    public const ACTION_RESET_EMAIL = 18;
    public const ACTION_RESET_USERNAME = 19;
    public const ACTION_VERIFY_USER = 20;
    public const ACTION_PASSWORD_CHANGED = 21;
    public const ACTION_USER_RESTORED = 22;
    public const ACTION_USER_DELETED = 23;
    public const ACTION_IMPORT_USER = 24;
    public const ACTION_EXPORT_USER = 25;
    public const ACTION_USER_UPDATED_BY_CRON = 26;

    // MFA/Settings
    public const ACTION_MFA_ENABLED = 27;
    public const ACTION_MFA_DISABLED = 28;
    public const ACTION_PROFILE_UPDATED = 29;
    public const ACTION_PROFILE_DELETED = 30;
    public const ACTION_EMAIL_UPDATED = 31;

    // Role/Permission Management
    public const ACTION_ROLE_ASSIGNED = 32;
    public const ACTION_PERMISSION_GRANTED = 33;
    public const ACTION_PERMISSION_REVOKED = 34;

    // Errors/Cache
    public const ACTION_GENERAL_ERROR = 35;
    public const ACTION_FOUR_HUNDRED_ERROR = 36;
    public const ACTION_FIVE_HUNDRED_ERRORS = 37;
    public const ACTION_CLEAR_CACHE = 38;

    public const ACTION_LOG_TABLE_DATA_RESET_BY_CRON = 39;

    // Company Industry Management
    public const ACTION_CREATE_COMPANY_INDUSTRY = 40;
    public const ACTION_UPDATE_COMPANY_INDUSTRY = 41;
    public const ACTION_DELETE_COMPANY_INDUSTRY = 42;
    public const ACTION_SHOW_COMPANY_INDUSTRY = 43;
    public const ACTION_FORCE_DELETE_COMPANY_INDUSTRY = 44;
    public const ACTION_RESTORE_COMPANY_INDUSTRY = 45;
    public const ACTION_IMPORT_COMPANY_INDUSTRY = 46;
    public const ACTION_EXPORT_COMPANY_INDUSTRY = 47;
    public const ACTION_COMPANY_INDUSTRY_UPDATED_BY_CRON = 48;

    // Company Management
    public const ACTION_CREATE_COMPANY = 49;
    public const ACTION_UPDATE_COMPANY = 50;
    public const ACTION_DELETE_COMPANY = 51;
    public const ACTION_SHOW_COMPANY = 52;
    public const ACTION_FORCE_DELETE_COMPANY = 53;
    public const ACTION_RESTORE_COMPANY = 54;
    public const ACTION_IMPORT_COMPANY = 55;
    public const ACTION_EXPORT_COMPANY = 56;
    public const ACTION_COMPANY_UPDATED_BY_CRON = 57;

    // Company Contact Management
    public const ACTION_CREATE_COMPANY_CONTACT = 58;
    public const ACTION_UPDATE_COMPANY_CONTACT = 59;
    public const ACTION_DELETE_COMPANY_CONTACT = 60;
    public const ACTION_SHOW_COMPANY_CONTACT = 61;
    public const ACTION_FORCE_DELETE_COMPANY_CONTACT = 62;
    public const ACTION_RESTORE_COMPANY_CONTACT = 63;
    public const ACTION_IMPORT_COMPANY_CONTACT = 64;
    public const ACTION_EXPORT_COMPANY_CONTACT = 65;
    public const ACTION_COMPANY_CONTACT_UPDATED_BY_CRON = 66;

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
