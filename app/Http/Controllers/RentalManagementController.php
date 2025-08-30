<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Company;
use App\Models\Floor;
use App\Models\Meter;
use App\Models\Property;
use App\Models\RentalBill;
use App\Models\RentalInvoice;
use App\Models\RentalPayment;
use App\Models\Renter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RentalManagementController extends Controller
{
    ////Property
    public function property_index(Request $request)
    {
        $data['main_menu']              = 'rent';
        $data['child_menu']             = 'property';

        $data['property_data']          = Property::where(['company_id' => Session::get('company_id')])->get();

        return view('rental_management.property.index', $data);
    }

    public function property_store(Request $request)
    {
        // return $request->all();
        // dd(session()->all());
        $request->validate([
            'name'          => 'required',
            'type'          => 'required',
            'phone'          => 'required',
            'address'          => 'required',
            'description'       => 'required',
            'property_image'     => 'required',
        ]);
        $model = new Property();
        $model->name             = $request->name;
        $model->type             = $request->type;
        $model->address          = $request->address;
        $model->description      = $request->description;
        $model->phone            = $request->phone;

        if ($request->property_image != null) {
            $property_image = 'property_image.' . $request->property_image->extension();
            $request->property_image->move(public_path('upload_images/property_image'), $property_image);
            $model->property_image = $property_image;
        }

        $model->company_id          = Session::get('company_id');
        $model->created_by          = auth()->user()->id;
        $model->save();

        // $msg = "Property Inserted.";
        // $request->session()->flash('message', $msg);

        return redirect('property-list')->with('status', 'Property Created!');
    }

    public function property_update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required',
            'type'          => 'required',
            'phone'          => 'required',
            'address'          => 'required',
            'description'          => 'required',
        ]);
        $property = Property::findOrFail($id);
        // dd($property);

        if ($request->hasFile('property_image')) {
            $property_image =  'property_image.' . $request->property_image->extension();
            $request->property_image->move(public_path('upload_images/property_image'), $property_image);
            $property->property_image = $property_image;
            // dd($property_image);
        } elseif ($request->has('remove_image')) {
            $property->property_image = null;
        }

        $property->name         = $request->name;
        $property->type         = $request->type;
        $property->description  = $request->description;
        $property->phone        = $request->phone;
        $property->address      = $request->address;
        $property->company_id   = Session::get('company_id');
        $property->updated_by   = auth()->user()->id;
        $property->update();
        // dd($property);

        // $msg = "Property Updated.";
        // $request->session()->flash('message', $msg);

        return redirect()->route('property_list')->with('status', 'Property updated!');
    }

    public function property_status($id)
    {
        $property = Property::find($id);

        if ($property->status == 1) {
            $property->status = 0;
        } else {
            $property->status = 1;
        }

        $property->save();
        return redirect()->route('property_list')->with('status', 'Property Status Updated Successfully');
    }


    ////Floor
    public function floor_index(Request $request)
    {
        $data['main_menu']              = 'rent';
        $data['child_menu']             = 'floor';
        $data['properties']         = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['floor_data']         = Floor::where(['company_id' => Session::get('company_id')])->get();

        return view('rental_management.floor.index', $data);
    }

    public function floor_store(Request $request)
    {
        $request->validate([
            'floor_name'          => 'required',
            'property_id'          => 'required',
        ]);
        $model = new Floor();
        $model->floor_name           = $request->floor_name;
        $model->property_id          = $request->property_id;
        $model->description          = $request->description;
        $model->company_id          = Session::get('company_id');
        $model->created_by          = auth()->user()->id;
        $model->save();

        // $msg = "Floor Inserted.";
        // $request->session()->flash('message', $msg);

        return redirect('floor-list')->with('status', 'Floor Created!');
    }

    public function floor_update(Request $request, $id)
    {
        $request->validate([
            'floor_name'          => 'required',
            'property_id'          => 'required',
        ]);
        $floor = Floor::findOrFail($id);
        // dd($floor);
        $floor->floor_name           = $request->floor_name;
        $floor->property_id          = $request->property_id;
        $floor->description          = $request->description;
        $floor->company_id           = Session::get('company_id');
        $floor->updated_by           = auth()->user()->id;
        $floor->update();
        // dd($floor);

        // $msg = "Floor Updated.";
        // $request->session()->flash('message', $msg);

        return redirect()->route('floor_list')->with('status', 'Floor updated!');
    }

    public function floor_status($id)
    {
        $floor = Floor::find($id);

        if ($floor->status == 1) {
            $floor->status = 0;
        } else {
            $floor->status = 1;
        }

        $floor->save();
        return redirect()->route('floor_list')->with('status', 'Floor Status Updated Successfully');
    }



    ////Meter
    public function meter_index(Request $request)
    {
        $data['main_menu']              = 'rent';
        $data['child_menu']             = 'meter';
        // $data['properties']         = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['meter_data']         = Meter::where(['company_id' => Session::get('company_id')])->get();
        //  dd($data['meter_data']);
        return view('rental_management.meter.index', $data);
    }

    public function meter_store(Request $request)
    {
        $request->validate([
            'meter_number'          => 'required',
            'installation_date'          => 'required',
        ]);
        $model = new Meter();
        $model->meter_number           = $request->meter_number;
        $model->installation_date      = $request->installation_date;
        $model->note                   = $request->note;
        $model->company_id             = Session::get('company_id');
        $model->created_by             = auth()->user()->id;
        $model->save();

        // $msg = "Meter Inserted.";
        // $request->session()->flash('message', $msg);

        return redirect('meter-list')->with('status', 'Meter Created!');
    }

    public function meter_update(Request $request, $id)
    {
        $request->validate([
            'meter_number'               => 'required',
            'installation_date'          => 'required',
        ]);
        $meter = Meter::findOrFail($id);
        // dd($meter);
        $meter->meter_number           = $request->meter_number;
        $meter->installation_date      = $request->installation_date;
        $meter->note                   = $request->note;
        $meter->company_id             = Session::get('company_id');
        $meter->updated_by             = auth()->user()->id;
        $meter->update();
        // dd($meter);

        // $msg = "Meter Updated.";
        // $request->session()->flash('message', $msg);

        return redirect()->route('meter_list')->with('status', 'Meter updated!');
    }

    public function meter_status($id)
    {
        $meter = Meter::find($id);

        if ($meter->status == 1) {
            $meter->status = 0;
        } else {
            $meter->status = 1;
        }

        $meter->save();
        return redirect()->route('meter_list')->with('status', 'Meter Status Updated Successfully');
    }


    /////Unit
    public function unit_index(Request $request)
    {
        $data['main_menu']              = 'rent';
        $data['child_menu']             = 'unit';

        $data['properties']     = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['floors']         = Floor::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['meters']         = Meter::with('unit')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $unit_data              = Unit::with('meter', 'company', 'renter', 'property', 'floor')->where(['company_id' => Session::get('company_id')]);
        // dd($unit_data);
        $where = array();
        if ($request->property_id) {
            $where['property_id'] = $request->property_id;
            $unit_data = $unit_data->where('property_id', '=', $request->property_id);
        }
        if ($request->floor_id) {
            $where['floor_id'] = $request->floor_id;
            $unit_data = $unit_data->where('floor_id', '=', $request->floor_id);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $unit_data = $unit_data->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $unit_data = $unit_data->whereDate('created_at', '<=', $request->end_date);
        }

        $unit_data = $unit_data->orderBy('id', 'desc')->paginate(20);
        $unit_data->appends($where);
        $data['unit_data'] = $unit_data;

        return view('rental_management.unit.index', $data);
    }

    public function unit_create()
    {
        $data['main_menu'] = 'rent';
        $data['child_menu'] = 'create-unit';
        $data['companies'] = Company::where('status', 1)->get();
        $data['properties'] = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['floors'] = Floor::with('property', 'company')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['meters'] = Meter::with('unit')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();

        return view('rental_management.unit.create', $data);
    }

    public function unit_store(Request $request)
    {
        $request->validate([
            'unit_name'       => 'required',
            'rent_amount'     => 'required',
            'gas_bill'        => 'required',
            'water_bill'      => 'required',
            'service_charge'  => 'required',
            'size'            => 'required',
            'property_id'     => 'required',
            'meter_id'        => 'required',
            'e_rate_per_unit' => 'required',
            'floor_id'        => 'required',
            'note'            => 'required',
        ]);
        $model = new Unit();
        $model->unit_name          = $request->unit_name;
        $model->rent_amount        = $request->rent_amount;
        $model->size               = $request->size;
        $model->service_charge     = $request->service_charge;
        $model->water_bill         = $request->water_bill;
        $model->gas_bill           = $request->gas_bill;
        $model->trash_bill         = $request->trash_bill;
        $model->security_bill      = $request->security_bill;
        $model->meter_id           = $request->meter_id;
        $model->e_rate_per_unit    = $request->e_rate_per_unit;
        $model->property_id        = $request->property_id;
        $model->floor_id           = $request->floor_id;
        $model->note               = $request->note;
        $model->company_id         = Session::get('company_id');
        $model->created_by         = auth()->user()->id;
        $model->save();

        $meter = Meter::where('id', $model->meter_id)->first();
        // dd($meter);
        $meter->unit_id = $model->id;
        $meter->save();

        return redirect('unit-list')->with('status', 'Unit Created!');
    }

    public function edit_unit($id)
    {
        $data['main_menu'] = 'rent';
        $data['child_menu'] = 'unit';
        $data['companies'] = Company::where('status', 1)->get();
        $data['properties'] = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['floors'] = Floor::with('property', 'company')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['meters'] = Meter::with('unit')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['unit'] = Unit::with('company', 'renter', 'property')->find($id);

        return view('rental_management.unit.edit', $data);
    }

    public function unit_update(Request $request, $id)
    {
        $request->validate([
            'unit_name'       => 'required',
            'rent_amount'     => 'required',
            'size'            => 'required',
            'gas_bill'        => 'required',
            'water_bill'      => 'required',
            'service_charge'  => 'required',
            'property_id'     => 'required',
            'meter_id'        => 'required',
            'e_rate_per_unit' => 'required',
            'floor_id'        => 'required',
            'note'            => 'required',
        ]);
        $unit = Unit::findOrFail($id);
        // dd($unit);

        $unit->unit_name          = $request->unit_name;
        $unit->rent_amount        = $request->rent_amount;
        $unit->size               = $request->size;
        $unit->service_charge     = $request->service_charge;
        $unit->water_bill         = $request->water_bill;
        $unit->gas_bill           = $request->gas_bill;
        $unit->trash_bill         = $request->trash_bill;
        $unit->security_bill      = $request->security_bill;
        $unit->meter_id           = $request->meter_id;
        $unit->e_rate_per_unit    = $request->e_rate_per_unit;
        $unit->property_id        = $request->property_id;
        $unit->floor_id           = $request->floor_id;
        $unit->note               = $request->note;
        $unit->current_status     = $request->current_status;
        $unit->company_id         = Session::get('company_id');
        $unit->updated_by         = auth()->user()->id;
        $unit->update();
        // dd($unit);
        Meter::where('id', $unit->meter_id)->where('company_id', Session::get('company_id'))->update(['unit_id' => $unit->id]);

        // $msg = "Unit Updated.";
        // $request->session()->flash('message', $msg);

        return redirect()->route('unit_list')->with('status', 'Unit updated!');
    }

    public function unit_status($id)
    {
        $unit = Unit::find($id);

        if ($unit->status == 1) {
            $unit->status = 0;
        } else {
            $unit->status = 1;
        }

        $unit->save();
        return redirect()->route('unit_list')->with('status', 'Unit Status Updated Successfully');
    }


    // public function current_status(Request $request, $id)
    // {
    //     try {
    //         $data = Unit::find($id);

    //         $status = $request->input('current_status');
    //         if ($status !== null) {
    //             $data->current_status = $status;
    //         }

    //         $data->save();
    //         return redirect()->back()->with('message', "Unit Current Status Updated!");
    //     } catch (\Exception $exception) {
    //         return redirect()->back()->with('error', $exception->getMessage());
    //     }
    // }


    ////Renter
    public function renter_index(Request $request)
    {
        $data['main_menu']      = 'rent';
        $data['child_menu']     = 'renter';
        $data['properties']     = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['units']          = Unit::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['floors']         = Floor::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['renter_data']    = Renter::with('company', 'property', 'unit', 'floor')->where(['company_id' => Session::get('company_id')])->get();
        return view('rental_management.renter_info.index', $data);
    }

    public function renter_store(Request $request)
    {
        // return $request->all();
        // dd(session()->all());
        $request->validate([
            'name'          => 'required',
            'property_id'   => 'required',
            'unit_id'       => 'required',
            'floor_id'      => 'required',
            'phone'         => 'required',
            'email'         => 'required',
            // 'document'      => 'required',
        ]);
        $model = new Renter();
        $model->name             = $request->name;
        $model->property_id      = $request->property_id;
        $model->unit_id          = $request->unit_id;
        $model->floor_id         = $request->floor_id;
        $model->father_name      = $request->father_name;
        $model->mother_name      = $request->mother_name;
        $model->business_address = $request->business_address;
        $model->business_category = $request->business_category;
        $model->present_address  = $request->present_address;
        $model->permanent_address = $request->permanent_address;
        $model->agreement_period = $request->agreement_period;
        $model->rent_start_date = $request->rent_start_date;
        $model->rent_end_date = $request->rent_end_date;
        $model->advance = $request->advance;
        $model->advance_left = $request->advance;
        $model->agreement_end_notice = $request->agreement_end_notice;
        $model->starting_month = $request->starting_month;
        $model->phone            = $request->phone;
        $model->email            = $request->email;
        $model->rent_duration    = $request->rent_duration;

        if ($request->agreement_document != null) {
            $agreement_document = 'agreement_document.' . $request->agreement_document->extension();
            $request->agreement_document->move(public_path('upload_images/agreement_document'), $agreement_document);
            $model->agreement_document = $agreement_document;
        }

        if ($request->trade_license != null) {
            $trade_license = 'trade_license.' . $request->trade_license->extension();
            $request->trade_license->move(public_path('upload_images/trade_license'), $trade_license);
            $model->trade_license = $trade_license;
        }

        if ($request->nid != null) {
            $nid = 'nid.' . $request->nid->extension();
            $request->nid->move(public_path('upload_images/nid_copy'), $nid);
            $model->nid = $nid;
        }

        $model->company_id          = Session::get('company_id');
        $model->created_by          = auth()->user()->id;
        $model->save();

        $unit = Unit::where('id', $model->unit_id)->first();
        // dd($unit);
        $unit->renter_id = $model->id;
        $unit->current_status = 2;
        $unit->save();

        // $msg = "Renter Inserted.";
        // $request->session()->flash('message', $msg);

        return redirect('renter-list')->with('status', 'Renter Added!');
    }

    public function renter_update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required',
            'property_id'   => 'required',
            'unit_id'       => 'required',
            'floor_id'      => 'required',
            'phone'         => 'required',
            'email'         => 'required',
            // 'document'      => 'required',
        ]);
        $renter = Renter::findOrFail($id);
        // dd($renter);

        if ($request->hasFile('agreement_document')) {
            $agreement_document =  'agreement_document.' . $request->agreement_document->extension();
            $request->agreement_document->move(public_path('upload_images/agreement_document'), $agreement_document);
            $renter->agreement_document = $agreement_document;
            // dd($renter);
        } elseif ($request->has('remove_image')) {
            $renter->agreement_document = null;
        }


        if ($request->hasFile('trade_license')) {
            $trade_license =  'trade_license.' . $request->trade_license->extension();
            $request->trade_license->move(public_path('upload_images/trade_license'), $trade_license);
            $renter->trade_license = $trade_license;
            // dd($renter);
        } elseif ($request->has('remove_image')) {
            $renter->trade_license = null;
        }


        if ($request->hasFile('nid')) {
            $nid =  'nid.' . $request->nid->extension();
            $request->nid->move(public_path('upload_images/nid_copy'), $nid);
            $renter->nid = $nid;
            // dd($renter);
        } elseif ($request->has('remove_image')) {
            $renter->nid = null;
        }

        $renter->name             = $request->name;
        $renter->property_id      = $request->property_id;
        $renter->unit_id          = $request->unit_id;
        $renter->floor_id         = $request->floor_id;
        $renter->father_name      = $request->father_name;
        $renter->mother_name      = $request->mother_name;
        $renter->business_address = $request->business_address;
        $renter->business_category = $request->business_category;
        $renter->present_address  = $request->present_address;
        $renter->permanent_address = $request->permanent_address;
        $renter->agreement_period = $request->agreement_period;
        $renter->rent_start_date = $request->rent_start_date;
        $renter->rent_end_date = $request->rent_end_date;
        $renter->advance = $request->advance;
        $renter->advance_left = $request->advance;
        $renter->agreement_end_notice = $request->agreement_end_notice;
        $renter->starting_month = $request->starting_month;
        $renter->phone            = $request->phone;
        $renter->email            = $request->email;
        $renter->rent_duration    = $request->rent_duration;
        $renter->company_id       = Session::get('company_id');
        $renter->updated_by       = auth()->user()->id;
        $renter->update();
        // dd($renter);

        Unit::where('id', $renter->unit_id)
            ->where('company_id', Session::get('company_id'))
            ->update([
                'renter_id' => $renter->id,
                'current_status' => 2
            ]);


        // $msg = "Renter Updated.";
        // $request->session()->flash('message', $msg);

        return redirect()->route('renter_list')->with('status', 'Renter updated!');
    }

    public function renter_status($id)
    {
        $renter = Renter::find($id);

        if ($renter->status == 1) {
            $renter->status = 0;

            Unit::where('id', $renter->unit_id)
            ->where('company_id', Session::get('company_id'))
            ->update([
                'current_status' => 1
            ]);

        } else {
            $renter->status = 1;

            Unit::where('id', $renter->unit_id)
            ->where('company_id', Session::get('company_id'))
            ->update([
                'current_status' => 2
            ]);
        }

        $renter->save();
        return redirect()->route('renter_list')->with('status', 'Renter Status Updated Successfully');
    }



    ////Rental Bill Generate
    public function rental_bill_index(Request $request)
    {
        $type = $request->type;
        $status = $request->status;
        $data['main_menu'] = 'rent';
        $data['child_menu'] = 'rental-bill-list';
        $data['companies'] = Company::where('status', 1)->get();
        $data['properties'] = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['floors'] = Floor::with('property', 'company')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['meters'] = Meter::with('unit')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['units']  = Unit::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['renters']  = Renter::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $bill_data        = RentalBill::with('company', 'renter', 'unit')->where(['company_id' => Session::get('company_id')]);
        // dd($bill_data);
        $where = array();
        if ($request->has('status') && in_array($status, [1, 2, 3])) {
            $bill_data = $bill_data->where('status', '=', $status);
        }
        if ($request->has('type') && in_array($type, ['Monthly Rent', 'Electricity Bill'])) {
            $bill_data = $bill_data->where('type', '=', $type);
        }
        if ($request->property_id) {
            $bill_data = $bill_data->whereHas('unit', function ($query) use ($request) {
                $query->where('property_id', $request->property_id);
            });
        }
        if ($request->floor_id) {
            $bill_data = $bill_data->whereHas('unit', function ($query) use ($request) {
                $query->where('floor_id', $request->floor_id);
            });
        }
        if ($request->renter_id) {
            $where['renter_id'] = $request->renter_id;
            $bill_data = $bill_data->where('renter_id', '=', $request->renter_id);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $bill_data = $bill_data->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $bill_data = $bill_data->whereDate('created_at', '<=', $request->end_date);
        }

        $bill_data = $bill_data->orderBy('id', 'desc')->paginate(20);
        $bill_data->appends($where);
        $data['bill_data'] = $bill_data;

        return view('rental_management.rental_bill.bill_list', $data);
    }


    public function rental_bill_generate()
    {
        $data['main_menu'] = 'rent';
        $data['child_menu'] = 'rental-bill-generate';
        $data['companies'] = Company::where('status', 1)->get();
        $data['properties'] = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['floors'] = Floor::with('property', 'company')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['meters'] = Meter::with('unit')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $data['units']  = Unit::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();

        return view('rental_management.rental_bill.generate_bill', $data);
    }

    public function generateRentalBill(Request $request)
    {
        // dd($request->all());
        $main_menu = 'rent';
        $child_menu = 'rental-bill-generate';
        $properties = Property::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $units = Unit::where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $floors = Floor::with('property', 'company')->where(['company_id' => Session::get('company_id')])->where('status', 1)->orderByDesc('id')->get();
        $billType = $request->input('type');
        $month = $request->input('month');
        // dd($month);
        $property_id = $request->input('property_id');
        $floor_id = $request->input('floor_id');
        $unit_id = $request->input('unit_id');

        $renter = Renter::with('property', 'unit', 'floor')->where('property_id', $property_id)->where('floor_id', $floor_id)->where('unit_id', $unit_id)->where('status', 1)->where(['company_id' => Session::get('company_id')])->first();
        // dd($renter);
        $meter = Meter::with('unit')->where('unit_id', $unit_id)->where('status', 1)->where(['company_id' => Session::get('company_id')])->first();
        $unit = Unit::where('id', $unit_id)->where('status', 1)->where(['company_id' => Session::get('company_id')])->first();
        // dd($unit);

        if (!$renter || !$meter || !$unit) {
            $msg = "Something is missing. Please check your Renter/Meter/Unit data.";
            return redirect()->back()->with('warning', $msg);
        }


        return view('rental_management.rental_bill.generate_bill', compact('unit', 'main_menu', 'child_menu', 'month', 'meter', 'renter', 'floors', 'properties', 'units', 'billType'));
    }

    public function printRentalInvoice($id)
    {
        try {
            $model      = RentalBill::where('id', $id)->first();
            return view('rental_management.rental_bill.rental_invoice_print', compact('model'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function electricity_bill_store(Request $request)
    {
        // return $request->all();
        // dd(session()->all());
        // $request->validate([
        //     'name'          => 'required',
        //     'property_id'   => 'required',
        //     'unit_id'       => 'required',
        //     'floor_id'      => 'required',
        //     'phone'         => 'required',
        //     'email'         => 'required',
        //     'document'      => 'required',
        // ]);

        $lastInvoice = RentalBill::latest()->first();
        $lastInvoiceNo = ($lastInvoice) ? $lastInvoice->id : 0;
        // $date = now()->format('Y');
        $invoice_no = "RINV-0" . $lastInvoiceNo + 1;

        $model = new RentalBill();
        $model->renter_id        = $request->renter_id;
        $model->unit_id          = $request->unit_id;
        $model->bill_date        = $request->bill_date;
        $model->due_date         = $request->due_date;
        $model->invoice_no         = $invoice_no;
        $model->total_amount     = $request->total_amount;
        $model->month            = $request->month;
        $model->last_reading_date   = $request->last_reading_date;
        $model->last_reading        = $request->last_reading;
        $model->current_reading_date = $request->current_reading_date;
        $model->current_reading      = $request->current_reading;
        $model->type             = $request->type;
        $model->note             = $request->note;
        $model->due_amount       = $request->total_amount;
        $model->status           = 1;
        $model->company_id       = Session::get('company_id');
        $model->created_by       = auth()->user()->id;
        $model->save();

        // $msg = "Electricity Bill Inserted.";
        // $request->session()->flash('message', $msg);

        return redirect('rental-bill-generate')->with('status', 'Electricity Bill Inserted!');
    }

    public function rental_bill_store(Request $request)
    {
        // return $request->all();
        // dd(session()->all());
        // $request->validate([
        //     'name'          => 'required',
        //     'property_id'   => 'required',
        //     'unit_id'       => 'required',
        //     'floor_id'      => 'required',
        //     'phone'         => 'required',
        //     'email'         => 'required',
        //     'document'      => 'required',
        // ]);

        $lastInvoice = RentalBill::latest()->first();
        $lastInvoiceNo = ($lastInvoice) ? $lastInvoice->id : 0;
        // $date = now()->format('Y');
        $invoice_no = "RINV-0" . $lastInvoiceNo + 1;

        if (!$request->advance_deduction) {
            $model = new RentalBill();
            $model->renter_id        = $request->renter_id;
            $model->unit_id          = $request->unit_id;
            $model->bill_date        = $request->bill_date;
            $model->due_date         = $request->due_date;
            $model->invoice_no       = $invoice_no;
            $model->total_amount     = $request->total_amount;
            $model->month            = $request->month;
            $model->type             = $request->type;
            $model->note             = $request->note;
            $model->due_amount       = $request->total_amount;
            $model->status           = 1;
            $model->company_id       = Session::get('company_id');
            $model->created_by       = auth()->user()->id;
            $model->save();

            return redirect('rental-bill-generate')->with('status', 'Rental Bill Inserted!');
        } else {
            $model = new RentalBill();
            $model->renter_id        = $request->renter_id;
            $model->unit_id          = $request->unit_id;
            $model->invoice_no       = $invoice_no;
            $model->advance_deduction = $request->advance_deduction;
            $model->total_amount     = $request->advance_deduction;
            $model->month            = $request->month;
            $model->type             = $request->type;
            $model->note             = $request->note;
            $model->due_amount       = 0;
            $model->status           = 2;
            $model->company_id       = Session::get('company_id');
            $model->created_by       = auth()->user()->id;
            $model->save();

            $payment = new RentalPayment();
            $payment->renter_id        = $request->renter_id;
            $payment->bill_id          = $model->id;
            $payment->unit_id          = $request->unit_id;
            $payment->date             = now()->format('Y-m-d');
            $payment->amount           = $request->advance_deduction;
            $payment->month            = $request->month;
            $payment->company_id       = Session::get('company_id');
            $payment->created_by       = auth()->user()->id;
            $payment->save();

            $renter = Renter::find($model->renter_id);

            if ($renter) {
                $renter->advance_left = $renter->advance - $request->advance_deduction;
                $renter->save();
            }
            return redirect('rental-bill-generate')->with('status', 'Monthly Rent Deducted From Advance!');
        }
    }

    public function rental_print(Request $request)
    {
        try {
            $data['title']               = 'Bill List || ' . Session::get('company_name');
            $bill_data                   = RentalBill::where(['company_id' => Session::get('company_id')])->with('company');
            $where = array();
            $type = $request->type;
            $status = $request->status;
            if ($request->property_id) {
                $where['property_id'] = $request->property_id;
                $bill_data->where('property_id', '=', $request->property_id);
            }
            if ($request->unit_id) {
                $where['unit_id'] = $request->unit_id;
                $bill_data->where('unit_id', '=', $request->unit_id);
            }
            if ($request->floor_id) {
                $where['floor_id'] = $request->floor_id;
                $bill_data->where('floor_id', '=', $request->floor_id);
            }
            if ($request->renter_id) {
                $where['renter_id'] = $request->renter_id;
                $bill_data->where('renter_id', '=', $request->renter_id);
            }
            if ($request->has('status') && in_array($status, [1, 2, 3])) {
                $bill_data = $bill_data->where('status', '=', $status);
            }
            if ($request->has('type') && in_array($type, ['Monthly Rent', 'Electricity Bill'])) {
                $bill_data = $bill_data->where('type', '=', $type);
            }
            if ($request->start_date) {
                $where['start_date'] = $request->start_date;
                $bill_data->whereDate('payment_date', '>=', $request->start_date);
            }
            if ($request->end_date) {
                $where['end_date'] = $request->end_date;
                $bill_data->whereDate('payment_date', '<=', $request->end_date);
            }

            $bill_data = $bill_data->paginate(20);
            $bill_data->appends($where);
            $data['bill_data']             = $bill_data;
            return view('rental_management.rental_bill.print_bill_list', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    ////Rental Payment
    public function save_bill_payment(Request $request)
    {
        // return $request->all();
        // dd(session()->all());
        // $request->validate([
        //     'name'          => 'required',
        //     'property_id'   => 'required',
        //     'unit_id'       => 'required',
        //     'floor_id'      => 'required',
        //     'phone'         => 'required',
        //     'email'         => 'required',
        //     'document'      => 'required',
        // ]);
        $model = new RentalPayment();
        $model->renter_id        = $request->renter_id;
        $model->bill_id          = $request->bill_id;
        $model->unit_id          = $request->unit_id;
        $model->date             = $request->date;
        $model->amount           = $request->amount;
        $model->month            = $request->month;
        $model->company_id       = Session::get('company_id');
        $model->created_by       = auth()->user()->id;
        $model->save();

        $bill = RentalBill::find($model->bill_id);

        if ($bill) {
            $totalPaid = RentalPayment::where('bill_id', $model->bill_id)->sum('amount');
            $bill->due_amount = $bill->total_amount - $totalPaid;
            $bill->save();

            if ($bill->due_amount == 0) {
                $bill->status = 2;
            } elseif ($bill->due_amount > 0 && $bill->due_amount < $bill->total_amount) {
                $bill->status = 3;
            } else {
                $bill->status = 1;
            }
            $bill->save();
        }

        return redirect('rental-bill-list')->with('status', 'Payment Made!');
    }


    ///Concat
    public function getFloorsByProperty($propertyId)
    {
        $floors = Floor::where('property_id', $propertyId)->where('status', 1)->get();
        // dd($floors);

        return response()->json(['floors' => $floors]);
    }

    public function getUnitsByFloor($floorId, $propertyId)
    {
        $rentedUnits = Renter::where('property_id', $propertyId)
            ->where('status', 1)
            ->where('floor_id', $floorId)
            ->pluck('unit_id');

        $units = Unit::where('floor_id', $floorId)
            ->where('status', 1)
            ->whereNotIn('id', $rentedUnits)
            ->get();

        return response()->json(['units' => $units]);
    }

    public function getUnitsFloorWise($floorId, $propertyId)
    {
        $units = Unit::where('floor_id', $floorId)
            ->where('property_id', $propertyId)
            ->where('status', 1)
            ->get();

        return response()->json(['units' => $units]);
    }
}
