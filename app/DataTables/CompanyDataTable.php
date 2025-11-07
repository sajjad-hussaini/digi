<?php

namespace App\DataTables;

use App\Company;
use App\Tag;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $dataTable = $dataTable->addColumn('action', 'companies.datatables_actions')
            ->addColumn('created_by', function (Tag $tag) {
                return $tag->createdBy->name;
            })->editColumn('color', function (Tag $tag) {
                return '<span class="label" style="background-color: ' . $tag->color . '">' . $tag->color . '</span>';
            })->rawColumns(['color'], true)
            ->filterColumn('created_by', function ($query, $keyword) {
                return $query->whereRaw("select count(*) from companies where lower(companies.name) like ? and companies.id=companies.created_by",["%$keyword%"]);
            });

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Tag $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Company $model)
    {
        $query = $model->newQuery()->with(['createdBy']);
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addColumn(['data' => 'created_by'])
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom' => 'Bfrtip',
                'stateSave' => true,
                'order' => [[0, 'desc']],
                'buttons' => [
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'company_name',
            'company_address',
            'contact_number',
            'email_address',
            'solicitor_name',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'companiesdatatable_' . time();
    }
}
