<?php
class Pagination
{
    public $limit;
    public $page;
    public $totalItems;
    public $totalPages;

    public function __construct($totalItems, $limit = 10, $currentPage = 1)
    {
        $this->limit = $limit;
        $this->totalItems = $totalItems;
        $this->totalPages = ceil($totalItems / $limit);
        $this->page = max(1, min($currentPage, $this->totalPages));
    }

    public function offset()
    {
        return ($this->page - 1) * $this->limit;
    }

    public function render($baseQueryParams = [], $target = 'pageproduct')
    {
        $prevPage = max(1, $this->page - 1);
        $nextPage = min($this->totalPages, $this->page + 1);

        $html = '
            <style>
                input[type=number]::-webkit-inner-spin-button,
                input[type=number]::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }
                input[type=number] {
                    -moz-appearance: textfield;
                }

                .btn-outer {
                    opacity: 0.65;
                    transition: opacity 0.2s ease;
                }
                .btn-outer:hover {
                    opacity: 1;
                }
            </style>
            <section class="phantrang py-3" data-target="' . $target . '">
                <div class="">
                    <div class="row justify-content-center">
                        <div class="col-auto text-center d-flex align-items-center gap-2">';

        // Nút << luôn mờ, hover sáng
        $html .= '
                    <a href="#" class="btn-outer page-link-custom btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px;" data-page="1" data-target="' . $target . '">
                        <i class="fa fa-angle-double-left text-dark"></i>
                    </a>';

        // Nút < bình thường
        $html .= '
                    <a href="#" class="page-link-custom btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px;" data-page="' . $prevPage . '" data-target="' . $target . '">
                        <i class="fa fa-chevron-left text-dark"></i>
                    </a>';

        // Ô input trang
        $html .= '
                    <span>
                        <input id="pageInput" type="number" min="1" max="' . $this->totalPages . '" value="' . $this->page . '"
                            class="form-control d-inline-block text-center" style="width: 60px; display: inline-block;" />
                        / ' . $this->totalPages . '
                    </span>';

        // Nút > bình thường
        $html .= '
                    <a href="#" class="page-link-custom btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px;" data-page="' . $nextPage . '" data-target="' . $target . '">
                        <i class="fa fa-chevron-right text-dark"></i>
                    </a>';

        // Nút >> luôn mờ, hover sáng
        $html .= '
                    <a href="#" class="btn-outer page-link-custom btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center"
                       style="width: 40px; height: 40px;" data-page="' . $this->totalPages . '" data-target="' . $target . '">
                        <i class="fa fa-angle-double-right text-dark"></i>
                    </a>';

        $html .= '
                    </div>
                </div>
            </div>
        </section>';
        return $html;
    }
}
