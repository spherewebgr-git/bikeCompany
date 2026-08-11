<?php

namespace App\Http\Controllers;

use App\Models\Provision;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Speed;
use App\Models\Status;
use App\Models\Location;

use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'brands' => Brand::orderBy('name')->get(),
            'types' => Type::orderBy('name')->get(),
            'speeds' => Speed::orderBy('gears')->get(),
            'provisions' => Provision::orderBy('name')->get(),
            'statuses' => Status::orderBy("step")->get(),
            'locations' => Location::orderBy("name")->get(),
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'category' => 'required|in:gears,provision,location,status,type,brand',
            'query' => 'nullable|string',
        ]);

        $query = $request->input('query', '');

        switch ($request->category)
        {
            case 'gears':
                $results = Speed::where('gears', 'LIKE', "%{$query}%")->orderBy('gears')->get();
                break;

            case 'provision':
                $results = Provision::where('name', 'LIKE', "%{$query}%")->orderBy('name')->get();
                break;

            case 'location':
                $results = Location::where('name', 'LIKE', "%{$query}%")->orderBy('name')->get();
                break;

            case 'status':
                $results = Status::where('name', 'LIKE', "%{$query}%")->orderBy('step')->get();
                break;

            case 'type':
                $results = Type::where('name', 'LIKE', "%{$query}%")->orderBy('name')->get();
                break;

            case 'brand':
                $results = Brand::where('name', 'LIKE', "%{$query}%")->orderBy('name')->get();
                break;
        }

        return response()->json($results);
    }

    public function delete($id, string $category)
    {
        switch ($category)
        {
            case "provision":
                Provision::where('id', $id)->delete();
                break;
            case "brand":
                Brand::where('id', $id)->delete();
                break;
            case "type":
                Type::where('id', $id)->delete();
                break;
            case "gears":
                Speed::where('id', $id)->delete();
                break;
            case "status":
                Status::where('id', $id)->delete();
                break;
            case "location":
                Location::where('id', $id)->delete();
                break;
            default:
                abort(404);
        }

        return response()->json([ 'message' => 'Category deleted successfully.', ]);
    }

    public function create(Request $request, string $category)
    {
        switch ($category)
        {
            case "provision":
                $request->validate(['provname' => 'required']);
                Provision::firstOrCreate(['name' => $request->provname]);
                break;

            case "brand":
                $request->validate(['brandname' => 'required']);
                Brand::firstOrCreate(['name' => $request->brandname]);
                break;

            case "type":
                $request->validate(['typename' => 'required']);
                Type::firstOrCreate(['name' => $request->typename]);
                break;

            case "gears":
                $request->validate(['gearamount' => 'required']);
                Speed::firstOrCreate(['gears' => $request->gearamount]);
                break;

            case "status":
                $request->validate(['statname' => 'required', 'statstep' => 'required']);
                Status::firstOrCreate(['name' => $request->statname, 'step' => $request->statstep]);
                break;

            case "location":
                $request->validate(['locname' => 'required']);
                $request->validate(['latitude' => 'required']);
                $request->validate(['longitude' => 'required']);
                Location::firstOrCreate([
                    'name' => $request->locname,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]);
                break;

            default:
                abort(404);
        }

        return response()->json([
            'brands' => Brand::orderBy('name')->get(),
            'types' => Type::orderBy('name')->get(),
            'speeds' => Speed::orderBy('gears')->get(),
            'provisions' => Provision::orderBy('name')->get(),
            'statuses' => Status::orderBy("step")->get(),
            'locations' => Location::orderBy("name")->get(),
        ]);
    }

    public function edit(Request $request, string $category, $id)
    {
        switch ($category)
        {
            case 'brand':
                $request->validate([ 'name' => 'required|string', ]);
                Brand::where('id', $id)->update([ 'name' => $request->name, ]);
                break;

            case 'gears':
                $request->validate([ 'gears' => 'required|integer', ]);
                Speed::where('id', $id)->update([ 'gears' => $request->gears, ]);
                break;

            case 'status':
                $request->validate([
                    'name' => 'required|string',
                    'step' => 'required|integer',
                ]);
                Status::where('id', $id)->update([
                    'name' => $request->name,
                    'step' => $request->step,
                ]);
                break;

            case 'location':
                $request->validate([
                    'name' => 'required|string',
                    'latitude' => 'required|numeric',
                    'longitude' => 'required|numeric',
                ]);
                Location::where('id', $id)->update([
                    'name' => $request->name,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                break;

            default:
                abort(404);
        }

        return response()->json([ 'message' => 'Category updated successfully.', ]);
    }
}
