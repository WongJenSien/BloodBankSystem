<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Override;

class SearchController extends Controller
{
    //
    public function search(Request $request)
    {
        $result = ['123','123'];
        $searchValue = $request->search;
        // get the current url
        $currentURL = $request->currentUrl;
        //get the route url
        $url = $this->removeUrlProtocol($currentURL);
        // get the parameter of the url
        $parameters = $this->getURLParameter($currentURL);

        if ($searchValue == null) {
            $this->goToPage($url, $parameters);
        }

        switch ($searchValue) {
            case 'view-inventory':
                $result = $this->searchResult($this->ref_table_inventories);
                break;
            case 'view-shipment':
                $result = $this->searchResult($this->ref_table_shipment);
                break;
            default:
                $this->goToPage($url, $parameters);
        }

        if ($result !== null) {
            return redirect($url)->with('searchResult', $result)->with('showModal', true);
        } else {
            // If the search result is null, redirect without showing the modal
            return redirect($url);
        }
    }
    public function goToPage($url, $parameters)
    {
        if ($parameters != null) {
            foreach ($parameters as $value) {
                $url = $url . '/' . $value;
            }
        }
        return redirect($url);
    }

    public function searchResult($ref_table)
    {

        $result = [];

        return ['123'];
    }
}
