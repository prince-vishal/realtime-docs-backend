<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
* ServiceProvider
*
* The service provider for the modules. After being registered
* it will make sure that each of the modules are properly loaded
* i.e. with their routes, views etc.
*
* @author Prince Sinha  <vishal.prince30@gmail.com>
* @package App\Modules
*/
class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Will make sure that the required modules have been fully loaded
     * @return void
     */
    public function boot()
    {
        $ds = DIRECTORY_SEPARATOR;
        // For each of the registered modules, include their routes and Views
        $modules = config("module.modules");

        /**
         * We are moving out of explode and moving it to app path
         */
        $modulePath = $this->app->path.$ds.'Modules';

        foreach ($modules as $module) {

            // Load the routes for each of the modules
            if (file_exists($modulePath.$ds.$module.$ds.'routes.php')) {
                include $modulePath.$ds.$module.$ds.'routes.php';
            }

            // Load the views
            if (is_dir($modulePath.$ds.$module.$ds.'Views')) {
                $this->loadViewsFrom($modulePath.$ds.$module.$ds.'Views', $module);
            }
        }
    }

    public function register()
    {
    }
}
