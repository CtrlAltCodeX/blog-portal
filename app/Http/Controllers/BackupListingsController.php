<?php

namespace App\Http\Controllers;

use App\Exports\BackupListingsExport;
use App\Models\BackupEmail;
use App\Models\BackupListing;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class BackupListingsController extends Controller
{
    /**
     * Initiate the class instance
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Index function
     *
     * @return \Illuminate\View\View
     */
    public function backupListings()
    {
        $getAllListings = BackupListing::all();

        return view('backup-listings.index', compact('getAllListings'));
    }

    /**
     * Export Table
     *
     * @return void
     */
    public function exportData()
    {
        $getAllListings = BackupListing::all()->makeHidden(['id', 'created_at', 'updated_at'])->toArray();

        $mainColums = [
            'Product id',
            'Title',
            'Description',
            'MRP',
            'Selling Price',
            'Publisher',
            'Author Name',
            'Edition',
            'Categories',
            'SKU',
            'language',
            'No of Pages',
            'Condition',
            'Binding Type',
            'Insta Mojo URL',
            'Base URL'
        ];

        array_unshift($getAllListings, $mainColums);

        return $getAllListings;
    }

    /**
     * Download Excel
     *
     * @return void
     */
    public function downloadExcel()
    {
        return Excel::download(new BackupListingsExport($this->exportData()), 'report.xlsx');
    }

    /**
     * Read Log and Display
     *
     * @return void
     */
    public function getLoggerFile()
    {
        $logFilePath = storage_path('logs/backup_activity.log');

        $logContent = 'No Such Logs Exist';
        if (File::exists($logFilePath)) $logContent = File::get($logFilePath);

        $logContent = str_ireplace("local.INFO", '', $logContent);

        return view('settings.backup-logs', compact('logContent'));
    }

    /**
     * Insert email for Backup
     *
     * @return void
     */
    public function backupEmail()
    {
        $backupMails = BackupEmail::all();

        return view('settings.backup-email', compact('backupMails'));
    }

    /**
     * Save & update email for Backup
     *
     * @return void
     */
    public function saveEmail()
    {
        try {
            $email = request()->email;
            $update = request()->update;

            if ((isset($update) && $existEmail = BackupEmail::find($update))) {
                $existEmail->update(['email' => $email]);
            } else {
                $this->validate(request(), [
                    'email' => 'required|email|unique:backup_emails,email',
                ]);

                BackupEmail::create([
                    'email' => $email
                ]);
            }

            session()->flash('success', 'Emails updated successfully');

            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Delete Email Successfully
     *
     * @param int $id
     * @return void
     */
    public function deleteEmail($id)
    {
        BackupEmail::find($id)->delete();

        session()->flash('success', 'Emails delete successfully');

        return redirect()->back();
    }
}
