<?php

use App\Models\StudentRecord;
use App\Models\Tenant;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DisapprovalNotification;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MpesaController;
use App\Notifications\SystemNotification;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\NotificationController;

// Removed Auth::routes() as we're using Fortify for authentication now

// Tenancy-based routes
Route::middleware(['tenant'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Create a tenant (school)
Route::get('/create-school/{name}', function ($name) {
    $tenant = Tenant::create([
        'school_name' => $name,
        'domain' => "{$name}.localhost"
    ]);

    return "Tenant created: {$tenant->school_name}";
});

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    
   
});

Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessagingController::class, 'index'])->name('messages.index');
});

// mpesa
Route::post('mpesa/callback', [MpesaController::class, 'callback'])->name('mpesa.callback');

//Route::get('/test', 'TestController@index')->name('test');
Route::get('/privacy-policy', 'HomeController@privacy_policy')->name('privacy_policy');
Route::get('/terms-of-use', 'HomeController@terms_of_use')->name('terms_of_use');

//route for the admission number
//Route::get('/check-admission-number/{number}', 'AdmissionController@checkNumber');
Route::get('/get-session', 'SessionController@getSession')->name('get-session');


Route::get('/student-info/{id}', function ($id) {
    return view('pages.support_team.students.student_info', ['student' => $id]);
})->name('student.info');

// Exam management routes
Route::group(['middleware' => ['auth', 'administrator_teacher']], function () {
    Route::get('/pages/support_team/exams/grades', function () {
        return view('pages.support_team.exams.grades');
    })->name('exams.grades');

    Route::get('/pages/support_team/exams/assign-exam-marks', function () {
        return view('pages.support_team.exams.assign-exam-marks');
    })->name('exams.assignExamMarks');

    Route::get('/pages/support_team/exams/set', function () {
        return view('pages.support_team.exams.set');
    })->name('exams.set');
    
    
    // New modern Google-inspired exam management system
    Route::get('/exams/modern-management', function () {
        return view('pages.exams.modern-management');
    })->name('exams.modern-management');
});

// Student promotion routes - accessible by administrators
Route::group(['middleware' => ['auth', 'administrator']], function () {
    Route::get('/pages/support_team/students/promotions_demotions', function () {
        return view('pages.support_team.students.promotions_demotions');
    })->name('students.promotions_demotions');

    Route::get('/pages/support_team/students/graduation', function () {
        return view('pages.support_team.students.graduation');
    })->name('students.graduation');

    Route::get('/pages/support_team/students/manage-students', function () {
        return view('pages.support_team.students.manage-students');
    })->name('students.manage-students');
});

// Parent routes - accessible by parents
Route::group(['middleware' => ['auth', 'parent']], function () {
    Route::get('/pages/parent/child-class', function () {
        return view('pages.parent.child-class');
    })->name('parent.child-class');

    Route::get('/pages/parent/child-dorm', function () {
        return view('pages.parent.child-dorm');
    })->name('parent.child-dorm');

    Route::get('/pages/parent/child-transition-status', function () {
        return view('pages.parent.child-transition-status');
    })->name('parent.child-transition-status');

    Route::get('/pages/parent/child-graduation', function () {
        return view('pages.parent.child-graduation');
    })->name('parent.child-graduation');

    Route::get('/pages/parent/child-exams', function () {
        return view('pages.parent.child-exams');
    })->name('parent.child-exams');

    Route::get('/pages/parent/child-marks', function () {
        return view('pages.parent.child-marks');
    })->name('parent.child-marks');
});

//Route to view class details
Route::resource('view-class', 'ViewClassController');
Route::get('view-class', 'ViewClassController@index')->name('view-class.index');
Route::get('view-class/{id}', 'ViewClassController@show')->name('view-class.show');

Route::resource('classmasters', 'ClassMasterController');
Route::get('classmasters/{id}/edit', 'ClassMasterController@edit')->name('classmasters.edit');
Route::put('classmasters/{id}', 'ClassMasterController@update')->name('classmasters.update');


