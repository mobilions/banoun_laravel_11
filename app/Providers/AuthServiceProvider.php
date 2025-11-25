<?php



namespace App\Providers;



use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Schema;

use Laravel\Passport\Passport;



class AuthServiceProvider extends ServiceProvider

{

    /**

     * The policy mappings for the application.

     *

     * @var array

     */

    protected $policies = [

        'App\Model' => 'App\Policies\ModelPolicy',

    ];



    /**

     * Register any authentication / authorization services.

     *

     * @return void

     */

    public function boot()

    {

        // Enable password grant for Passport (so /oauth/token supports password)
        Passport::enablePasswordGrant();
        $this->registerPolicies();

        Schema::defaultStringLength(191);

    }

}

