<?php

namespace App\Http\Controllers;

use App\Models\DonorName;
use App\Models\LandDocumet;
use App\Models\LandPurchase;
use App\Models\LandPurchaseDetail;
use App\Models\LandStock;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LandPurchaseController extends Controller
{

    public function index()
    {
        $data['main_menu'] = 'land_purchase';
        $data['child_menu'] = 'land_purchase_list';
        $data['landPurchase'] = LandPurchase::with('projectInformation', 'landPurchaseDetails')->paginate(20);
        return view('land_purchase.purchase_list', $data);
    }
    public function create()
    {
        $data['main_menu'] = 'land_purchase';
        $data['child_menu'] = 'land_purchase_create';
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();
        return view('land_purchase.create', $data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer',
            'purchase_date' => 'required|date',
            'dolil_no' => 'required|array',
            'rs_dag_no' => 'required|array',
            'khatian_no' => 'required|array',
            'khazna_no' => 'required|array',
            'shotangso' => 'required|array',
            'per_shotangso_rate' => 'required|array',
            'total_amount' => 'required|array',
            'medium_amount' => 'required|array',
            'type' => 'required|array',
            'remarks' => 'required|array',
            'donor_name' => 'required|array',
            'attached_file.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();
            $dolilNo = $request->input('dolil_no');
            $total_amount = 0;
            $totalShotangso = 0;
            for ($i = 0; $i < count($dolilNo); $i++) {
                $total_amount += $request->total_amount[$i];
                $totalShotangso += $request->shotangso[$i];
            }
            $purchaseData = [
                'project_id' => $request->project_id,
                'purchase_date' => $request->purchase_date,
                'current_date' => date('Y-m-d'),
                'total_amount' => $total_amount,
                'total_purchase_land' => $totalShotangso,
                'created_by' => auth()->user()->id,
            ];
            $files = $request->file('land_documents');
            $donorName = $request->donor_name;
            $check = LandPurchase::create($purchaseData);
            for ($i = 0; $i < count($dolilNo); $i++) {
                $landPurchaseDetail = LandPurchaseDetail::create([
                    'land_purchase_id' => $check->id,
                    'dolil_no' => $request->input('dolil_no')[$i],
                    'rs_dag_no' => $request->input('rs_dag_no')[$i],
                    'khatian_no' => $request->input('khatian_no')[$i],
                    'khazna_no' => $request->input('khazna_no')[$i],
                    'shotangso' => $request->input('shotangso')[$i],
                    'per_shotangso_rate' => $request->input('per_shotangso_rate')[$i],
                    'total_amount' => $request->input('total_amount')[$i],
                    'medium_amount' => $request->input('medium_amount')[$i],
                    'type' => $request->input('type')[$i],
                    'remarks' => $request->input('remarks')[$i],
                ]);
                if (isset($files[$i + 1])) {
                    foreach ($files[$i + 1] as $index => $attachment) {
                        if ($attachment instanceof \Illuminate\Http\UploadedFile) {
                            $attachmentName = time() . "_land_purchase_file_" . $i + 1 . '.' . $attachment->getClientOriginalExtension();
                            $attachment->move(public_path('land_purchase'), $attachmentName);
                            // dd($request->land_documents_type_name[$i][$index]);
                            LandDocumet::create([
                                'land_documents' => $attachmentName,
                                'land_documents_type_name' => $request->land_documents_type_name[$i + 1][$index],
                                'land_purchase_detail_id' => $landPurchaseDetail->id,
                            ]);
                        }
                    }
                }

                if (isset($donorName[$i + 1])) {
                    foreach ($request->donor_name[$i + 1] as $donorIndex => $donorName) {
                        DonorName::create([
                            'land_purchase_detail_id' => $landPurchaseDetail->id,
                            'donor_name' => $request->donor_name[$i + 1][$donorIndex],
                        ]);
                    }
                }
            }
            $landStock = LandStock::where('project_id', $request->project_id)->first();

            if ($landStock) {
                $landStock->total_stock_land += $totalShotangso;
                $landStock->total_amount += $total_amount;
                $landStock->created_by = auth()->user()->id;
                $landStock->current_date = date('Y-m-d');
                $landStock->update();
            } else {
                $landStock = new LandStock();
                $landStock->total_stock_land = $totalShotangso;
                $landStock->project_id = $request->project_id;
                $landStock->total_amount = $total_amount;
                $landStock->company_id = Session::get('company_id');
                $landStock->created_by = auth()->user()->id;
                $landStock->current_date = date('Y-m-d');
                $landStock->save();
            }
            DB::commit();

            return redirect()->route('land_purchase_list')->with('status', 'Land Purchased Successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function land_stock_list()
    {
        $data['main_menu'] = 'land_purchase';
        $data['child_menu'] = 'land_stock_list';
        $data['land_stock'] = LandStock::with('project')->orderBy('id', 'desc')->paginate(20);
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();

        return view('land_purchase.land_stock', $data);
    }

    public function purchaseEdit($id)
    {
        $data['purchase'] = LandPurchase::find($id);
        $data['purchase_details'] = LandPurchaseDetail::with('documents', 'donors')->where('land_purchase_id', $id)->get();
        $data['project_data'] = Project::where(['company_id' => Session::get('company_id')])->with('company')->get();
        return view('land_purchase.edit', $data);
    }

    public function updatePurchase(Request $request,$id){
        $request->validate([
            'project_id' => 'required|integer',
            'purchase_date' => 'required|date',
            'dolil_no' => 'required|array',
            'rs_dag_no' => 'required|array',
            'khatian_no' => 'required|array',
            'khazna_no' => 'required|array',
            'shotangso' => 'required|array',
            'per_shotangso_rate' => 'required|array',
            'total_amount' => 'required|array',
            'medium_amount' => 'required|array',
            'type' => 'required|array',
            'remarks' => 'required|array',
            'donor_name' => 'required|array',
            'attached_file.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $landPurchase = LandPurchase::find($id);
        $details = $request->details_id;
        //Total Amount and Total Land Calculation
        $total_amount = 0;
        $totalShotangso = 0;

        for ($i = 0; $i < count($details); $i++) {
            $total_amount += $request->total_amount[$i];
            $totalShotangso += $request->shotangso[$i];
        }

        //Update Purchase Data
        $purchaseData = [
            'project_id' => $request->project_id,
            'purchase_date' => $request->purchase_date,
            'current_date' => date('Y-m-d'),
            'total_amount' => $total_amount,
            'total_purchase_land' => $totalShotangso,
            'updated_by' => auth()->user()->id,
        ];

        $landPurchase->update($purchaseData);

        for ($i = 0; $i < count($details); $i++) {

            $landPurchaseDetail = LandPurchaseDetail::where('id',$details[$i])->update([
                'dolil_no' => $request->input('dolil_no')[$i],
                'rs_dag_no' => $request->input('rs_dag_no')[$i],
                'khatian_no' => $request->input('khatian_no')[$i],
                'khazna_no' => $request->input('khazna_no')[$i],
                'shotangso' => $request->input('shotangso')[$i],
                'per_shotangso_rate' => $request->input('per_shotangso_rate')[$i],
                'total_amount' => $request->input('total_amount')[$i],
                'medium_amount' => $request->input('medium_amount')[$i],
                'type' => $request->input('type')[$i],
                'remarks' => $request->input('remarks')[$i],
            ]);


           if (isset($request->land_documents_type_name[$i])) {
            // dd('dds');
                foreach ($request->land_documents_type_name[$i] as $docIndex => $docTypeName) {
                    $documentId = $request->document_id[$i][$docIndex] ?? null;
                    $newFile = $request->file('land_documents')[$i][$docIndex] ?? null;

                    if ($documentId) {
                        $document = LandDocumet::find($documentId);
                        if ($document) {
                            if ($newFile instanceof \Illuminate\Http\UploadedFile) {
                                if (file_exists(public_path('land_purchase/' . $document->land_documents))) {
                                    unlink(public_path('land_purchase/' . $document->land_documents));
                                }
                                $fileName = time() . "_document_" . ($i+1) . '_' . $docIndex . '.' . $newFile->getClientOriginalExtension();
                                $newFile->move(public_path('land_purchase'), $fileName);
                                $document->update([
                                    'land_documents' => $fileName,
                                    'land_documents_type_name' => $docTypeName,
                                ]);
                            } else {
                                $document->update([
                                    'land_documents_type_name' => $docTypeName,
                                ]);
                            }
                        }
                    } else {
                        $fileName = null;
                        if ($newFile instanceof \Illuminate\Http\UploadedFile) {
                            $fileName = time() . "_document_" . ($i+1) . '_' . $docIndex . '.' . $newFile->getClientOriginalExtension();
                            $newFile->move(public_path('land_purchase'), $fileName);
                        }
                        LandDocumet::create([
                            'land_purchase_detail_id' => $details[$i],
                            'land_documents_type_name' => $docTypeName,
                            'land_documents' => $fileName,
                        ]);
                    }
                }
            }


            if (isset($request->donor_name[$i])) {
// dd($request->donor_name[$i], $request->land_doner_id[$i]);
                foreach ($request->donor_name[$i] as $donorIndex => $donorName) {

                    $donorId = $request->land_doner_id[$i][$donorIndex] ?? null;
                    // dd($donorId);
                    if ($donorId) {
                        $donor = DonorName::find($donorId);
                        if ($donor) {
                            $donor->update([
                                'donor_name' => $donorName,
                            ]);
                        }
                    } else {
                        $donarData = DonorName::create([
                            'land_purchase_detail_id' => (int) $details[$i],
                            'donor_name' => $donorName,
                        ]);
                    }
                }

            }

        }
        return redirect()->back()->with('status', 'Land Purchased Data Updated Successfully.');

    }
}
