<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Alumni\AlumniController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\Tracer\TracerStudyController;
use App\Http\Controllers\Admin\Tracer\AdminTracerController;
use App\Http\Controllers\Alumni\ProfileController;
use App\Http\Controllers\Alumni\ExperienceController;
use App\Http\Controllers\Alumni\EducationController;
use App\Http\Controllers\Alumni\VerificationController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\Admin\ScholarshipTabController;
use App\Http\Controllers\Admin\ScholarshipFormController;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
    })->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/news', [NewsPostController::class, 'index'])->name('news.index');
        Route::get('/news/create', [NewsPostController::class, 'create'])->name('news.create');
        Route::post('/news', [NewsPostController::class, 'store'])->name('news.store');
        Route::delete('/news/{post}', [NewsPostController::class, 'destroy'])->name('news.destroy');
        Route::get('/news/{post}/edit', [NewsPostController::class, 'edit'])->name('news.edit');
        Route::put('/news/{post}', [NewsPostController::class, 'update'])->name('news.update');
    });
});

route::get('/home', [IndexController::class,'index']);

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::resource('users', UserController::class);
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['verified'])->group(function () {
    Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
    Route::get('/threads/{thread}', [ThreadController::class, 'show'])->name('threads.show');
    Route::get('/threads/{thread}/reaction-status', [ThreadController::class, 'getReactionStatus']);
    Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
    Route::post('/threads/{thread}/comments', [ThreadController::class, 'storeComment'])->name('threads.comments.store');
    Route::post('/threads/{thread}/react', [ThreadController::class, 'react'])->name('threads.react');
    Route::resource('threads', ThreadController::class);
});

Route::post('/threads/check-content', [App\Http\Controllers\ThreadController::class, 'checkContent'])->name('threads.check-content');
Route::post('/comments/check-content', [App\Http\Controllers\CommentController::class, 'checkContent'])->name('comments.check-content');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/chatbot', [ChatbotController::class, 'chat']);
});

Route::get('/contact-directory', function () {
    return view('contact-directory');
})->name('contact-directory');

Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

Route::group(['middleware' => 'admin'], function () {
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/create', [AlumniController::class, 'create'])->name('alumni.create');
    Route::post('/alumni', [AlumniController::class, 'store'])->name('alumni.store');
    Route::get('/alumni/{alumnus}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::get('/alumni/{alumnus}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumnus}', [AlumniController::class, 'update'])->name('alumni.update');
    Route::delete('/alumni/{alumnus}', [AlumniController::class, 'destroy'])->name('alumni.destroy');
});

Route::get('/tracer-study', [TracerStudyController::class, 'showForm'])->name('tracer-study.form');
Route::post('/tracer-study', [TracerStudyController::class, 'submitForm'])->name('tracer-study.submit');
Route::get('/tracer-study/thank-you', [TracerStudyController::class, 'thankYou'])->name('tracer-study.thank-you');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/pending-responses', [AdminTracerController::class, 'index'])->name('admin.pending-responses');
    Route::get('/admin/pending-responses/{response}', [AdminTracerController::class, 'show']);
    Route::get('/admin/pending-responses/{response}/edit', [AdminTracerController::class, 'edit']);
    Route::post('/admin/pending-responses/{response}', [AdminTracerController::class, 'update']);
    Route::post('/admin/pending-responses/{response}/approve', [AdminTracerController::class, 'approve'])->name('admin.approve');
    Route::post('/admin/pending-responses/{response}/reject', [AdminTracerController::class, 'reject'])->name('admin.reject');
});

// Alumni new profile creation

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/experience', [ProfileController::class, 'addExperience'])->name('experience.add');
    Route::post('/profile/education', [ProfileController::class, 'addEducation'])->name('education.add');
    Route::delete('/profile/experience/{id}', [ProfileController::class, 'destroyExperience'])->name('profile.destroyExperience');
    Route::delete('/profile/education/{id}', [ProfileController::class, 'destroyEducation'])->name('profile.destroyEducation');

    Route::post('/verification-request', [VerificationController::class, 'store'])->name('verification.request');
    Route::delete('/verification/{verificationRequest}/cancel', [VerificationController::class, 'cancel'])->name('verification.cancel');
    Route::get('/alumni-profile/{user}', [ProfileController::class, 'show'])->name('alumni.profile.show');
    Route::get('/alumni-profiles', [ProfileController::class, 'index'])->name('alumni.all-profiles.index');

    Route::post('/profile/accept-eula', [App\Http\Controllers\Alumni\ProfileController::class, 'acceptEula'])->name('profile.accept-eula');
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::get('/verification-requests', [VerificationController::class, 'index'])->name('verification-requests.index');
    Route::put('/verification-request/{verificationRequest}/review', [VerificationController::class, 'review'])->name('verification.review');
    Route::put('/verification-requests/{verificationRequest}', [VerificationController::class, 'review'])->name('verification-requests.review');
    
    Route::get('/verification-requests', [VerificationController::class, 'index'])->name('verification.index');
    Route::get('/verification-documents/{id}', [VerificationController::class, 'showDocument'])->name('verification.show-document');
    Route::put('/verification-requests/{verificationRequest}/approve', [VerificationController::class, 'approve'])->name('verification.approve');
    Route::put('/verification-requests/{verificationRequest}/reject', [VerificationController::class, 'reject'])->name('verification.reject');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});


// Admin Scholarship Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Form management routes - place these BEFORE the specific application routes
    Route::get('/scholarships/forms', [ScholarshipController::class, 'formManagement'])->name('scholarships.forms');
    Route::post('/scholarships/forms', [ScholarshipController::class, 'uploadForm'])->name('scholarships.upload-form');
    Route::delete('/scholarships/forms/{form}', [ScholarshipController::class, 'deleteForm'])->name('scholarships.delete-form');
    Route::put('/scholarships/forms/{form}/toggle-status', [ScholarshipController::class, 'toggleFormStatus'])->name('scholarships.toggle-form-status');
    
    // Existing scholarship routes
    Route::get('/scholarships', [ScholarshipController::class, 'adminIndex'])->name('scholarships.index');
    Route::get('/scholarships/{application}', [ScholarshipController::class, 'adminShow'])->name('scholarships.show');
    Route::put('/scholarships/{application}/review', [ScholarshipController::class, 'markUnderReview'])->name('scholarships.review');
    Route::put('/scholarships/{application}/approve', [ScholarshipController::class, 'approve'])->name('scholarships.approve');
    Route::put('/scholarships/{application}/reject', [ScholarshipController::class, 'reject'])->name('scholarships.reject');
});

// User-facing scholarship routes
Route::get('/scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
Route::get('/scholarships/apply', [ScholarshipController::class, 'showApplicationForm'])->name('scholarships.apply');
Route::post('/scholarships/apply', [ScholarshipController::class, 'storeApplication'])->name('scholarships.store-application');
Route::get('/scholarships/download/{formId?}', [ScholarshipController::class, 'downloadForm'])->name('scholarships.download-form');
Route::get('/scholarships/document/{document}', [ScholarshipController::class, 'viewDocument'])->name('scholarships.view-document');

Route::get('/favicon.ico', function () {
    return Response::file(public_path('favicon.ico'));
});

Route::middleware(['auth'])->group(function () {
    // Comment routes
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});