Route::resource('dormasters', 'DormMasterController');
Route::get('dormasters/{id}/edit', 'DormMasterController@edit')->name('dormasters.edit');
Route::put('dormasters/{id}', 'DormMasterController@update')->name('dormasters.update');


Route::resource('grading_system', 'GradingSystemController')->name('grading_system', ['except' => ['show']]);
Route::group(['prefix' => 'grading_system/{grading_system}'], function () {
    Route::resource('subject-ranges', 'SubjectRangesController')->except(['show'])->names('subject-ranges');
    Route::get('subject-ranges/{subject_range}', 'SubjectRangesController@show')->name('subject-ranges.show');
    Route::put('subject-ranges/{subject_range}', 'SubjectRangesController@edit')->name('subject-ranges.edit');
});

//routes for the external site pages before logging in
Route::get('/', 'HomeController@landingpage');
Route::get('/landing', 'HomeController@landingpage')->name('landing');
Route::get('/pricing', 'HomeController@pricingpage')->name('pricing');
Route::get('/contact', 'HomeController@contactpage')->name('contact');
// Modified to use Fortify's register route
Route::get('/signup', 'HomeController@signuppage')->name('signup');

// Remove redundant auth routes as they're handled by Fortify now
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@dashboard')->name('home');
    Route::get('/home', 'HomeController@dashboard')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::group(['prefix' => 'my_account'], function () {
        Route::get('/', 'MyAccountController@edit_profile')->name('my_account');
        Route::put('/update', 'MyAccountController@update_profile')->name('my_account.update');
        Route::put('/change_pass', 'MyAccountController@change_pass')->name('my_account.change_pass');
    });

    /*************** Support Team *****************/
    Route::group(['namespace' => 'SupportTeam',], function () {

        /*************** Students *****************/
        Route::group(['prefix' => 'students'], function () {
            Route::get('reset_pass/{st_id}', 'StudentRecordController@reset_pass')->name('st.reset_pass')->middleware('administrator');
            Route::get('graduated', 'StudentRecordController@graduated')->name('students.graduated');
            Route::put('not_graduated/{id}', 'StudentRecordController@not_graduated')->name('st.not_graduated')->middleware('administrator');
            Route::get('list/{class_id}', 'StudentRecordController@listByClass')->name('students.list')->middleware('administrator_teacher');

            /* Promotions */
            Route::group(['middleware' => 'administrator'], function () {
                Route::post('promote_selector', 'PromotionController@selector')->name('students.promote_selector');
                Route::get('promotion/manage', 'PromotionController@manage')->name('students.promotion_manage');
                Route::delete('promotion/reset/{pid}', 'PromotionController@reset')->name('students.promotion_reset');
                Route::delete('promotion/reset_all', 'PromotionController@reset_all')->name('students.promotion_reset_all');
                Route::get('promotion/{fc?}/{fs?}/{tc?}/{ts?}', 'PromotionController@promotion')->name('students.promotion');
                Route::post('promote/{fc}/{fs}/{tc}/{ts}', 'PromotionController@promote')->name('students.promote');
            });
        });

        /*************** Users *****************/
        Route::group(['prefix' => 'users', 'middleware' => 'administrator'], function () {
            Route::get('reset_pass/{id}', 'UserController@reset_pass')->name('users.reset_pass')->middleware('super_admin');
        });

        /*************** TimeTables *****************/
        Route::group(['prefix' => 'timetables'], function () {
            Route::get('/', 'TimeTableController@index')->name('tt.index');

            Route::group(['middleware' => 'administrator'], function () {
                Route::post('/', 'TimeTableController@store')->name('tt.store');
                Route::put('/{tt}', 'TimeTableController@update')->name('tt.update');
                Route::delete('/{tt}', 'TimeTableController@delete')->name('tt.delete');
            });

            // New modern timetable manager
            Route::get('/manager', function() {
                return view('pages.support_team.timetables.manager');
            })->name('tt.manager');

            // Timetable export routes
            Route::get('/export/pdf/{timetableId}/{sectionId?}', [App\Http\Controllers\TimetableExportController::class, 'exportPdf'])->name('tt.export.pdf');
            Route::get('/export/excel/{timetableId}/{sectionId?}', [App\Http\Controllers\TimetableExportController::class, 'exportExcel'])->name('tt.export.excel');
            Route::get('/print/{timetableId}/{sectionId?}', [App\Http\Controllers\TimetableExportController::class, 'printView'])->name('tt.print');

            // Consolidated Timetable routes
            Route::get('/consolidator', function() {
                return view('pages.support_team.timetables.consolidator');
            })->name('tt.consolidator');

            // Consolidated Timetable export routes
            Route::get('/consolidated/export/pdf', [App\Http\Controllers\ConsolidatedTimetableController::class, 'exportPdf'])->name('tt.consolidated.export.pdf');
            Route::get('/consolidated/export/excel', [App\Http\Controllers\ConsolidatedTimetableController::class, 'exportExcel'])->name('tt.consolidated.export.excel');
            Route::get('/consolidated/print', [App\Http\Controllers\ConsolidatedTimetableController::class, 'printView'])->name('tt.consolidated.print');
            
            // Exam Timetable routes
            Route::get('/exam-timetable', function() {
                return view('pages.support_team.timetables.exam-timetable');
            })->name('exam.timetable');
            
            // Exam Timetable export routes
            Route::get('/exam-timetable/export/pdf/{examId}/{classId}/{sectionId?}', [App\Http\Controllers\ExamTimetableController::class, 'exportPdf'])->name('exam.timetable.export.pdf');
            Route::get('/exam-timetable/export/excel/{examId}/{classId}/{sectionId?}', [App\Http\Controllers\ExamTimetableController::class, 'exportExcel'])->name('exam.timetable.export.excel');
            Route::get('/exam-timetable/print/{examId}/{classId}/{sectionId?}', [App\Http\Controllers\ExamTimetableController::class, 'printView'])->name('exam.timetable.print');

            /*************** TimeTable Records *****************/
            Route::group(['prefix' => 'records'], function () {

                Route::group(['middleware' => 'administrator'], function () {
                    Route::get('manage/{ttr}', 'TimeTableController@manage')->name('ttr.manage');
                    Route::post('/', 'TimeTableController@store_record')->name('ttr.store');
                    Route::get('edit/{ttr}', 'TimeTableController@edit_record')->name('ttr.edit');
                    Route::put('/{ttr}', 'TimeTableController@update_record')->name('ttr.update');
                });

                Route::get('show/{ttr}', 'TimeTableController@show_record')->name('ttr.show');
                Route::get('print/{ttr}', 'TimeTableController@print_record')->name('ttr.print');
                Route::delete('/{ttr}', 'TimeTableController@delete_record')->name('ttr.destroy')->middleware('administrator');
            });
            /*************** Time Slots *****************/
            Route::group(['prefix' => 'time_slots', 'middleware' => 'administrator'], function () {
                Route::post('/', 'TimeTableController@store_time_slot')->name('ts.store');
                Route::post('/use/{ttr}', 'TimeTableController@use_time_slot')->name('ts.use');
                Route::get('edit/{ts}', 'TimeTableController@edit_time_slot')->name('ts.edit');
                Route::delete('/{ts}', 'TimeTableController@delete_time_slot')->name('ts.destroy');
                Route::put('/{ts}', 'TimeTableController@update_time_slot')->name('ts.update');
            });
        });
        /*************** Payments *****************/
        Route::group(['prefix' => 'payments'], function () {
            Route::get('manage/{class_id?}', 'PaymentController@manage')->name('payments.manage')->middleware('accountant');
            Route::get('invoice/{id}/{year?}', 'PaymentController@invoice')->name('payments.invoice');
            Route::get('receipts/{id}', 'PaymentController@receipts')->name('payments.receipts');
            Route::get('pdf_receipts/{id}', 'PaymentController@pdf_receipts')->name('payments.pdf_receipts');
            Route::post('select_year', 'PaymentController@select_year')->name('payments.select_year');
            Route::post('select_class', 'PaymentController@select_class')->name('payments.select_class');
            Route::delete('reset_record/{id}', 'PaymentController@reset_record')->name('payments.reset_record')->middleware('accountant');
            Route::post('pay_now/{id}', 'PaymentController@pay_now')->name('payments.pay_now')->middleware('accountant');
        });

        /*************** Marks *****************/
        Route::group(['prefix' => 'marks'], function () {

            // FOR administrator
            Route::group(['middleware' => 'administrator'], function () {
                Route::get('batch_fix', 'MarkController@batch_fix')->name('marks.batch_fix');
                Route::put('batch_update', 'MarkController@batch_update')->name('marks.batch_update');
                Route::get('tabulation/{exam?}/{class?}/{sec_id?}', 'MarkController@tabulation')->name('marks.tabulation');
                Route::post('tabulation', 'MarkController@tabulation_select')->name('marks.tabulation_select');
                Route::get('tabulation/print/{exam}/{class}/{sec_id}', 'MarkController@print_tabulation')->name('marks.print_tabulation');
            });

            // FOR administrator_teacher
            Route::group(['middleware' => 'administrator_teacher'], function () {
                Route::get('/', 'MarkController@index')->name('marks.index');
                Route::get('manage/{exam}/{class}/{section}/{subject}', 'MarkController@manage')->name('marks.manage');
                Route::put('update/{exam}/{class}/{section}/{subject}', 'MarkController@update')->name('marks.update');
                Route::put('comment_update/{exr_id}', 'MarkController@comment_update')->name('marks.comment_update');
                Route::put('skills_update/{skill}/{exr_id}', 'MarkController@skills_update')->name('marks.skills_update');
                Route::post('selector', 'MarkController@selector')->name('marks.selector');
                Route::get('bulk/{class?}/{section?}', 'MarkController@bulk')->name('marks.bulk');
                Route::post('bulk', 'MarkController@bulk_select')->name('marks.bulk_select');
            });

            Route::get('select_year/{id}', 'MarkController@year_selector')->name('marks.year_selector');
            Route::post('select_year/{id}', 'MarkController@year_selected')->name('marks.year_select');
            Route::get('show/{id}/{year}', 'MarkController@show')->name('marks.show');
            Route::get('print/{id}/{exam_id}/{year}', 'MarkController@print_view')->name('marks.print');
        });

        // Resource routes
        Route::group(['middleware' => 'administrator'], function () {
            Route::resource('students', 'StudentRecordController', ['except' => ['index', 'show']]);
            Route::resource('users', 'UserController', ['except' => ['show']]);
            Route::resource('classes', 'MyClassController');
            Route::resource('sections', 'SectionController');
            Route::resource('subjects', 'SubjectController');
            Route::resource('grades', 'GradeController');
            Route::resource('dorms', 'DormController');
        });

        // Routes accessible by administrators and teachers
        Route::group(['middleware' => 'administrator_teacher'], function () {
            Route::resource('exams', 'ExamController');
        });

        // Routes accessible by accountants
        Route::group(['middleware' => 'accountant'], function () {
            Route::resource('payments', 'PaymentController', ['except' => ['show']]);
        });

        // Routes accessible by all authenticated users
        Route::get('students', 'StudentRecordController@index')->name('students.index');
        Route::get('students/{id}', 'StudentRecordController@show')->name('students.show');
        Route::get('users/{id}', 'UserController@show')->name('users.show');
        Route::get('payments/{id}', 'PaymentController@show')->name('payments.show');
    });
});

