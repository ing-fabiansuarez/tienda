<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CodigomotoModel;

class Codigomoto extends BaseController
{
    public function __construct()
    {
        $this->mdlCodigosSorteo = new CodigomotoModel();
    }

    public function index()
    {
        return view('adminpage/structure/header')
            . view('adminpage/structure/navbar')
            . view('adminpage/structure/sidebar')
            . view('adminpage/contents/codigos_view', [
                'codes' => $this->mdlCodigosSorteo->where('by_codigo', session()->get('idadministrator'))->orderBy('created_at_codigo', 'desc')->findAll()
            ])
            . view('adminpage/structure/footer');
    }

    public function getInfoForm()
    {
        if (!$this->validate([
            'idpedido' => 'required',
            'identificacion' => 'required',
            'cantidad' => 'required|numeric'
        ])) {
            return redirect()->to(base_url() . route_to('index_codigo_moto'))->with('errors', $this->validator->getErrors())->withInput();
        }
        $idPedido = $this->request->getPost('idpedido');
        $identification = $this->request->getPost('identificacion');
        $cantidad = $this->request->getPost('cantidad');

        for ($i = 0; $i < floor($cantidad / 500000); $i++) {
            $this->mdlCodigosSorteo->insert([
                'id_codigosmoto' => '',
                'codigo_codigo' => uniqid(),
                'id_pedido' => $idPedido,
                'by_codigo' => session()->get('idadministrator'),
                'cliente_codigo' => $identification,
                'active' => false,
                'ip_codigo' => null
            ]);
        }

        return redirect()->to(base_url() . route_to('index_codigo_moto'))
            ->with('msg', ['body' => 'Fue creado con exito ' . floor($cantidad / 500000) . ' codigos']);
    }
}
