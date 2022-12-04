<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Tabula\Tabula;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        //  abort_if(Gate::denies('employee_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::with(['created_by'])
            /*->whereHas('created_by', function($q)  {
                // Query the name field in status table
                $q->where('ddo', auth()->user()->ddo); // '=' is optional
            })*/
            ->get();

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        //  abort_if(Gate::denies('employee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->all() + ['created_by_id' => auth()->id()]);

        return redirect()->route('admin.employees.index');
    }

    public function edit(Employee $employee)
    {
        //  abort_if(Gate::denies('employee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        //get ddos of admins. only two admins.
        $ddos = \App\Models\User::whereHas('roles', function ($q) {
            $q->where('id', 1);
        })
        ->get()
        ->map(function ($item) {
            $item['ddo'] = \App\Models\TaxEntry::DDO_SELECT[$item->ddo];

            return $item;
        })
        ->pluck('ddo', 'id')
        ->prepend(trans('global.pleaseSelect'), '');

        $employee->load('created_by');

        return view('admin.employees.edit', compact('employee', 'ddos'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());

        return redirect()->route('admin.employees.index');
    }

    public function show(Employee $employee)
    {
        //   abort_if(Gate::denies('employee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->load('created_by');

        return view('admin.employees.show', compact('employee'));
    }

    public function destroy(Employee $employee)
    {
        //   abort_if(Gate::denies('employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmployeeRequest $request)
    {
        Employee::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function parseSparkImport(Request $request)
    {
        //$file = $request->file('pdf_file');
        // dd($request);
        /* $request->validate([
             'pdf_file' => 'mimes:pdf',
         ]);*/
        //
        $filename = time().'.pdf';
        $request->pdf_file->move(public_path('uploads'), $filename);
        $fileName1 = public_path('uploads').'/'.$filename;

        //in php.ini, set upload_max_filesize
        //you need to edit both /etc/php/7.2/cli/php.ini and /etc/php/7.2/apache2/php.ini.
        //Note: before editing the php.ini values, ensure that /etc/php/7.2/apache2/php.ini is the loaded php configuration file.
        //The command is: php -i | grep php.ini

        //$filen= $request->file('pdf_file')->storeAs('public', time());

        $tabula = new Tabula('/usr/bin/');

        //Tabula PHP does not return a value. It was set to output to file using 'output' param
        // So I edited vendor/initred/laravel-tabula/src/InitRed/Tabula/Tabula.php to  return $process->getOutput(); in function run()

        $result1 = $tabula->setPdf($fileName1)
            ->setOptions([
                'format' => 'csv',
                'pages' => 'all',
                'lattice' => true,
                'stream' => false,
            ])
            ->convert();

        File::delete($fileName1);

        $innerlines = explode("\r\n", $result1);

        $pen = '';
        $name = '';
        $data = [];

        for ($i = 0; $i < count($innerlines); $i++) {
            $l = $innerlines[$i];

            if (0 === strncmp($l, 'PEN :', strlen('PEN :'))) {
                $arr = explode(',', $l);
                $pen = str_replace(' ', '', $arr[1]);
                $name = $arr[3];
            }

            if (0 === strncmp($l, 'PAN Number :', strlen('PAN Number :'))) {
                $arr = explode(',', $l);
                $pan = str_replace(' ', '', $arr[1]);
                //for some, this can be empty

                if ($pan) {
                    $data[] = ['pen' => $pen, 'pan' => $pan, 'name' => $name];
                }

                $pen = '';
                $name = '';
            }
        }

        $emppan = Employee::pluck('pan');

        $new_employees = Collect($data)->wherenotin('pan', $emppan)->toArray();

        // dd($newemp);

        if (count($new_employees) == 0) {
            session()->flash('message', 'No new employees found');

            return redirect()->route('admin.employees.index');
        }

        return view('admin.employees.parseInput', compact('new_employees'));
    }

    public function processSparkImport(Request $request)
    {
        try {
            $data = $request->new_emp;
            $data = collect($data);

            $data->transform(function ($item) {
                $item['created_by_id'] = auth()->id();

                return $item;
            });

            Employee::insert($data->toArray());

            $rows = count($data);

            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $rows, 'table' => 'Employees']));

            return redirect()->route('admin.employees.index');
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
