<?php

namespace App\DataTables;

use App\invoice;
use App\Tag;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
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
        $dataTable = $dataTable->addColumn('action', 'invoices.datatables_actions')
            ->addColumn('created_by', function (Invoice $invoice) {
                return $invoice->created_by ? $invoice->creator->name : '';
            })->editColumn('color', function (Invoice $invoice) {
                return '<span class="label" style="background-color: ' . $invoice->color . '">' . $invoice->color . '</span>';
            })->rawColumns(['color'], true)
            ->filterColumn('created_by', function ($query, $keyword) {
                return $query->whereRaw("select count(*) from invoices where lower(invoices.name) like ? and invoices.id=invoices.created_by",["%$keyword%"]);
            })
            ->addColumn('client', function($invoice){
                  return $invoice->client ? $invoice->client->name : '';
            });

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Invoice $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model)
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
            // ->addColumn(['data' => 'created_by'])
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
            'client',
            'invoice_no',
            'amount',
            'invoice_date',
            'status',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'invoicesdatatable_' . time();
    }
}
