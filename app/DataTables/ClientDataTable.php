<?php

namespace App\DataTables;

use App\Client;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class ClientDataTable extends DataTable
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
        $dataTable = $dataTable->addColumn('action', 'clients.datatables_actions')
            ->addColumn('created_by', function (Client $client) {
                return $client->created_by ? $client->creator->name : '';
            })->editColumn('color', function (Client $client) {
                return '<span class="label" style="background-color: ' . $client->color . '">' . $client->color . '</span>';
            })->rawColumns(['color'], true)
            ->filterColumn('created_by', function ($query, $keyword) {
                return $query->whereRaw("select count(*) from clients where lower(clients.name) like ? and clients.id=clients.created_by",["%$keyword%"]);
            });

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Tag $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Client $model)
    {
        $query = $model->newQuery();
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
            'client_name',
            'client_address',
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
        return 'clientsdatatable_' . time();
    }
}
