<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Session;
use App\Models\{
	Unit,
};
use DB;
use PDF;
use Auth;

class UnitService
{
	public function store($data,$id=null)
	{
		if ($id == null) {
			$create = new Unit;
		} else if ($id != null) {
			$create = Unit::find($id);
		}
		$create->name = $data['name'];
		$create->status = $data['status'];
		return $create;		
	}
}