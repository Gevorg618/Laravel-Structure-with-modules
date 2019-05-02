<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class TicketServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\TicketContract',
            'Modules\Admin\Repositories\Ticket\TicketRepository'
        );

        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\OrderContract',
            'Modules\Admin\Repositories\Ticket\OrderRepository'
        );

        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\ActivityContract',
            'Modules\Admin\Repositories\Ticket\ActivityRepository'
        );

        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\StatusContract',
            'Modules\Admin\Repositories\Ticket\StatusRepository'
        );

        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\CategoryContract',
            'Modules\Admin\Repositories\Ticket\CategoryRepository'
        );

        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\OrderDocumentContract',
            'Modules\Admin\Repositories\Ticket\OrderDocumentRepository'
        );

        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\TicketContentContract',
            'Modules\Admin\Repositories\Ticket\TicketContentRepository'
        );

        $this->app->bind(
            'Modules\Admin\Contracts\Ticket\TicketFileContract',
            'Modules\Admin\Repositories\Ticket\TicketFileRepository'
        );
    }
}
