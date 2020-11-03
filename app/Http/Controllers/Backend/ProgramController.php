<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Lang;
use App\Models\Program;
use App\Models\ProgramContent;
use App\Traits\Exploding;
use App\Traits\LangTrait;
use App\Traits\RespondBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class ProgramController extends Controller
{
    use RespondBack;
    use Exploding;
    use LangTrait;


    public function __construct()
    {
        $this->middleware('authAdmin');
    }

    ######################################   Begin Indexing   ################################################

    public function index(){
        return view('backend.programs.index', ['pageTitle' => 'All program']);
    }

    public function allData(){


        $programs = Program::select('id', 'photo', 'status')->with(['programContents' => function($q) {
                $q->whereIn('lang_id', [$this->mainLang(), $this->secondaryLang()])->orderBy('lang_id', 'desc');
             }])->get()
            ->transform(function($program){
                $newProgram = [];
                $newProgram['id'] =  $program->id;
                $newProgram['photo'] =  $program->photo;
                $newProgram['name'] = $program->programContents[0]->name;
                $newProgram['status'] =  $program->status;
                return $newProgram;
            });

        return DataTables::of($programs)
            ->addColumn('actions', 'backend.programs.actions')
            ->editColumn('photo', '<img src="{{ url(\'uploads/backend/programs/images/75x75\')}}/{{$photo}}" style="height: 75px; width: 75px" class="mx-auto d-block">')
            ->editColumn('status', '{{$status==1?"Active":"unActive"}}')
            ->rawColumns(['actions', 'photo', 'status'])
            ->make(true);
    }

    ######################################   End Indexing   ####################################################

    ######################################   Begin Creation   ####################################################

    public function create(){
                                                                                          // get Active programs and pass it to view
        $activeLangs = Lang::select('id', 'name')->get();
        return view('backend.programs.create', ['pageTitle' => 'Create New program', 'activeLangs' => $activeLangs]);
    }

    public function insert(Request $request){
        $langs = explode(',', $request->input('programs_langs'));
        $validation = Validator::make($request->all(), $this->getCreateRules($request, $langs), $this->getCreateMessages());
        if($validation->fails())
            return $this->ResponseFail($validation->errors());

        $photoName = 'program_default.jpg';
        if($request->hasFile('program_photo')){
            $photo = $request->file('program_photo');
            $photoName = 'program_'.time().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->resize(75, 75)->save(base_path('uploads/backend/programs/images/75x75/'.$photoName));
            Image::make($photo)->resize(250, 250)->save(base_path('uploads/backend/programs/images/250x250/'.$photoName));
            Image::make($photo)->resize(1200, 700)->save(base_path('uploads/backend/programs/images/1200x700/'.$photoName));
        }

        $newProgramId = DB::table('programs')->insertGetId([
            'photo' => $photoName,
            'status' => 1,
        ]);

        foreach ($langs as $lang){
            ProgramContent::create([
               'program_id' => $newProgramId,
                'lang_id' => $lang,
                'name' => $request->input('program_name_'.$lang),
                'slug' => $this->slugging($request->input('program_name_'.$lang)),
                'description' => $request->input('program_description_'.$lang),
            ]);
        }

        return $this->ResponseSuccessMessage('Program Inserted, wanna Add another one ?');
    }

    ######################################   End Creation   ####################################################

    ######################################   Begin Updating   ####################################################

    public function edit($oldLang, $programId){
        $program = Program::with(['programContents'])->find($programId);
        if(!isset($program))
            return $this->ResponseFail('sry this program not found');

        $activeLangs = Lang::select('id', 'name')->where('status', 1)->get();
        $programLangs = [];
        foreach ($program->programContents as $programContent){
            $programLangs[] = $programContent->lang_id;
        }


        return view('backend.programs.edit',['program' => $program, 'activeLangs' => $activeLangs, 'programLangs' => $programLangs]);

    }

    public function update(Request $request, $programId){
        $program = Program::find($programId);
        if(!isset($program))
            return $this->ResponseFail('Sry this program not found');

        $programOldLangs = explode(',', $request->old_langs);
        $programNewLangs = explode(',', $request->new_langs);

        $validation = Validator::make($request->all(), $this->getUpdateRules($request, $programNewLangs), $this->getUpdateMessages());
        if($validation->fails())
            return $this->ResponseFail($validation->errors());

        $photoOldName = $program->photo;
        $photoNewName = $program->photo;
        if($request->hasFile('program_photo')){
            $photo = $request->file('program_photo');
            $photoNewName = 'program_'.time().'.'.$photo->getClientOriginalExtension();
            Image::make($photo)->resize(75, 75)->save(base_path('uploads/backend/programs/images/75x75/'.$photoNewName));        // upload new photo
            Image::make($photo)->resize(250, 250)->save(base_path('uploads/backend/programs/images/250x250/'.$photoNewName));
            Image::make($photo)->resize(1200, 700)->save(base_path('uploads/backend/programs/images/1200x700/'.$photoNewName));

            Storage::disk('programs')->delete('images/75x75/'.$photoOldName);               // delete old photo
            Storage::disk('programs')->delete('images/250x250/'.$photoOldName);
            Storage::disk('programs')->delete('images/1200x700/'.$photoOldName);
        }

        $program->photo = $photoNewName;        // save new name to program
        $program->save();

        foreach ($programOldLangs as $programLang){
            if(!in_array($programLang, $programNewLangs)){                                           // remove offer content if un selected
                $programContentDelete = ProgramContent::where('program_id', $programId)->where('lang_id', $programLang)->first();
                if(!isset($programContentDelete))
                    return $this->ResponseFail('Sry this offer Content not found (deleting)');
                $programContentDelete->delete();
                continue;
            }

            $programContentUpdate = ProgramContent::where('program_id', $programId)->where('lang_id', $programLang)->first();                   // updated the offer content
            if(!isset($programContentUpdate))
                return $this->ResponseFail('Sry this Program Content not found (updating)');
            $programContentUpdate->name = $request->input('program_name_'.$programLang);
            $programContentUpdate->description = $request->input('program_description_'.$programLang);
            $programContentUpdate->save();
        }
        foreach($programNewLangs as $programLang){              // checking for new offer content && insert if found
            if(!in_array($programLang, $programOldLangs)){
                ProgramContent::create([
                    'program_id' => $programId,
                    'lang_id' => $programLang,
                    'name' => $request->input('offer_name_'.$programLang),
                    'slug' => $this->slugging($request->input('offer_name_'.$programLang)),
                    'description' => $request->input('offer_description_'.$programLang),
                ]);
            }
        }

        return $this->ResponseSuccessMessage('Program updated successfully');

    }

    ######################################   End Updating   ####################################################

    ######################################   Begin Deletion   ####################################################

    public function delete(Request $request){
        $program = Program::find($request->id);
        if(!isset($program))
            return $this->ResponseFail('sry this Program not found');

        if(isset($program->photo) && $program->photo !== "program_default.jpg"){
            Storage::disk('programs')->delete('images/75x75/'.$program->photo);
            Storage::disk('programs')->delete('images/250x250/'.$program->photo);
            Storage::disk('programs')->delete('images/1200x700/'.$program->photo);
        }
        $program->delete();  // program_contents automitaccly removed because there relations (foreign keys) in  mysql
        return $this->ResponseSuccessMessage('deleted successfully');

    }

    ######################################   End Deletion   ####################################################


    ######################################   Begin Status Changing   ####################################################


    public function changeStatus(Request $request){

        $program = Program::find($request->id);
        if(!isset($program))
            return $this->ResponseFail('sry this program not found');

        $program->status = ($program->status == 1)? 0: 1;
        $program->save();
        return $this->ResponseSuccessMessage('status changed successfully');
    }

    ######################################   End Status Changing   ####################################################

////////////////////////////////////////////////////// Begin  validation Arrays //////////////////////////////////////////////////

    protected function getCreateRules($request, $langs){
        $arr = [
            'program_photo' => 'required|image|dimensions:min_width=1200,min_height=700,max_width=1300,max_height=800',
        ];
        foreach($langs as $lang){
            $arr['program_name_'.$lang] = 'required';
            $arr['program_description_'.$lang] = 'required';
        }
        return $arr;
    }

    protected function getCreateMessages(){
        return [
            'program_photo.required' => __('backend.Please select program photo'),
            'program_photo.image' => __('backend.photo must be an image'),
            'program_photo.dimensions' => __('backend.photo size must be 1200x700'),
            'program_name_*.required' => __('backend.Program name is required'),
            'program_description_*.required' => __('backend.Program description is required'),
        ];
    }

    /////////////////////////////////////////////////////////// update Rules && messages /////////////////////////////////////////////

    protected function getUpdateRules($request, $langs){
        $arr = [];
        foreach($langs as $lang){
            $arr['program_name_'.$lang] = 'required';
            $arr['program_description_'.$lang] = 'required';
        }
        if($request->deleted_photo !== "empty") {         // if  old photo deleted  add photo required Rule
            $arr['program_photo'] = 'required';
        }
        if($request->hasFile('program_photo')){
            $arr['program_photo'] = 'image|dimensions:min_width=1200,min_height=700,max_width=1300,max_height=800';
        }

        return $arr;
    }

    protected function getUpdateMessages(){
        return [
            'program_photo.required' => __('backend.Please select program photo'),
            'program_photo.image' => __('backend.photo must be an image'),
            'program_photo.dimensions' => __('backend.photo size must be 1200x700'),
            'program_name_*.required' => __('backend.Program name is required'),
            'program_description_*.required' => __('backend.Program description is required'),
        ];
    }


    ////////////////////////////////////////////////////// End  validation Arrays //////////////////////////////////////////////////

    ////////////////////////////////////////////////////// Begin other functions /////////////////////////////////////////////////////////
}
