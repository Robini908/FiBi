<?php

namespace App\Helpers;

use App\Models\Setting;
use Carbon\Carbon;

class SettingsHelper
{
    /**
     * Get the school name
     *
     * @return string
     */
    public function schoolName(): string
    {
        return Setting::get('school_name', 'Kenya School System');
    }

    /**
     * Get the school address
     *
     * @return string
     */
    public function schoolAddress(): string
    {
        return Setting::get('school_address', '');
    }

    /**
     * Get the school phone
     *
     * @return string
     */
    public function schoolPhone(): string
    {
        return Setting::get('school_phone', '');
    }

    /**
     * Get the school email
     *
     * @return string
     */
    public function schoolEmail(): string
    {
        return Setting::get('school_email', '');
    }

    /**
     * Get the school logo path
     *
     * @return string
     */
    public function schoolLogo(): string
    {
        return Setting::get('school_logo', 'images/logo.png');
    }

    /**
     * Get the current academic year
     *
     * @return string
     */
    public function currentAcademicYear(): string
    {
        return Setting::get('current_academic_year', Carbon::now()->year . '-' . (Carbon::now()->year + 1));
    }

    /**
     * Get the current term
     *
     * @return string
     */
    public function currentTerm(): string
    {
        return Setting::get('current_term', 'Term 1');
    }

    /**
     * Get all terms
     *
     * @return array
     */
    public function terms(): array
    {
        return [
            'Term 1', 
            'Term 2', 
            'Term 3'
        ];
    }

    /**
     * Get school currency
     *
     * @return string
     */
    public function currency(): string
    {
        return Setting::get('currency', 'KES');
    }

    /**
     * Get currency symbol
     *
     * @return string
     */
    public function currencySymbol(): string
    {
        return Setting::get('currency_symbol', 'KSh');
    }

    /**
     * Get school system timezone
     *
     * @return string
     */
    public function timezone(): string
    {
        return Setting::get('timezone', 'Africa/Nairobi');
    }

    /**
     * Get attendance threshold percentage for warnings
     *
     * @return int
     */
    public function attendanceThreshold(): int
    {
        return (int) Setting::get('attendance_threshold', 80);
    }

    /**
     * Get mark submission deadline days after exam
     *
     * @return int
     */
    public function markSubmissionDeadline(): int
    {
        return (int) Setting::get('mark_submission_deadline', 7);
    }

    /**
     * Get the next academic year based on the current one
     *
     * @return string
     */
    public function nextAcademicYear(): string
    {
        $current = $this->currentAcademicYear();
        $years = explode('-', $current);
        
        if (count($years) !== 2) {
            return (Carbon::now()->year + 1) . '-' . (Carbon::now()->year + 2);
        }
        
        return (intval($years[0]) + 1) . '-' . (intval($years[1]) + 1);
    }

    /**
     * Get the previous academic year based on the current one
     *
     * @return string
     */
    public function previousAcademicYear(): string
    {
        $current = $this->currentAcademicYear();
        $years = explode('-', $current);
        
        if (count($years) !== 2) {
            return (Carbon::now()->year - 1) . '-' . Carbon::now()->year;
        }
        
        return (intval($years[0]) - 1) . '-' . (intval($years[1]) - 1);
    }

    /**
     * Get the next term based on the current one
     *
     * @return string
     */
    public function nextTerm(): string
    {
        $current = $this->currentTerm();
        $terms = $this->terms();
        
        $currentIndex = array_search($current, $terms);
        
        if ($currentIndex === false || $currentIndex === count($terms) - 1) {
            return $terms[0];
        }
        
        return $terms[$currentIndex + 1];
    }

    /**
     * Check if result publishing is enabled
     *
     * @return bool
     */
    public function resultsPublished(): bool
    {
        return (bool) Setting::get('publish_results', false);
    }

    /**
     * Get the minimum grade for promotion
     *
     * @return string
     */
    public function minimumPromotionGrade(): string
    {
        return Setting::get('min_promotion_grade', 'D');
    }

    /**
     * Get the minimum marks for promotion
     *
     * @return int
     */
    public function minimumPromotionMarks(): int
    {
        return (int) Setting::get('min_promotion_marks', 40);
    }
} 