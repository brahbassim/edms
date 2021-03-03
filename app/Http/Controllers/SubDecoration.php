<?php

    namespace App\Http\Controllers;

    use App\Models\SubCategory;
    use App\Models\Category;
    use Exception;
    use Illuminate\Http\Request;

    class SubDecorationController extends Controller
    {
        public function index()
        {
            $title = 'Gestion des sous types de décorations';
            $categories = Category::all();

            $data = [];
            foreach($categories as $key => $cat){
                $data[$key] = ['id' => $cat->id,'name' => $cat->name];
            }
            if(request()->wantsJson()){
                return collect(SubCategory::orderBy('created_at','DESC')->with('category')->paginate(10));
            }
            return view('grade.index',['title' => $title,'categories'=> $categories]);
        }

        public function search($id)
        {
            $subCategories = SubCategory::where('category_id',$id)->get();
            $data = [];
            foreach($subCategories as $key => $cat){
                $data[$key] = ['id' => $cat->id,'name' => $cat->name];
            }

            return collect($data);
        }

        public function store(Request $request)
        {
            $this->validateGrade($request);
            $category = new SubCategory();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->category_id = $request->category_id;
            $category->save();
            return response()->json(['success' => true],200);
        }

        public function update(Request $request, $id)
        {
            $this->validateGrade($request,$id);
            $category = SubCategory::find($id);
            $category->name = $request->name;
            $category->description = $request->description;
            $category->category_id = $request->category_id;
            $category->save();
            return response()->json(['success' => true],200);
        }

        public function destroy($id)
        {
            $category = SubCategory::find($id);
            $category->delete();
            return response()->json(['OK'],200);
        }

        private function validateGrade(Request $request,$id = null){
            $rules =  [];
            if ($id){
                $rules['name'] = 'nullable|unique:sub_categories,id,' . $id;
            }else{
                $rules['name'] = 'required|unique:sub_categories';
            }
            return $request->validate($rules);
        }
    }