// Student routes - accessible by students
Route::group(['middleware' => ['auth', 'student']], function () {
    Route::get('/pages/student/my-exams', function () {
        return view('pages.student.my-exams');
    })->name('student.my-exams');

    Route::get('/pages/student/my-marks', function () {
        return view('pages.student.my-marks');
    })->name('student.my-marks');
});

/************************ SUPER ADMIN ****************************/
Route::group(['namespace' => 'SuperAdmin', 'middleware' => 'super_admin', 'prefix' => 'super_admin'], function () {
    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::put('/settings', 'SettingController@update')->name('settings.update');

    // New Livewire School Settings Route
    Route::get('/school-settings', function() {
        return view('pages.super_admin.school-settings', [
            'title' => 'School Settings',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'link' => route('dashboard')],
                ['label' => 'School Settings', 'link' => null]
            ]
        ]);
    })->name('school.settings');
});

Route::get('/demo', function () {
    return view('outerpages.demo');
})->name('demo');

Route::post('/demo/book', [App\Http\Controllers\DemoController::class, 'bookDemo'])->name('demo.book');

// Contact form submission route
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

// Student Import Template route
Route::get('/download/student-import-template', function() {
    return response()->download(public_path('downloads/student_import_template.xlsx'));
})->name('download.template')->middleware('auth');

