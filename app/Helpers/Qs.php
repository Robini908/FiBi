<?php

namespace App\Helpers;

use Hashids\Hashids;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Qs
{

    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids();
    }

    public function hashRoute($route, $id)
    {
        // Using Laravel's built-in hashing functionality
        $hashedId = Hash::make($id);

        // Using Hashids\Hashids to generate a unique identifier
        $hashedRoute = $this->hashids->encode($route, $hashedId);

        return $hashedRoute;
    }
    public static function displayError($errors)
    {
        $errorMessages = '';
        foreach ($errors as $err) {
            $errorMessages .= '<li>' . $err . '</li>';
        }

        return '
            <div id="error-alert" class="alert alert-danger alert-styled-left alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-semibold">Oops!</span> 
                <ul class="mb-0">' .
            $errorMessages . '
                </ul>
            </div>
        ';
    }

    public static function displaySuccess($msg)
    {
        return '
            <div id="success-alert" class="alert alert-success alert-bordered alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>' .
            $msg .
            '</div>
        ';
    }

    public static function getAppCode()
    {
        return self::getSetting('system_title') ?: 'MBUKU';
    }

    public static function getDefaultUserImage()
    {
        return asset('global_assets/images/user.png');
    }

    public static function getPanelOptions()
    {
        return '    <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>';
    }

    // Using Spatie permissions
    /**
     * Check if the user has an accountant role
     * @return bool
     */
    public static function isAccountant()
    {
        return self::userHasRole('accountant');
    }

    /**
     * For backward compatibility
     * @deprecated Use isAccountant() instead
     */
    public static function userIsTeamAccount()
    {
        return self::isAccountant();
    }

    /**
     * Check if the user is an administrator (admin or super admin)
     * @return bool
     */
    public static function isAdministrator(): bool
    {
        return self::userHasRole(['admin', 'super_admin']);
    }

    /**
     * For backward compatibility
     * @deprecated Use isAdministrator() instead
     */
    public static function userIsTeamSA(): bool
    {
        return self::isAdministrator();
    }

    /**
     * Check if the user is an administrator or teacher
     * @return bool
     */
    public static function isAdministratorOrTeacher()
    {
        return self::userHasRole(['super_admin', 'admin', 'teacher']);
    }

    /**
     * For backward compatibility
     * @deprecated Use isAdministratorOrTeacher() instead
     */
    public static function userIsTeamSAT()
    {
        return self::isAdministratorOrTeacher();
    }

    /**
     * Check if the user is part of the academic staff
     * @return bool
     */
    public static function isAcademicStaff()
    {
        return self::userHasRole(['teacher', 'librarian']);
    }

    /**
     * For backward compatibility
     * @deprecated Use isAcademicStaff() instead
     */
    public static function userIsAcademic()
    {
        return self::isAcademicStaff();
    }

    /**
     * Check if the user is administrative personnel
     * @return bool
     */
    public static function isAdministrativeStaff()
    {
        return self::userHasRole(['admin', 'super_admin', 'accountant']);
    }

    /**
     * For backward compatibility
     * @deprecated Use isAdministrativeStaff() instead
     */
    public static function userIsAdministrative()
    {
        return self::isAdministrativeStaff();
    }

    /**
     * Check if the user is an admin
     * @return bool
     */
    public static function isAdmin()
    {
        return self::userHasRole('admin');
    }

    /**
     * For backward compatibility
     * @deprecated Use isAdmin() instead
     */
    public static function userIsAdmin()
    {
        return self::isAdmin();
    }

    /**
     * Get the user's primary role
     * @return string|null
     */
    public static function getUserRole()
    {
        $user = Auth::user();
        return $user && $user->roles->isNotEmpty() ? $user->roles->first()->name : null;
    }

    /**
     * For backward compatibility
     * @deprecated Use getUserRole() instead
     */
    public static function getUserType()
    {
        return self::getUserRole();
    }

    /**
     * Check if the user is a super administrator
     * @return bool
     */
    public static function isSuperAdmin()
    {
        return self::userHasRole('super_admin');
    }

    /**
     * For backward compatibility
     * @deprecated Use isSuperAdmin() instead
     */
    public static function userIsSuperAdmin()
    {
        return self::isSuperAdmin();
    }

    /**
     * Check if the user is a student
     * @return bool
     */
    public static function isStudent()
    {
        return self::userHasRole('student');
    }

    /**
     * For backward compatibility
     * @deprecated Use isStudent() instead
     */
    public static function userIsStudent()
    {
        return self::isStudent();
    }

    /**
     * Check if the user is a teacher
     * @return bool
     */
    public static function isTeacher()
    {
        return self::userHasRole('teacher');
    }

    /**
     * For backward compatibility
     * @deprecated Use isTeacher() instead
     */
    public static function userIsTeacher()
    {
        return self::isTeacher();
    }

    /**
     * Check if the user is a librarian
     * @return bool
     */
    public static function isLibrarian()
    {
        return self::userHasRole('librarian');
    }

    /**
     * For backward compatibility
     * @deprecated Use isLibrarian() instead
     */
    public static function userIsLibrarian()
    {
        return self::isLibrarian();
    }

    /**
     * Check if the user is a parent
     * @return bool
     */
    public static function isParent()
    {
        return self::userHasRole('parent');
    }

    /**
     * For backward compatibility
     * @deprecated Use isParent() instead
     */
    public static function userIsParent()
    {
        return self::isParent();
    }

    /**
     * Check if the user is a staff member
     * @return bool
     */
    public static function isStaffMember()
    {
        return self::userHasRole(['super_admin', 'admin', 'teacher', 'accountant', 'librarian']);
    }

    /**
     * For backward compatibility
     * @deprecated Use isStaffMember() instead
     */
    public static function userIsStaff()
    {
        return self::isStaffMember();
    }

    /**
     * Get all staff roles
     * @param array $remove Roles to exclude
     * @return array
     */
    public static function getStaffRoles($remove = [])
    {
        $data = ['super_admin', 'admin', 'teacher', 'accountant', 'librarian'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    /**
     * For backward compatibility
     * @deprecated Use getStaffRoles() instead
     */
    public static function getStaff($remove = [])
    {
        return self::getStaffRoles($remove);
    }

    /**
     * Get administrator roles
     * @return array
     */
    public static function getAdministratorRoles()
    {
        return ['admin', 'super_admin'];
    }

    /**
     * For backward compatibility
     * @deprecated Use getAdministratorRoles() instead
     */
    public static function getTeamSA()
    {
        return self::getAdministratorRoles();
    }

    /**
     * Get financial management roles
     * @return array
     */
    public static function getFinancialRoles()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    /**
     * For backward compatibility
     * @deprecated Use getFinancialRoles() instead
     */
    public static function getTeamAccount()
    {
        return self::getFinancialRoles();
    }

    /**
     * Get administrator and teacher roles
     * @return array
     */
    public static function getAdminAndTeacherRoles()
    {
        return ['admin', 'super_admin', 'teacher'];
    }

    /**
     * For backward compatibility
     * @deprecated Use getAdminAndTeacherRoles() instead
     */
    public static function getTeamSAT()
    {
        return self::getAdminAndTeacherRoles();
    }

    /**
     * Get academic community roles
     * @return array
     */
    public static function getAcademicCommunityRoles()
    {
        return ['admin', 'super_admin', 'teacher', 'student', 'parent'];
    }

    /**
     * For backward compatibility
     * @deprecated Use getAcademicCommunityRoles() instead
     */
    public static function getTeamAcademic()
    {
        return self::getAcademicCommunityRoles();
    }

    public static function hash($id)
    {
        $date = date('dMY') . 'CJ';
        $hash = new Hashids($date, 14);
        return $hash->encode($id);
    }

    public static function unhash($hashedId)
    {
        $date = date('dMY') . 'CJ';
        $hash = new Hashids($date, 14);
        $decoded = $hash->decode($hashedId);
        return $decoded ? $decoded[0] : null;
    }

    public static function getUserRecord($remove = [])
    {
        $data = ['first_name', 'middle_name', 'last_name', 'email', 'phone', 'dob', 'gender', 'address', 'bg_id', 'nal_id', 'state_id', 'lga_id'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Fetch staff record fields
    public static function getStaffRecord($remove = [])
    {
        $data = ['emp_date'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Fetch student data fields
    public static function getStudentData($remove = [])
    {
        $data = ['my_class_id', 'section_id', 'parent_id_no', 'dorm_id', 'dorm_room_no', 'adm_no', 'year_admitted', 'wd', 'wd_date', 'grad', 'grad_date', 'house', 'age'];
        return $remove ? array_values(array_diff($data, $remove)) : $data;
    }

    // Decode a hash string
    public static function decodeHash($str, $toString = true)
    {
        $date = date('dMY') . 'CJ';
        $hash = new Hashids($date, 14);
        $decoded = $hash->decode($str);
        return $toString ? implode(',', $decoded) : $decoded;
    }

    /**
     * Check if the user is a PTA member
     * @return bool
     */
    public static function isPtaMember()
    {
        return self::userHasRole(['super_admin', 'admin', 'teacher', 'parent']);
    }

    /**
     * For backward compatibility
     * @deprecated Use isPtaMember() instead
     */
    public static function userIsPTA()
    {
        return self::isPtaMember();
    }

    /**
     * Check if the authenticated parent is a guardian of the specified student
     * @param int $student_id Student user ID
     * @param int $parent_id Parent user ID
     * @return bool
     */
    public static function isParentOfStudent($student_id, $parent_id)
    {
        return StudentRecord::where(['user_id' => $student_id, 'parent_id_no' => $parent_id])->exists();
    }

    /**
     * For backward compatibility
     * @deprecated Use isParentOfStudent() instead
     */
    public static function userIsMyChild($student_id, $parent_id)
    {
        return self::isParentOfStudent($student_id, $parent_id);
    }

    /**
     * Get roles with administrative access
     * @return array
     */
    public static function getAdministrativeRoles()
    {
        return ['admin', 'super_admin', 'accountant'];
    }

    /**
     * For backward compatibility
     * @deprecated Use getAdministrativeRoles() instead
     */
    public static function getTeamAdministrative()
    {
        return self::getAdministrativeRoles();
    }

    /**
     * Get Parent-Teacher Association roles
     * @return array
     */
    public static function getPtaRoles()
    {
        return ['admin', 'super_admin', 'teacher', 'parent'];
    }

    /**
     * For backward compatibility
     * @deprecated Use getPtaRoles() instead
     */
    public static function getPTA()
    {
        return self::getPtaRoles();
    }

    /**
     * Get Student Record by User ID
     * @param int $user_id
     * @return StudentRecord|null
     */
    public static function getStudentRecordByUserId($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    /**
     * For backward compatibility
     * @deprecated Use getStudentRecordByUserId() instead
     */
    public static function getSRByUserID($user_id)
    {
        return self::getStudentRecordByUserId($user_id);
    }

    /**
     * Helper function to check if user has a role
     * @param string|array $roles Role or roles to check
     * @return bool
     */
    protected static function userHasRole($roles)
    {
        $user = Auth::user();
        if(!$user) { 
            return false;
        }

        return is_array($roles) 
            ? $user->hasAnyRole($roles) 
            : $user->hasRole($roles);
    }

    public static function getPublicUploadPath()
    {
        return 'uploads/';
    }

    public static function getUserUploadPath()
    {
        return 'uploads/users/';
    }

    public static function getUploadPath($user_type)
    {
        return 'uploads/' . $user_type . 's/';
    }

    public static function getFileMetaData($file)
    {
        $file_path = $file->getPathName();
        $file_size = $file->getSize();
        $file_ext = $file->getClientOriginalExtension();
        $file_name = $file->getClientOriginalName();
        return (['temp_file_path' => $file_path, 'file_size' => $file_size, 'file_ext' => $file_ext, 'file_name' => $file_name]);
    }

    public static function generateUserCode()
    {
        return strtoupper(substr(md5(time()), -7, -2));
    }

    public static function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    public static function getSetting($type)
    {
        return Setting::where('type', $type)->first()->value;
    }

    public static function getCurrentSession()
    {
        return self::getSetting('current_session');
    }

    public static function getNextSession()
    {
        $current_session = explode('-', self::getCurrentSession());
        return ++$current_session[0] . '-' . ++$current_session[1];
    }

    public static function getSystemName()
    {
        return self::getSetting('system_name');
    }

    public static function findMyChildren($parent_id)
    {
        return StudentRecord::where('parent_id_no', $parent_id)->with(['user', 'my_class'])->get();
    }

    public static function findTeacherSubjects($teacher_id)
    {
        return Subject::where('teacher_id', $teacher_id)->with('my_class')->get();
    }

    public static function findStudentRecord($user_id)
    {
        return StudentRecord::where('user_id', $user_id)->first();
    }

    public static function getMarkType($class_type)
    {
        switch ($class_type) {
            case 'J':
                $mark_type = 'junior';
                break;
            case 'S':
                $mark_type = 'senior';
                break;
            case 'P':
                $mark_type = 'primary';
                break;
            case 'N':
                $mark_type = 'nursery';
                break;
            case 'C':
                $mark_type = 'college';
                break;
            case 'PR':
                $mark_type = 'pre_school';
                break;
            default:
                $mark_type = 'exam';
                break;
        }
        return $mark_type;
    }

    public static function json($msg, $ok = TRUE, $arr = [])
    {
        return $arr ? response()->json($arr) : response()->json(['ok' => $ok, 'msg' => $msg]);
    }

    public static function jsonStoreOk()
    {
        return self::json(__('msg.store_ok'));
    }

    public static function jsonUpdateOk()
    {
        return self::json(__('msg.update_ok'));
    }

    public static function storeOk($routeName)
    {
        return redirect()->route($routeName)->with('flash_success', __('msg.store_ok'));
    }

    public static function deleteOk($routeName)
    {
        return redirect()->route($routeName)->with('flash_success', __('msg.del_ok'));
    }

    public static function updateOk($routeName)
    {
        return redirect()->route($routeName)->with('flash_success', __('msg.update_ok'));
    }

    public static function goToRoute($goto, $status = 302, $headers = [], $secure = null)
    {
        $url = (is_array($goto)) ? route($goto[0], $goto[1]) : route($goto);
        return redirect()->to($url, $status, $headers, $secure);
    }

    public static function goWithDanger($to = 'dashboard', $msg = NULL)
    {
        $msg = $msg ? $msg : __('msg.rnf');
        return redirect()->route($to)->with('flash_danger', $msg);
    }

    public static function goWithSuccess($to, $msg)
    {
        return redirect()->route($to)->with('flash_success', $msg);
    }

    public static function getDaysOfTheWeek()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }
}

