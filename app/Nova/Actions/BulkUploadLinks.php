<?php

namespace App\Nova\Actions;

use App\Imports\LinkSubmissionImport;
use Brightspot\Nova\Tools\DetachedActions\DetachedAction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Maatwebsite\Excel\Facades\Excel;

class BulkUploadLinks extends DetachedAction
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        $pathinfo = pathinfo($fields->file->getClientOriginalName());
        if ($pathinfo['extension'] == 'csv') {

            Excel::import(new LinkSubmissionImport, $fields->file->store('uploads'), null, \Maatwebsite\Excel\Excel::CSV);


        } else if ($pathinfo['extension'] == 'xlsx') {

            Excel::import(new LinkSubmissionImport, $fields->file->store('uploads'), null, \Maatwebsite\Excel\Excel::XLSX);

        } else {

            return Action::danger('Invalid file type. Upload *.csv or *.xlsx files only.');
        }

        return Action::message("Done!");

    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make('File')->help('*.xls or *.csv')->rules('required')
        ];
    }
}
