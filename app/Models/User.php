<?php

namespace App\Models;

use App\Base\Traits\Custom\NotificationAttribute;
use App\Base\Traits\Model\FilterSort;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, FilterSort, NotificationAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getTableName()
    {
        return with(new static())->getTable();
    }

    public static function MyColumns()
    {
        return Schema::getColumnListing(self::getTableName());
    }

    public static function filterColumns(): array
    {
        return array_merge(self::MyColumns(), [
            static::createdAtBetween('created_from'),
            static::createdAtBetween('created_to'),
            static::filterSearchInAllColumns('search'),
        ]);
    }

    public static function sortColumns(): array
    {
        return self::MyColumns();
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => (bcrypt($value)),
        );
    }

    public function deleteRelations(): array
    {
        return [];
    }

    public function wallet(){
        return $this->hasOne(Wallet::class);
    }
}
