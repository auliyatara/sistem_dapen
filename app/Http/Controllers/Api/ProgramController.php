<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\RoleAccess;
use App\Models\Program;
use App\Models\ProgramKegiatan;
use App\Models\ProgramPelaksanaan;
use App\Models\ProgramEvaluasi;
use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProgramController extends Controller
{
    public function index(){
        if(isset($_GET['type'])){
            $operation = Program::where('program_type',$_GET['type'])->with('program_pelaksanaan')->with('program_evaluasi')->orderBy('created_at', 'asc')->get();
        } else{
            $operation = Program::with('program_pelaksanaan')->orderBy('created_at', 'asc')->with('program_evaluasi')->get();
        }
        return $this->response($operation);
    }

    public function show($id){
        $operation = Program::where('program_id',$id)->with('program_kegiatan')->with('program_pelaksanaan')->orderBy('created_at', 'asc')->with('program_evaluasi')->first();
        return $this->responseFirst($operation);
    }

    public function store(Request $request){
        try {
            $request->validate([
                'program_nama' => 'required',
            ]);
            
            $data = $request->all();
            $data['created_at'] = now();
            $operation = DB::transaction(function () use($data) {
                $program = Program::create($data); 

                if(isset($data['program_kegiatan'])){
                    foreach($data['program_kegiatan'] as $value){
                        $kegiatan['program_kegiatan_program_id'] = $program['program_id'];
                        $kegiatan['program_kegiatan_nama'] = $value;
                        ProgramKegiatan::create($kegiatan);
                    }
                }
                
                if(isset($data['program_pelaksanaan'])){
                    foreach($data['program_pelaksanaan'] as $value){
                        $pelaksanaan['program_pelaksanaan_program_id'] = $program['program_id'];
                        $pelaksanaan['program_pelaksanaan_nama'] = $value;
                        ProgramPelaksanaan::create($pelaksanaan);
                    }
                }

                if(isset($data['program_evaluasi'])){
                    foreach($data['program_evaluasi'] as $value){
                        $evaluasi['program_evaluasi_program_id'] = $program['program_id'];
                        $evaluasi['program_evaluasi_nama'] = $value;
                        ProgramEvaluasi::create($evaluasi);
                    }
                }

                return $program;
            });
            return $this->responseCreate($operation);
        } catch (\Exception $e) {
            return $this->responseCreate($e->getMessage(),true);
        }
    }

    public function update(Request $request, $id){
        try {
            $request->validate([
                'program_nama' => 'required',
            ]);

            $data = $request->all();
            unset($data['_token']);
            $operation = DB::transaction(function () use($data,$id) {
                $program = Program::find($id)->update($data); 
                ProgramKegiatan::where('program_kegiatan_program_id',$id)->delete();
                ProgramPelaksanaan::where('program_pelaksanaan_program_id',$id)->delete();
                ProgramEvaluasi::where('program_evaluasi_program_id',$id)->delete();

                if(isset($data['program_kegiatan'])){
                    foreach($data['program_kegiatan'] as $value){
                        $kegiatan['program_kegiatan_program_id'] = $id;
                        $kegiatan['program_kegiatan_nama'] = $value;
                        ProgramKegiatan::create($kegiatan);
                    }
                }

                if(isset($data['program_pelaksanaan'])){
                    foreach($data['program_pelaksanaan'] as $value){
                        $pelaksanaan['program_pelaksanaan_program_id'] = $id;
                        $pelaksanaan['program_pelaksanaan_nama'] = $value;
                        ProgramPelaksanaan::create($pelaksanaan);
                    }
                }

                if(isset($data['program_evaluasi'])){
                    foreach($data['program_evaluasi'] as $value){
                        $evaluasi['program_evaluasi_program_id'] = $id;
                        $evaluasi['program_evaluasi_nama'] = $value;
                        ProgramEvaluasi::create($evaluasi);
                    }
                }

                return $program;
            });
            return $this->responseUpdate($operation);
        } catch (\Exception $e) {
            return $this->responseUpdate($e->getMessage(),true);
        }
    }

    public function destroy($id){
        try {  
            $operation = DB::transaction(function () use($id) {
                $program = Program::find($id)->delete();
                ProgramKegiatan::where('program_kegiatan_program_id',$id)->delete();
                ProgramPelaksanaan::where('program_pelaksanaan_program_id',$id)->delete();
                ProgramEvaluasi::where('program_evaluasi_program_id',$id)->delete();

                return $program;
            });          

            return $this->responseDelete($operation);
        } catch (\Exception $e) {
            return $this->responseDelete($e->getMessage());
        }
    }

    public function generatepdf(Request $request) 
    {
        $data = $request->all();
        $operation = Program::whereIn('program_id', $data['list'])->with('program_pelaksanaan')->with('program_evaluasi')->orderBy('created_at', 'asc')->get();
        $data = [
            'data'=>$operation->toArray()
        ];
    
        $pdf = PDF::loadView('layout.Program.pdf', $data);
        return Response::make(base64_encode($pdf->output()), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename=my-pdf.pdf', // Display in the browser
        ]);
    }

    public function generateexcel(Request $request){
        $data = $request->all();
        $operation = Program::whereIn('program_id', explode(',',$data['list']))->with('program_pelaksanaan')->with('program_evaluasi')->orderBy('created_at', 'asc')->get();
        return Excel::download(new ExcelExport($operation->toArray()), 'Program.xlsx');
    }

    public function setAllCreatedAt(){
        $operation = DB::table('programs')->update(['created_at' => now()]);
        return $operation;
    }

    public function programList(){
        $user = User::find(Auth::id());
        $access = DB::select("SELECT menu_id,menu_kode,menu_nama,menu_level,menu_parent,menu_order FROM menus m JOIN role_accesses ra ON role_access_menu_id = menu_id WHERE role_access_role_id = '".$user['user_role_id']."' AND (menu_parent IN ( SELECT menu_id FROM menus WHERE menu_parent = '7283h2131h31b512') OR menu_id IN ( SELECT menu_id FROM menus WHERE menu_parent = '7283h2131h31b512')) AND menu_status=1 ORDER BY menu_level,menu_order");
        foreach($access as $key=>$value){
            if($value->menu_level == 4){
                $column = array_column($access, 'menu_id');              
                $foundKey = array_search($value->menu_parent, $column);

                if($value->menu_nama == "View Data"){
                    $access[$foundKey]->view = true; 
                } else{
                    $access[$foundKey]->edit = true; 
                }
                unset($access[$key]);
            }
        }
        return $this->response($access);
    }
}
