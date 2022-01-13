<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
	public function index()
	{
		//
		return Proveedor::paginate(5);
	}

	public function create($id)
	{
		//
		$proveedor = Proveedor::findOrFail($id);
		Proveedor::destroy($id);
		return redirect('proveedor')->with('mensaje', 'Proveedor eliminado correctamente');
	}

	public function store(Request $request)
	{

		$proveedor = new Proveedor();
		$proveedor->name = $request['name'];
		$proveedor->last_name = $request['last_name'];
		$proveedor->type_document = $request['type_document'];
		$proveedor->document_number = $request['document_number'];
		$proveedor->cell_phone1 = $request['cell_phone1'];
		$proveedor->cell_phone2 = $request['cell_phone2'];
		$proveedor->address = $request['address'];
    $proveedor->email = $request['email'];
		$proveedor->save();
	}

	public function show(Proveedor $proveedor)
	{
		//
	}

	public function edit(Proveedor $proveedor)
	{
		//
	}

	public function update(Request $request, Proveedor $proveedor)
	{
		$proveedor = Proveedor::find($request->id);
		$proveedor->name = $request['name'];
		$proveedor->last_name = $request['last_name'];
		$proveedor->type_document = $request['type_document'];
		$proveedor->document_number = $request['document_number'];
		$proveedor->cell_phone1 = $request['cell_phone1'];
		$proveedor->cell_phone2 = $request['cell_phone2'];
		$proveedor->address = $request['address'];
    $proveedor->email = $request['email'];
		$proveedor->save();
	}

	public function changeStatus(Proveedor $proveedor)
	{
		//
		$c = Proveedor::find($proveedor->id);
		$c->status = !$c->status;
		$c->save();
	}
	public function destroy($id)
	{
		//
	}
}