// Finance Routes
Route::group(['prefix' => 'finance', 'middleware' => 'auth'], function () {
    // Finance Dashboard - Admin, Accountant
    Route::get('/dashboard', function () {
        return view('pages.finance.dashboard');
    })->name('finance.dashboard')->middleware('administrator_accountant');

    // Finance Accounts - Admin, Accountant
    Route::get('/accounts', function () {
        return view('pages.finance.accounts');
    })->name('finance.accounts')->middleware('administrator_accountant');

    // Voteheads - Admin, Accountant
    Route::get('/voteheads', function () {
        return view('pages.finance.voteheads');
    })->name('finance.voteheads')->middleware('administrator_accountant');

    // Fee Allocations - Admin, Accountant
    Route::get('/fee-allocations', function () {
        return view('pages.finance.fee-allocations');
    })->name('finance.fee-allocations')->middleware('administrator_accountant');

    // Fee Structure - Available to all users (permissions handled in component)
    Route::get('/fee-structure', function () {
        return view('pages.finance.fee-structure');
    })->name('finance.fee-structure');

    // Payment Vouchers - Admin, Accountant
    Route::get('/payment-vouchers', function () {
        return view('pages.finance.payment-vouchers');
    })->name('finance.payment-vouchers')->middleware('administrator_accountant');

    // Student Fee Payments - Available to all users (permissions handled in component)
    Route::get('/student-fee-payments', function () {
        return view('pages.finance.student-fee-payments');
    })->name('finance.student-fee-payments');

    // Parent route to view children's fee payments
    Route::get('/student-fee-payments/my-children', function () {
        return view('pages.finance.student-fee-payments', ['view_type' => 'my_children']);
    })->name('finance.student-fee-payments.my-children')->middleware(['auth', 'parent']);

    // Student Arrears - Available to all users (permissions handled in component)
    Route::get('/student-arrears', function () {
        return view('pages.finance.student-arrears');
    })->name('finance.student-arrears');

    // Student route to view their own arrears
    Route::get('/student-arrears/my-arrears', function () {
        return view('pages.finance.student-arrears', ['view_type' => 'my_arrears']);
    })->name('finance.arrears.my-arrears')->middleware(['auth', 'student']);

    // Parent route to view children's arrears
    Route::get('/student-arrears/my-children', function () {
        return view('pages.finance.student-arrears', ['view_type' => 'my_children']);
    })->name('finance.arrears.my-children')->middleware(['auth', 'parent']);

    // Student route to view their fee structure
    Route::get('/fee-structure/student', function () {
        return view('pages.finance.fee-structure', ['view_type' => 'student']);
    })->name('finance.fee-structure.view-for-student')->middleware(['auth', 'student']);

    // Parent route to view children's fee structure
    Route::get('/fee-structure/children', function () {
        return view('pages.finance.fee-structure', ['view_type' => 'children']);
    })->name('finance.fee-structure.view-for-children')->middleware(['auth', 'parent']);

    // Fee Payment - Available to all users (permissions handled in component)
    Route::get('/fee-payment', function () {
        return view('pages.finance.fee-payment');
    })->name('finance.fee-payment');
});

