<?php

namespace App\Traits;

trait WithSorting
{
    /**
     * The column being sorted.
     *
     * @var string
     */
    public $sortField = '';
    
    /**
     * The direction of the sort.
     *
     * @var string
     */
    public $sortDirection = 'asc';
    
    /**
     * Get the sort field and direction in a format that can be used in a query.
     *
     * @return array
     */
    public function getSortBy()
    {
        return [$this->sortField, $this->sortDirection];
    }
    
    /**
     * Sort by a given field.
     *
     * @param string $field
     * @return void
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }
    
    /**
     * Get the sort icon for a given field.
     *
     * @param string $field
     * @return string
     */
    public function getSortIcon($field)
    {
        if ($this->sortField !== $field) {
            return 'selector';
        }
        
        return $this->sortDirection === 'asc' ? 'selector-asc' : 'selector-desc';
    }
    
    /**
     * Check if the field is currently being sorted.
     *
     * @param string $field
     * @return boolean
     */
    public function isSorted($field)
    {
        return $this->sortField === $field;
    }
} 