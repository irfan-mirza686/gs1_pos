<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

use App\Repositories\Interfaces\ProductTypeRepositoryInterface;
use App\Repositories\ProductTypeRepository;

use App\Repositories\Interfaces\SegmentRepositoryInterface;
use App\Repositories\SegmentRepository;

use App\Repositories\Interfaces\FamilyRepositoryInterface;
use App\Repositories\FamilyRepository;

use App\Repositories\Interfaces\ClassRepositoryInterface;
use App\Repositories\ClassRepository;

use App\Repositories\Interfaces\BrickRepositoryInterface;
use App\Repositories\BrickRepository;

use App\Repositories\Interfaces\AttributeRepositoryInterface;
use App\Repositories\AttributeRepository;

use App\Repositories\Interfaces\AttributeValuesRepositoryInterface;
use App\Repositories\AttributeValuesRepository;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\CountryRepository;

use App\Repositories\Interfaces\SubscriberRepositoryInterface;
use App\Repositories\SubscriberRepository;

use App\Repositories\Interfaces\JournalRepositoryInterface;
use App\Repositories\JournalRepository;

use App\Repositories\Interfaces\PackagesRepositoryInterface;
use App\Repositories\PackagesRepository;

use App\Repositories\Interfaces\UnitRepositoryInterface;
use App\Repositories\UnitRepository;

use App\Repositories\Interfaces\AdminProfileInterface;
use App\Repositories\AdminProfileRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
        $this->app->bind(ProductTypeRepositoryInterface::class,ProductTypeRepository::class);
        $this->app->bind(SegmentRepositoryInterface::class,SegmentRepository::class);
        $this->app->bind(FamilyRepositoryInterface::class,FamilyRepository::class);
        $this->app->bind(ClassRepositoryInterface::class,ClassRepository::class);
        $this->app->bind(BrickRepositoryInterface::class,BrickRepository::class);
        $this->app->bind(AttributeRepositoryInterface::class,AttributeRepository::class);
        $this->app->bind(AttributeValuesRepositoryInterface::class,AttributeValuesRepository::class);
        $this->app->bind(ProductRepositoryInterface::class,ProductRepository::class);
        $this->app->bind(CountryRepositoryInterface::class,CountryRepository::class);
        $this->app->bind(SubscriberRepositoryInterface::class,SubscriberRepository::class);
        $this->app->bind(JournalRepositoryInterface::class,JournalRepository::class);
        $this->app->bind(PackagesRepositoryInterface::class,PackagesRepository::class);
        $this->app->bind(UnitRepositoryInterface::class,UnitRepository::class);
        $this->app->bind(AdminProfileInterface::class,AdminProfileRepository::class);
    
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
