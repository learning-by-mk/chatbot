<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = ['inquiries', 'respondedInquiries', 'avatar', 'favorites', 'documents', 'authorProfile'];

    protected $guard_name = 'web';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isAuthor(): bool
    {
        return $this->hasRole('author');
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(File::class, 'avatar_file_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'author_id');
    }

    public function author_profile(): HasOne
    {
        return $this->hasOne(AuthorProfile::class);
    }

    public function isLocked(): bool
    {
        return $this->locked_until && now()->lessThan($this->locked_until);
    }

    public function getLockTimeRemaining()
    {
        return $this->locked_until ? now()->diffInSeconds($this->locked_until) : null;
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function respondedInquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'responded_by_id');
    }

    public function deposit(PointPackage $pointPackage)
    {
        $this->points += $pointPackage->points;
        $this->historyPoints()->create([
            'points' => $pointPackage->points,
            'description' => 'Nạp điểm từ gói ' . $pointPackage->name . ' với giá ' . $pointPackage->price . ' VND'
        ]);
        $this->save();
    }

    public function historyPoints(): HasMany
    {
        return $this->hasMany(HistoryPoint::class);
    }

    public function statistics()
    {
        $totalUsers = User::count();
        $totalActiveUsers = User::where('status', 'active')->count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
        $userGrowthRate = User::where('created_at', '>=', now()->subMonth())->count() / User::where('created_at', '>=', now()->subMonth())->count() * 100;
        $activityStats = [
            'daily' => User::where('created_at', '>=', now()->startOfDay())->count(),
            'weekly' => User::where('created_at', '>=', now()->startOfWeek())->count(),
            'monthly' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'previousPeriodChange' => User::where('created_at', '>=', now()->subMonth())->count() / User::where('created_at', '>=', now()->subMonth())->count() * 100,
        ];
        $userRegistration = DB::table(DB::raw('(SELECT 1 as month UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) as months'))
            ->leftJoin('users', function ($join) {
                $join->whereRaw('MONTH(users.created_at) = months.month');
            })
            ->select(
                'months.month',
                DB::raw('COALESCE(COUNT(users.id), 0) as users')
            )
            ->groupBy('months.month')
            ->orderBy('months.month')
            ->get();

        return [
            'totalUsers' => $totalUsers,
            'totalActiveUsers' => $totalActiveUsers,
            'newUsersThisMonth' => $newUsersThisMonth,
            'userGrowthRate' => $userGrowthRate,
            'activityStats' => $activityStats,
            'userRegistration' => $userRegistration,
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $url = env('FRONTEND_URL', 'http://localhost:3000') . '/reset-password?token=' . $token . '&email=' . $this->email;

        $this->notify(new \App\Notifications\ResetPasswordNotification($token, $url));
    }
}
