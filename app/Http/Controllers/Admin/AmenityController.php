<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AmenityRequest;
use App\Models\Amenity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AmenityController extends Controller
{
    protected $entity = 'settings.amenities';


    public function index()
    {
        $companies = auth()->user()->companies->pluck('id')->prepend(0);
        $amenities = Amenity::select('*')->whereIn('company_id', $companies)->paginate(10);
        if (request()->ajax() && !request('_pjax')) return $this->ajaxView($amenities);

        return view('admin.' . $this->entity . '.index', compact('amenities') + ['entity' => $this->entity]);
    }

    public function create()
    {
        $amenity = new Amenity();
        $company = auth()->user()->company_id;
        return view('admin.' . $this->entity . '.edit', compact('amenity', 'company') + ['entity' => $this->entity]);

    }

    public function edit(Amenity $amenity)
    {
        $company = $amenity->company_id;
        return view('admin.' . $this->entity . '.edit', compact('amenity', 'company') + ['entity' => $this->entity]);
    }

    public function store(AmenityRequest $request)
    {
        if ($id = request('id')) {
            $amenity = Amenity::find($id);
            $amenity->update(request()->all());
        } else {
            $amenity = Amenity::create(request()->all());
        }
        $amenity->syncImages(request()->all());

        return $this->responseSuccess();

    }

    public function delete(Amenity $amenity)
    {
        $companies = auth()->user()->companies->pluck('id')->toArray();

        if (in_array($amenity->company_id, $companies) && $amenity->buses()->count() == 0) {
            $amenity->delete();
        }
        else{
            return $this->responseError(['message' => trans('messages.admin.settings.amenities.amenity_used')]);
        }
        return $this->responseSuccess();
    }
}
