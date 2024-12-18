<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MakananController extends Controller
{
    

    public function index()
    {
        $data = Makanan::all();
        
        return view('makanan.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('makanan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function simpan  (Request $request)
    {
        /* 
            Validation
        */
        $rules = [
            'nama'=>'required',
            'harga'=>'required',
            'deskripsi'=>'required',
            'foto' => 'required'
        ];
        $messages = [
            'nama.required'=>'Nama Makanan harus diisi!',
            'harga.required'=>'Harga harus diisi!',
            'deskripsi.required'=>'Deskripsi Makanan harus diisi!',
            'foto.required'=>'foto Makanan harus diisi!',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        

        if( $validator->fails() ) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        $foto = $request->file('foto');
        // dd($request);
        $fileName = time().'.'.$foto->getClientOriginalExtension();
        $foto->move(public_path('images'), $fileName);
        
        Makanan::create([
            'nama'=>$request->nama,
            'harga'=>$request->harga,
            'deskripsi'=>$request->deskripsi,
            'foto'=>$fileName,
        ]);

        return redirect('makanan')->with('success', 'Data Makanan berhasil ditambahkan!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort('404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Makanan::find($id);
        if( !$data ) return back()->with('error', 'Data Makanan tidak ditemukan');
       
        return view('makanan.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        /* 
            Validation
        */
        $rules = [
            'nama'=>'required',
            'harga'=>'required',
            'deskripsi'=>'required',
            'foto' => 'required'
        ];
        $messages = [
            'nama.required'=>'Nama Makanan harus diisi!',
            'harga.required'=>'Harga harus diisi!',
            'deskripsi.required'=>'Deskripsi Makanan harus diisi!',
            'foto.required'=>'foto harus diisi!',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);

        if( $validator->fails() ) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        // cek apakah data makanan tersebut ada di database
        $data = Makanan::find($id);
        if( !$data ) return back()->with('error', 'Data Makanan tidak terdaftar');
        // update data makanan

        if ($request->hasFile('foto')) { 
            
            $foto = $request->file('foto');
            $fileName = time().'.'.$foto->getClientOriginalExtension();
            $foto->move(public_path('images'), $fileName);
            $data->foto = $fileName;

        }

        $data->nama = $request->nama;
        $data->harga = $request->harga;
        $data->deskripsi = $request->deskripsi;
        $data->save();

        return redirect('makanan')->with('success', 'Data Makanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapus($id)
    {
        // cek apakah data makanan tersebut ada di database
        $data = Makanan::find($id);
        if( !$data ) return back()->with('error', 'Data Makanan tidak terdaftar');
         
        $data->delete(); 
 
        return redirect('makanan')->with('success', 'Data Makanan berhasil dihapus!');
    }

}
