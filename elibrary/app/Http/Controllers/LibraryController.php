<?php
namespace App\Http\Controllers;

use App\adminUsers;
use App\user_accounts;
use App\library_categories;
use App\library_data;
use App\library_subcategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use DB;
use Image;
use File;
use Session;
use Illuminate\Pagination\LengthAwarePaginator;

class LibraryController extends Controller
{
    public function getCategory()
    {
        $availableCategories = library_categories::select('*')->where([['id','>',0],['status','=',1]])->get();
        return view('admin.categories',["categories"=>$availableCategories]);
    }

    public function createCategory(Request $request)
    {
        $this->validate($request,[
            "category_name"=>'required|unique:library_categories,name|max:20',
            "category_short_name"=>'required|unique:library_categories,short_name|max:10',
            "visibility"=>'required'
        ]);

        $cateObject = new library_categories();
        $cateObject->name = $request['category_name'];
        $cateObject->short_name = $request['category_short_name'];
        $cateObject->status = $request['visibility'];
        $cateObject->created_by = Session::get('accountDetails.username');

        if($cateObject->save())
        {
            $message = "New Category Successfully Added";
            return redirect()->back()->With(['message'=>$message]);
        }
        else{

            $error = new MessageBag(['Error:Unable to create category']);
			return redirect()->back()->withErrors($error);
        }

    }

    public function getLibraryContent()
    {
        $availableData = library_data::select('*')->where([['id','>',0],['status','=',1]])->paginate();
        return view('admin.library',["libraryContents"=>$availableData]);
    }

    public function getMediaLink($fileName)
	{

		$filedata = Storage::disk('local')->get('/mediafiles/'.$fileName);

		return $filedata;
		
	}

    public function getDeleteLesson($lesson_id)
	{
		
		$filenames = Lessons::select("cover","attachment")->where("id",$lesson_id)->first();

		$lessonData = Lessons::where("id", $lesson_id)->first();

		if($lessonData->delete())
		{
			$message = 'Lesson Successfully Deleted';

			//also delete the files associated with this lesson
			File::delete(public_path('/covers/'.$filenames->cover)); //for covers

			Storage::delete('/mediafiles/'.$filenames->attachment);

			//checking if media file exist
			$mediaExist = Storage::disk('local')->has('/mediafiles/'.$filenames->attachment);

			//checking if cover exist
			$coverExist = File::exists(public_path('/ covers/'.$filenames->cover));

			//creating success message
			
			if($mediaExist && $coverExist)
			{
				$message.=" .But unable to delete media files associated with deleted lesson, you need to remove this manually";
			}

			return redirect()->back()->with(["message"=>$message]);

		}
		else
		{
			
			$errors = new MessageBag;

			$error = new MessageBag(['errormessage' => ['Unable to Delete Lesson']]);
			return redirect()->back()->withErrors($error);

		}

	}

	public function addToLibrary(Request $request)
	{
		
		$this->validate($request, [
			"name" => 'required|max:100',
			"description" => 'required|max:500',
			"author" => 'required',
			"category" => 'required',
			"book_cover" => 'sometimes|required|mimes:jpg,jpeg',
            "visibility" => 'required',
			"media_file" => 'required|mimes:mp4,pdf'

		]);

		$newLibraryData = new library_data();

		$newLibraryData->name = $request['name'];
		$newLibraryData->description = $request['description'];
		$newLibraryData->authors = $request['author'];
		$newLibraryData->category = $request['category'];
		$newLibraryData->subcategory = $request['subcategory'];
		$newLibraryData->isbn = $request['book_isbn'];
		$newLibraryData->tags = $request['tag'];
		$newLibraryData->added_by = Session::get('accountDetails.username');
        $newLibraryData->status = $request['visibility'];

        if(!$request->hasfile('book_cover'))
        {
            $pickThumb = rand(1,2);
            $coverPicked = 'edcodelab_nothumb_'.$pickThumb.'.png';
            $addMe = false;
            $newLibraryData->cover_image = $coverPicked;
        }
        else
        {
            $cover = $request->file('book_cover');

			$coverExt = $cover->getClientOriginalExtension();

			$coverPicked = time().'.'.$coverExt;

            $newLibraryData->cover_image = $coverPicked;

            $addMe = true;
        }

		if($request->hasfile('media_file'))
			{

				$mediafile = $request->file('media_file');

				$mediaExt = $mediafile->getClientOriginalExtension();

				$mediaName = time().'.'.$mediaExt;

				$newLibraryData->media = $mediaName;

				$newLibraryData->book_type_format = $mediaExt;

				if($newLibraryData->save())
				{
					if($addMe)
                    {
                        Image::make($cover)->resize(600,300)->save(public_path('assets/images/cover/'.$coverPicked));
                    }

					Storage::disk('local')->put('/mediafiles/'.$mediaName, File::get($mediafile));

					$message = 'New Item Added Successfully';

					return redirect()->back()->with(["message"=>$message]);

				}
				else
				{
					
					$errors = new MessageBag;

					$error = new MessageBag(['errormessage' => ['Unable to add content to library']]);

					return redirect()->back()->withErrors($error);

				}

			}
			else
			{

					$errors = new MessageBag;

					$error = new MessageBag(['errormessage' => ['Media File is very important, upload and try again...']]);

					return redirect()->back()->withErrors($error);

			}
	}

    public function getLibraryItem($category,$id)
    {
        $item = library_data::select('*')->where('id',$id)->first();

        $related = $item->LibraryData;

        return view('view.view-item',["item"=>$item, "related"=>$related]);
    }
}