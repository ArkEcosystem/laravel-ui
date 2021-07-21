<?php

namespace ARKEcosystem\UserInterface;

use ARKEcosystem\UserInterface\Components\ContactForm;
use ARKEcosystem\UserInterface\Components\FlashMessage;
use ARKEcosystem\UserInterface\Components\HoneyPot;
use ARKEcosystem\UserInterface\Components\Toast;
use ARKEcosystem\UserInterface\Http\Controllers\ImageCropController;
use ARKEcosystem\UserInterface\Http\Controllers\WysiwygControlller;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Spatie\Flash\Flash;

class UserInterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Flash::levels([
            'info'    => 'alert-info',
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'danger'  => 'alert-danger',
            'error'   => 'alert-error',
            'hint'    => 'alert-hint',
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerLoaders();

        $this->registerPublishers();

        $this->registerBladeComponents();

        $this->registerLivewireComponents();

        $this->registerRoutes();

        UI::bootErrorMessages();
    }

    /**
     * Register the loaders.
     *
     * @return void
     */
    public function registerLoaders(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ui');
    }

    /**
     * Register routes.
     *
     * @return void
     */
    public function registerRoutes(): void
    {
        Route::group(['prefix' => 'wysiwyg'], function () {
            Route::get('twitter-embed-code', [WysiwygControlller::class, 'getTwitterEmbedCode'])->name('wysiwyg.twitter');
            Route::post('upload-image', [WysiwygControlller::class, 'uploadImage'])->name('wysiwyg.upload-image')->middleware(['web', 'auth']);
        });

        Route::post('cropper/upload-image', ImageCropController::class)->name('cropper.upload-image')->middleware(['web', 'auth']);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    private function registerPublishers(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ark');

        $this->publishes([
            __DIR__.'/../config/ui.php' => config_path('ui.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/ui.php', 'ui'
        );

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/ark'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../resources/views/pagination.blade.php'     => resource_path('views/vendor/ark/pagination.blade.php'),
            __DIR__.'/../resources/views/pagination-url.blade.php' => resource_path('views/vendor/ark/pagination-url.blade.php'),
        ], 'pagination');

        $this->publishes([
            __DIR__.'/../resources/assets/js/markdown-editor' => resource_path('js/vendor/ark/markdown-editor'),
        ], 'wysiwyg');

        $this->publishes([
            __DIR__.'/../resources/assets/fonts' => resource_path('fonts'),
        ], 'fonts');

        $this->publishes([
            __DIR__.'/../resources/assets/css' => resource_path('css/vendor/ark'),
        ], 'css');

        $this->publishes([
            __DIR__.'/../resources/assets/icons' => resource_path('icons'),
        ], 'icons');

        $this->publishes([
            __DIR__.'/../resources/assets/images/components' => resource_path('images/vendor/ark'),
        ], 'images');

        $this->publishes([
            __DIR__.'/../resources/views/errors'         => resource_path('views/errors'),
            __DIR__.'/../resources/assets/images/errors' => resource_path('images/errors'),
        ], 'error-pages');

        // Individual JS files

        $this->publishes([
            __DIR__.'/../resources/assets/js/modal.js' => resource_path('js/vendor/ark/modal.js'),
        ], 'modal');

        $this->publishes([
            __DIR__.'/../resources/assets/js/page-scroll.js' => resource_path('js/vendor/ark/page-scroll.js'),
        ], 'page-scroll');

        $this->publishes([
            __DIR__.'/../resources/assets/js/rich-select.js' => resource_path('js/vendor/ark/rich-select.js'),
        ], 'tippy');

        $this->publishes([
            __DIR__.'/../resources/assets/js/prism-line-numbers.js' => resource_path('js/vendor/ark/prism-line-numbers.js'),
            __DIR__.'/../resources/assets/js/prism.js'              => resource_path('js/vendor/ark/prism.js'),
        ], 'prism');

        $this->publishes([
            __DIR__.'/../resources/assets/js/clipboard.js' => resource_path('js/vendor/ark/clipboard.js'),
        ], 'clipboard');

        $this->publishes([
            __DIR__.'/../resources/assets/js/reposition-dropdown.js' => resource_path('js/vendor/ark/reposition-dropdown.js'),
        ], 'dropdown');

        $this->publishes([
            __DIR__.'/../resources/assets/js/highlightjs-copy.js' => resource_path('js/vendor/ark/highlightjs-copy.js'),
        ], 'highlightjs');

        $this->publishes([
            __DIR__.'/../resources/assets/js/file-download.js' => resource_path('js/vendor/ark/file-download.js'),
        ], 'file-download');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    private function registerBladeComponents(): void
    {
        Blade::component('ark::inputs.checkbox', 'ark-checkbox');
        Blade::component('ark::inputs.date-picker', 'ark-date-picker');
        Blade::component('ark::inputs.input', 'ark-input');
        Blade::component('ark::inputs.input-with-icon', 'ark-input-with-icon');
        Blade::component('ark::inputs.input-with-prefix', 'ark-input-with-prefix');
        Blade::component('ark::inputs.radio', 'ark-radio');
        Blade::component('ark::inputs.textarea', 'ark-textarea');
        Blade::component('ark::inputs.toggle', 'ark-toggle');
        Blade::component('ark::inputs.select', 'ark-select');
        Blade::component('ark::inputs.upload', 'ark-upload');
        Blade::component('ark::inputs.password-toggle', 'ark-password-toggle');
        Blade::component('ark::inputs.rich-select', 'ark-rich-select');
        Blade::component('ark::inputs.markdown', 'ark-markdown');
        Blade::component('ark::inputs.user-tagger', 'ark-user-tagger');
        Blade::component('ark::inputs.switch', 'ark-switch');
        Blade::component('ark::inputs.tile-selection', 'ark-tile-selection');
        Blade::component('ark::inputs.time', 'ark-time');
        Blade::component('ark::inputs.upload-image-single', 'ark-upload-image-single');
        Blade::component('ark::inputs.upload-image-collection', 'ark-upload-image-collection');
        Blade::component('ark::inputs.tags', 'ark-tags');

        Blade::component('ark::pages.contact.content', 'ark-pages-contact-content');
        Blade::component('ark::pages.contact.header', 'ark-pages-contact-header');

        Blade::component('ark::pages.includes.markdown-scripts', 'ark-pages-includes-markdown-scripts');
        Blade::component('ark::pages.includes.crop-image-scripts', 'ark-pages-includes-crop-image-scripts');
        Blade::component('ark::pages.includes.compress-image-scripts', 'ark-pages-includes-compress-image-scripts');

        Blade::component('ark::tables.table', 'ark-tables.table');
        Blade::component('ark::tables.row', 'ark-tables.row');
        Blade::component('ark::tables.cell', 'ark-tables.cell');
        Blade::component('ark::tables.cell-status', 'ark-tables.cell-status');
        Blade::component('ark::tables.header', 'ark-tables.header');
        Blade::component('ark::tables.view-options', 'ark-tables.view-options');
        Blade::component('ark::tables.mobile.cell', 'ark-tables.mobile.cell');
        Blade::component('ark::tables.mobile.row', 'ark-tables.mobile.row');

        Blade::component('ark::accordion-group', 'ark-accordion-group');
        Blade::component('ark::accordion', 'ark-accordion');
        Blade::component('ark::alert', 'ark-alert');
        Blade::component('ark::alert-simple', 'ark-alert-simple');
        Blade::component('ark::avatar', 'ark-avatar');
        Blade::component('ark::breadcrumbs', 'ark-breadcrumbs');
        Blade::component('ark::clipboard', 'ark-clipboard');
        Blade::component('ark::code', 'ark-code');
        Blade::component('ark::code-lines', 'ark-code-lines');
        Blade::component('ark::container', 'ark-container');
        Blade::component('ark::description-block', 'ark-description-block');
        Blade::component('ark::description-block-link', 'ark-description-block-link');
        Blade::component('ark::details-box', 'ark-details-box');
        Blade::component('ark::details-box-mobile', 'ark-details-box-mobile');
        Blade::component('ark::divider', 'ark-divider');
        Blade::component('ark::dropdown', 'ark-dropdown');
        Blade::component('ark::expandable', 'ark-expandable');
        Blade::component('ark::expandable-item', 'ark-expandable-item');
        Blade::component('ark::external-link', 'ark-external-link');
        Blade::component('ark::external-link-confirm', 'ark-external-link-confirm');
        Blade::component('ark::flash', 'ark-flash');
        Blade::component('ark::footer-bar-desktop', 'ark-footer-bar-desktop');
        Blade::component('ark::footer-bar-mobile', 'ark-footer-bar-mobile');
        Blade::component('ark::footer-copyright', 'ark-footer-copyright');
        Blade::component('ark::footer-social', 'ark-footer-social');
        Blade::component('ark::footer', 'ark-footer');
        Blade::component('ark::horizontal-divider', 'ark-horizontal-divider');
        Blade::component('ark::icon', 'ark-icon');
        Blade::component('ark::icon-link', 'ark-icon-link');
        Blade::component('ark::image-tile', 'ark-image-tile');
        Blade::component('ark::info', 'ark-info');
        Blade::component('ark::js-modal', 'ark-js-modal');
        Blade::component('ark::logo', 'ark-logo');
        Blade::component('ark::logo-simple', 'ark-logo-simple');
        Blade::component('ark::loading-spinner', 'ark-loading-spinner');
        Blade::component('ark::spinner-icon', 'ark-spinner-icon');
        Blade::component('ark::message', 'ark-message');
        Blade::component('ark::metadata', 'ark-metadata');
        Blade::component('ark::metadata-tags', 'ark-metadata-tags');
        Blade::component('ark::modal', 'ark-modal');
        Blade::component('ark::no-results', 'ark-no-results');
        Blade::component('ark::outgoing-link', 'ark-outgoing-link');
        Blade::component('ark::pagination', 'ark-pagination');
        Blade::component('ark::pagination-url', 'ark-pagination-url');
        Blade::component('ark::read-more', 'ark-read-more');
        Blade::component('ark::secondary-menu', 'ark-secondary-menu');
        Blade::component('ark::sidebar-link', 'ark-sidebar-link');
        Blade::component('ark::simple-footer', 'ark-simple-footer');
        Blade::component('ark::slider-slide', 'ark-slider-slide');
        Blade::component('ark::slider', 'ark-slider');
        Blade::component('ark::social-link', 'ark-social-link');
        Blade::component('ark::social-square', 'ark-social-square');
        Blade::component('ark::sort-icon', 'ark-sort-icon');
        Blade::component('ark::status', 'ark-status');
        Blade::component('ark::status-circle', 'ark-status-circle');
        Blade::component('ark::status-circle-shallow', 'ark-status-circle-shallow');
        Blade::component('ark::svg-lazy', 'ark-svg-lazy');
        Blade::component('ark::toast', 'ark-toast');
        Blade::component('ark::shapes.line', 'ark-placeholder-line');
        Blade::component('ark::shapes.square', 'ark-placeholder-square');
        Blade::component('ark::link-collection', 'ark-link-collection');
        Blade::component('ark::file-download', 'ark-file-download');

        // Navigation
        Blade::component('ark::navbar', 'ark-navbar');
        Blade::component('ark::navbar.link-mobile', 'ark-navbar-link-mobile');

        // Font Loader
        Blade::component('ark::font-loader', 'ark-font-loader');

        // Honey Pot
        Blade::component('honey-pot', HoneyPot::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    private function registerLivewireComponents(): void
    {
        Livewire::component('contact-form', ContactForm::class);
        Livewire::component('flash-message', FlashMessage::class);
        Livewire::component('toast', Toast::class);
    }
}
