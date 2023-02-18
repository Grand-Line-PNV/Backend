<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Models\Campaign;
use App\Traits\ApiResponse;
use App\Helpers\FileHelper;

class CampaignController extends Controller
{
    use ApiResponse;
    public function create(CampaignRequest $request)
    {

        $campaign = new Campaign([
            'brand_id' => $request->brand_id,
            'campaign_status' => Campaign::STATUS_APPLY,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'started_date' => $request->started_date,
            'ended_date' => $request->ended_date,
            'industry' => $request->industry,
            'hashtag' => $request->hashtag,
            'socialChannel' => $request->socialChannel,
            'amount' => $request->amount,
            'require' => $request->require,
            'interest' => $request->interest,
        ]);
        $campaign->save();

        if ($request->hasfile('campaignImages')) {
            foreach ($request->file('campaignImages') as $file) {
                $campaignImage = FileHelper::uploadFileToS3($file, 'campaigns');
                $campaignImage->campaign_id = $campaign->id;
                $campaignImage->save();
            }
        }

        if ($request->hasfile('productImages')) {
            foreach ($request->file('productImages') as $file) {
                $productImage = FileHelper::uploadFileToS3($file, 'products');
                $productImage->campaign_id = $campaign->id;
                $productImage->save();
            }
        }

        return $this->responseSuccess();
    }

    public function update(CampaignRequest $request, $campaignId)
    {
        $campaign = Campaign::find($campaignId);
        if (empty($campaign)) {
            return $this->responseError('Campaign does not exist!');
        }

        $campaign->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'started_date' => $request->started_date,
            'ended_date' => $request->ended_date,
            'industry' => $request->industry,
            'hashtag' => $request->hashtag,
            'socialChannel' => $request->socialChannel,
            'amount' => $request->amount,
            'require' => $request->require,
            'interest' => $request->interest,
        ]);

        $campaignFiles = Campaign::with('files')->findOrFail($campaign->id);
        foreach ($campaignFiles->files as $file) {
            FileHelper::removeFileFromS3($file);
            $file->delete();
        }

        if ($request->hasfile('campaignImages')) {
            foreach ($request->file('campaignImages') as $file) {
                $campaignImage = FileHelper::uploadFileToS3($file, 'campaigns');
                $campaignImage->campaign_id = $campaign->id;
                $campaignImage->save();
            }
        }

        if ($request->hasfile('productImages')) {
            foreach ($request->file('productImages') as $file) {
                $productImage = FileHelper::uploadFileToS3($file, 'products');
                $productImage->campaign_id = $campaign->id;
                $productImage->save();
            }
        }

        return $this->responseSuccess();
    }
    public function destroy($campaignId)
    {
        $campaign = Campaign::find($campaignId);
        if (empty($campaign)) {
            return $this->responseError('Campaign does not exist!');
        }

        $campaign = Campaign::with('files')->where('id', $campaignId)->first();
        foreach ($campaign->files as $file) {
            FileHelper::removeFileFromS3($file);
            $file->delete();
        }

        $campaign->delete();

        return $this->responseSuccess();
    }

    public function viewDetailCampaign($campaignId)
    {
        $campaign = Campaign::with('files')->where('id', $campaignId)->first();
        return $campaign;
    }

    public function viewCampaigns()
    {
        $campaign = Campaign::with('files')->get();
        return $campaign;
    }
}