// Attendance Routes
Route::group(['prefix' => 'attendance', 'middleware' => 'auth'], function () {
    // Take Attendance - Admin, Teachers
    Route::get('/take', function () {
        return view('pages.attendance.take-attendance');
    })->name('attendance.take')->middleware('administrator_teacher');

    // Take Attendance for specific class/section - Admin, Teachers
    Route::get('/take/{class_id}/{section_id}', function ($class_id, $section_id) {
        return view('pages.attendance.take-attendance', [
            'class_id' => $class_id,
            'section_id' => $section_id,
        ]);
    })->name('attendance.take.class')->middleware('administrator_teacher');

    // View Attendance - All authenticated users (permissions handled in component)
    Route::get('/view', function () {
        return view('pages.attendance.view-attendance');
    })->name('attendance.view');

    // View Attendance for specific class/section - All authenticated users
    Route::get('/view/{class_id}/{section_id}', function ($class_id, $section_id) {
        return view('pages.attendance.view-attendance', [
            'class_id' => $class_id,
            'section_id' => $section_id,
        ]);
    })->name('attendance.view.class');

    // Student Attendance History - All authenticated users (permissions handled in component)
    Route::get('/student-history/{student_id?}', function ($student_id = null) {
        return view('pages.attendance.student-attendance-history', [
            'student_id' => $student_id,
        ]);
    })->name('attendance.student.history');
    
    // Student Attendance Export
    Route::get('/student-export/{student_id}', [App\Http\Controllers\SupportTeam\AttendanceController::class, 'exportStudentAttendance'])
        ->name('attendance.student.export');

    // Attendance Analytics - Admin only
    Route::get('/analytics', function () {
        return view('pages.attendance.attendance-analytics');
    })->name('attendance.analytics')->middleware('administrator');
    
    // Attendance Report
    Route::get('/report', [App\Http\Controllers\SupportTeam\AttendanceController::class, 'attendanceReport'])
        ->name('attendance.report')
        ->middleware('administrator_teacher');
});

