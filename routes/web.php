<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\FeaturedProgramController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\GalleryAlbumController;
use App\Http\Controllers\Admin\GalleryPhotoController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\HomepageSectionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PasswordResetController;
use App\Http\Controllers\Admin\PpdbPeriodController;
use App\Http\Controllers\Admin\PpdbRequirementController;
use App\Http\Controllers\Admin\PpdbStepController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AchievementController as FrontendAchievementController;
use App\Http\Controllers\Frontend\AgendaController as FrontendAgendaController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\FacilityController as FrontendFacilityController;
use App\Http\Controllers\Frontend\PpdbController as FrontendPpdbController;
use App\Http\Controllers\Frontend\ContactController as FrontendContactController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ProgramController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\StaffController as FrontendStaffController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil-sekolah', [ProfileController::class, 'index'])->name('profile');
Route::get('/guru', [FrontendStaffController::class, 'index'])->name('staff');
Route::get('/guru/{staff}', [FrontendStaffController::class, 'show'])->name('staff.show');
Route::get('/program', [ProgramController::class, 'index'])->name('program');
Route::get('/program/{program:slug}', [ProgramController::class, 'show'])->name('program.show');
Route::get('/berita', [FrontendPostController::class, 'index'])->name('news');
Route::get('/berita/{post:slug}', [FrontendPostController::class, 'show'])->name('news.show');
Route::get('/prestasi', [FrontendAchievementController::class, 'index'])->name('achievement');
Route::get('/prestasi/{achievement:slug}', [FrontendAchievementController::class, 'show'])->name('achievement.show');
Route::get('/agenda', [FrontendAgendaController::class, 'index'])->name('agenda');
Route::get('/agenda/{agenda:slug}', [FrontendAgendaController::class, 'show'])->name('agenda.show');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery');
Route::get('/galeri/{gallery_album:slug}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/sarana-prasarana', [FrontendFacilityController::class, 'index'])->name('facility');
Route::get('/sarana-prasarana/{facility:slug}', [FrontendFacilityController::class, 'show'])->name('facility.show');
Route::get('/ppdb', [FrontendPpdbController::class, 'index'])->name('ppdb');
Route::get('/kontak', [FrontendContactController::class, 'index'])->name('contact');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'index'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');

        Route::get('/lupa-password', [PasswordResetController::class, 'create'])->name('password.request');
        Route::post('/lupa-password', [PasswordResetController::class, 'store'])->name('password.email');
        Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
        Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/akun', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('/akun', [AccountController::class, 'update'])->name('account.update');

        Route::put('school-profile/{school_profile}/content/{section}', [\App\Http\Controllers\Admin\SchoolProfileController::class, 'updateContent'])
            ->whereIn('section', ['about', 'history', 'vision-mission'])
            ->name('school-profile.content.update');

        Route::resource('school-profile', \App\Http\Controllers\Admin\SchoolProfileController::class)
            ->parameters(['school-profile' => 'school_profile']);

        Route::resource('principal-message', \App\Http\Controllers\Admin\PrincipalMessageController::class)
            ->parameters(['principal-message' => 'principal_message']);

        Route::resource('staff', StaffController::class)->except('show');

        Route::resource('programs', FeaturedProgramController::class)->except('show');

        Route::resource('hero-slides', HeroSlideController::class)->except('show');

        Route::resource('homepage-sections', HomepageSectionController::class)->except('show');

        Route::resource('post-categories', PostCategoryController::class)->except('show');

        Route::resource('posts', PostController::class)->except('show');

        Route::resource('achievements', AchievementController::class)->except('show');

        Route::resource('agendas', AgendaController::class)->except('show');

        Route::resource('gallery-albums', GalleryAlbumController::class)->except('show');

        Route::resource('gallery-photos', GalleryPhotoController::class)->except('show');

        Route::resource('facilities', FacilityController::class)->except('show');

        Route::resource('ppdb-periods', PpdbPeriodController::class)->except('show');

        Route::resource('ppdb-requirements', PpdbRequirementController::class)->except('show');

        Route::resource('ppdb-steps', PpdbStepController::class)->except('show');

        Route::resource('contacts', ContactController::class)->except('show');

        Route::resource('social-links', SocialLinkController::class)->except('show');

        Route::resource('site-settings', SiteSettingController::class)->except('show');

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
