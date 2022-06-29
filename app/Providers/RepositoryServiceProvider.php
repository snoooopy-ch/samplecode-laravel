<?php

namespace App\Providers;

use App\Repositories\AllianceRepositoryInterface;
use App\Repositories\EloquentRepositoryInterface; 
use App\Repositories\BinaryRepositoryInterface;
use App\Repositories\ReferralRepositoryInterface; 
use App\Repositories\BonusHistoryRepositoryInterface;
use App\Repositories\GameHistoryRepositoryInterface; 
use App\Repositories\FileRepositoryInterface; 
use App\Repositories\NewsRepositoryInterface;
use App\Repositories\ManualInputRepositoryInterface;
use App\Repositories\MaintenanceRepositoryInterface;
use App\Repositories\NoticeRepositoryInterface;
use App\Repositories\FaqRepositoryInterface; 
use App\Repositories\CryptoSettingRepositoryInterface; 
use App\Repositories\UserRepositoryInterface; 
use App\Repositories\Eloquent\AllianceRepository;
use App\Repositories\Eloquent\BaseRepository; 
use App\Repositories\Eloquent\BinaryRepository; 
use App\Repositories\Eloquent\ReferralRepository;
use App\Repositories\Eloquent\BonusHistoryRepository; 
use App\Repositories\Eloquent\GameHistoryRepository; 
use App\Repositories\Eloquent\NoticeRepository; 
use App\Repositories\Eloquent\FileRepository; 
use App\Repositories\Eloquent\MaintenanceRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\FaqRepository;
use App\Repositories\Eloquent\ManualInputRepository;
use App\Repositories\Eloquent\CryptoSettingRepository; 
use App\Repositories\Eloquent\UserRepository; 
use Illuminate\Support\ServiceProvider;

/** 
* Class RepositoryServiceProvider 
* @package App\Providers 
*/
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(BinaryRepositoryInterface::class, BinaryRepository::class);
        $this->app->bind(ReferralRepositoryInterface::class, ReferralRepository::class);
        $this->app->bind(GameHistoryRepositoryInterface::class, GameHistoryRepository::class);
        $this->app->bind(BonusHistoryRepositoryInterface::class, BonusHistoryRepository::class);
        $this->app->bind(CryptoSettingRepositoryInterface::class, CryptoSettingRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(NoticeRepositoryInterface::class, NoticeRepository::class);
        $this->app->bind(ManualInputRepositoryInterface::class, ManualInputRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(AllianceRepositoryInterface::class, AllianceRepository::class);
        $this->app->bind(MaintenanceRepositoryInterface::class, MaintenanceRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
