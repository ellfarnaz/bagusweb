<?php

namespace App\Http\Controllers;

use App\Models\Minuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MinumanController extends Controller
{
    

    public function index()
    {
        $data = Minuman::all();
        
        return view('minuman.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('minuman.create');
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
            'nama.required'=>'Nama Minuman harus diisi!',
            'harga.required'=>'Harga harus diisi!',
            'deskripsi.required'=>'Deskripsi Minuman harus diisi!',
            'foto.required'=>'foto Minuman harus diisi!',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);
        

        if( $validator->fails() ) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        $foto = $request->file('foto');
        // dd($request);
        $fileName = time().'.'.$foto->getClientOriginalExtension();
        $foto->move(public_path('images'), $fileName);
        
        Minuman::create([
            'nama'=>$request->nama,
            'harga'=>$request->harga,
            'deskripsi'=>$request->deskripsi,
            'foto'=>$fileName,
        ]);

        return redirect('minuman')->with('success', 'Data Minuman berhasil ditambahkan!');

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
        $data = Minuman::find($id);
        if( !$data ) return back()->with('error', 'Data Minuman tidak ditemukan');
       
        return view('minuman.edit',compact('data'));
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
            'nama.required'=>'Nama Minuman harus diisi!',
            'harga.required'=>'Harga harus diisi!',
            'deskripsi.required'=>'Deskripsi Minuman harus diisi!',
            'foto.required'=>'foto harus diisi!',
        ];

        $validator = Validator::make($request->all(),$rules,$messages);

        if( $validator->fails() ) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        // cek apakah data minuman tersebut ada di database
        $data = Minuman::find($id);
        if( !$data ) return back()->with('error', 'Data Minuman tidak terdaftar');
        // update data minuman

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

        return redirect('minuman')->with('success', 'Data Minuman berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapus($id)
    {
        // cek apakah data minuman tersebut ada di database
        $data = Minuman::find($id);
        if( !$data ) return back()->with('error', 'Data Minuman tidak terdaftar');
         
        $data->delete(); 
 
        return redirect('minuman')->with('success', 'Data Minuman berhasil dihapus!');
    }

}