// Library Routes
Route::group(['prefix' => 'library', 'middleware' => 'auth'], function () {
    // Books Management
    Route::get('/books', 'App\Http\Controllers\Library\LibraryController@books')
        ->name('library.books')
        ->middleware('role:librarian,admin,super_admin,teacher');
        
    // Export books to Excel
    Route::get('/books/export', 'App\Http\Controllers\Library\LibraryController@exportBooks')
        ->name('library.books.export')
        ->middleware('role:librarian,teacher,admin,super_admin');

    // Categories Management
    Route::get('/categories', 'App\Http\Controllers\Library\LibraryController@categories')
        ->name('library.categories')
        ->middleware('role:librarian,admin,super_admin');
        
    // Authors Management
    Route::get('/authors', 'App\Http\Controllers\Library\LibraryController@authors')
        ->name('library.authors')
        ->middleware('role:librarian,admin,super_admin');
        
    // Inventory Management
    Route::get('/inventory', 'App\Http\Controllers\Library\LibraryController@inventory')
        ->name('library.inventory')
        ->middleware('role:librarian,admin,super_admin');
        
    // Loans Management
    Route::get('/loans', 'App\Http\Controllers\Library\LibraryController@loans')
        ->name('library.loans')
        ->middleware('role:librarian,admin,super_admin');
        
    // Book Requests Management 
    Route::get('/book-requests', 'App\Http\Controllers\Library\LibraryController@bookRequests')
        ->name('library.book-requests')
        ->middleware('role:librarian,admin,super_admin');
        
    // Library Reports
    Route::get('/reports', 'App\Http\Controllers\Library\LibraryController@reports')
        ->name('library.reports')
        ->middleware('role:librarian,admin,super_admin,accountant');
        
    // Book Catalog - accessible to all authenticated users
    Route::get('/catalog', 'App\Http\Controllers\Library\LibraryController@catalog')
        ->name('library.catalog');
        
    // Student's borrowed books
    Route::get('/my-books', 'App\Http\Controllers\Library\LibraryController@myBooks')
        ->name('library.my-books')
        ->middleware('role:student');
        
    // Parent's children borrowed books
    Route::get('/my-children-books', 'App\Http\Controllers\Library\LibraryController@myChildrenBooks')
        ->name('library.my-children-books')
        ->middleware('role:parent');
});

