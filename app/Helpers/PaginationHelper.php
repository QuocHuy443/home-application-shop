<?php

namespace App\Helpers;

class PaginationHelper
{
    /**
     * Render Bootstrap 5 pagination HTML for non-Laravel environment.
     * 
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @return string
     */
    public static function render($paginator): string
    {
        if (!$paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return '';
        }

        if ($paginator->lastPage() <= 1) {
            return '';
        }

        $html = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm mb-0">';

        // Trang trước (Previous Page Link)
        if ($paginator->onFirstPage()) {
            $html .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">&laquo;</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link shadow-none" href="' . htmlspecialchars($paginator->previousPageUrl()) . '" rel="prev">&laquo;</a></li>';
        }

        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();

        // Limit the number of surrounding pages shown to prevent UI issues
        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $currentPage + 2);

        if ($start > 1) {
            $html .= '<li class="page-item"><a class="page-link shadow-none" href="' . htmlspecialchars($paginator->url(1)) . '">1</a></li>';
            if ($start > 2) {
                $html .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $html .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link shadow-none" href="' . htmlspecialchars($paginator->url($i)) . '">' . $i . '</a></li>';
            }
        }

        if ($end < $lastPage) {
            if ($end < $lastPage - 1) {
                $html .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link shadow-none" href="' . htmlspecialchars($paginator->url($lastPage)) . '">' . $lastPage . '</a></li>';
        }

        // Trang sau (Next Page Link)
        if ($paginator->hasMorePages()) {
            $html .= '<li class="page-item"><a class="page-link shadow-none" href="' . htmlspecialchars($paginator->nextPageUrl()) . '" rel="next">&raquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">&raquo;</span></li>';
        }

        $html .= '</ul></nav>';

        return $html;
    }
}
