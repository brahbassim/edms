<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use Exception;
    use Illuminate\Http\Request;

    class DecorationController extends Controller
    {
        public function index()
        {
            $title = 'Gestion des types de décorations';
            if(request()->wantsJson()){
                return collect(Category::orderBy('created_at','DESC')->paginate(10));
            }
            return view('decoration.index',['title' => $title]);
        }

        public function search($field,$value)
        {
            return response()->json(Category::where($field,'LIKE',"%$value%")->orderBy('id','DESC')->paginate(10));
        }

        public function store(Request $request)
        {
            $this->validateDecoration($request);
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            return response()->json(['success' => true],200);
        }

        public function update(Request $request, $id)
        {
            $this->validateDecoration($request,$id);
            $category = Category::find($id);
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();
            return response()->json(['success' => true],200);
        }

        public function destroy($id)
        {
            $category = Category::find($id);
            $category->delete();
            return response()->json(['OK'],200);
        }

        private function validateDecoration(Request $request,$id = null){
            $rules =  [];
            if ($id){
                $rules['name'] = 'nullable|unique:categories,id,' . $id;
            }else{
                $rules['name'] = 'required|unique:categories';
            }
            return $request->validate($rules);
        }
    }