// LGA Route - Returns empty array since we've removed LGA functionality
Route::get('/get_lga/{id}', 'AjaxController@get_lga')->name('get_lga')->middleware('auth');

// Staff Management Routes
Route::prefix('staff')->name('staff.')->middleware(['auth', 'checkUserType:admin,super-admin'])->group(function () {
    // Staff List
    Route::get('/', function () {
        return view('pages.staff.index', [
            'title' => 'Staff Management',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'link' => route('dashboard')],
                ['label' => 'Staff', 'link' => null]
            ]
        ]);
    })->name('index');

    // New Staff Management Page using Livewire
    Route::get('/manage', function () {
        return view('pages.staff.manage', [
            'title' => 'Staff Management',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'link' => route('dashboard')],
                ['label' => 'Staff Management', 'link' => null]
            ]
        ]);
    })->name('manage');

    // Staff Detail View
    Route::get('/{id}', function ($id) {
        return view('pages.staff.detail', [
            'staff_id' => $id,
            'title' => 'Staff Details',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'link' => route('dashboard')],
                ['label' => 'Staff', 'link' => route('staff.index')],
                ['label' => 'Staff Details', 'link' => null]
            ]
        ]);
    })->name('show');

    // Staff Qualifications
    Route::get('/{id}/qualifications', function ($id) {
        return view('pages.staff.qualifications', [
            'staff_id' => $id,
            'title' => 'Staff Qualifications',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'link' => route('dashboard')],
                ['label' => 'Staff', 'link' => route('staff.index')],
                ['label' => 'Staff Details', 'link' => route('staff.show', $id)],
                ['label' => 'Qualifications', 'link' => null]
            ]
        ]);
    })->name('qualifications');

    // Download Staff Report
    Route::get('/download/report', [App\Http\Controllers\StaffController::class, 'downloadReport'])->name('download.report');
});

// Diagnostic route - only available in debug mode
Route::get('/diagnostic', 'App\Http\Controllers\DiagnosticController@index')->name('diagnostic');

// Test route to verify role middleware
Route::get('/test-role-middleware', function () {
    return 'Role middleware is working correctly!';
})->middleware('role:librarian,admin')->name('test.role.middleware');

// Grading Systems Routes
Route::prefix('exams')->name('exams.')->group(function() {
    // Standard routes
    Route::get('/grading-systems', [App\Http\Controllers\Exams\GradingSystemController::class, 'index'])->name('grading-systems.index');
    
    // Livewire routes - these must come before the show route to avoid conflicts
    Route::get('/grading-systems/create', function() {
        return view('exams.grading-systems.create');
    })->name('grading-systems.create');
    
    // Show route - this must come after any specific routes with the same prefix
    Route::get('/grading-systems/{gradingSystem}', [App\Http\Controllers\Exams\GradingSystemController::class, 'show'])->name('grading-systems.show');
    
    // Edit routes - these include the gradingSystem parameter
    Route::get('/grading-systems/{gradingSystem}/edit', function($gradingSystem) {
        return view('exams.grading-systems.edit', compact('gradingSystem'));
    })->name('grading-systems.edit');
    
    // Grade Range routes
    Route::get('/grading-systems/{gradingSystem}/ranges/edit', function($gradingSystem) {
        return view('exams.grading-systems.ranges.edit', compact('gradingSystem'));
    })->name('grading-systems.ranges.edit');
    
    Route::get('/grading-systems/{gradingSystem}/ranges/create', function($gradingSystem) {
        return view('exams.grading-systems.ranges.create', compact('gradingSystem'));
    })->name('grading-systems.ranges.create');
});

