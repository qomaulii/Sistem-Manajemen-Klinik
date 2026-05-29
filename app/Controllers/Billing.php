<?php

namespace App\Controllers;

class Billing extends BaseController
{
    protected $bitauth;

    public function __construct()
    {
        helper(['url', 'form', 'date']);
        $this->bitauth = new \App\Libraries\Bitauth();
    }

    private function _check_access()
    {
        if (!$this->bitauth->logged_in()) {
            session()->set('redir', current_url());
            return redirect()->to('account/login');
        }

        return true;
    }

    private function _reduceDrugStockForVisit($visitId)
    {
        $db = \Config\Database::connect();

        $billing = $db->table('billing')
            ->where('visit_id', $visitId)
            ->get()
            ->getRow();

        if ($billing && (int) ($billing->stock_reduced ?? 0) === 1) {
            return;
        }

        $drugItems = $db->table('visit_items')
            ->where('visit_id', $visitId)
            ->where('item_type', 'OBAT')
            ->get()
            ->getResult();

        foreach ($drugItems as $item) {
            $qty = (int) ($item->qty ?? 1);

            if ($qty <= 0) {
                $qty = 1;
            }

            $db->table('service_items')
                ->where('item_id', $item->item_id)
                ->where('item_type', 'OBAT')
                ->set('stock', 'GREATEST(stock - ' . $qty . ', 0)', false)
                ->update();
        }

        $db->table('billing')
            ->where('visit_id', $visitId)
            ->update([
                'stock_reduced' => 1
            ]);
    }

    public function index()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $data['billings'] = $db->table('patient_visits pv')
            ->select("
                pv.visit_id,
                pv.patient_id,
                pv.queue_number,
                pv.status,
                pv.payment_status,
                u.first_name,
                u.last_name,
                b.bill_id,
                b.total_amount,
                b.payment_method,
                b.payment_status AS billing_status,
                b.create_date,
                COUNT(vi.visit_item_id) AS total_item
            ")
            ->join('userdata u', 'u.user_id = pv.patient_id', 'left')
            ->join('visit_items vi', 'vi.visit_id = pv.visit_id', 'left')
            ->join('billing b', 'b.visit_id = pv.visit_id', 'left')
            ->where('pv.status !=', 'Batal')
            ->groupBy('pv.visit_id')
            ->having('total_item >', 0)
            ->orderBy('pv.visit_id', 'DESC')
            ->get()
            ->getResult();

        $data['title'] = 'Tagihan Pembayaran';
        $data['includes'] = ['billing/list'];

        return view('header', $data)
             . view('index', $data)
             . view('footer', $data);
    }

    public function create()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $visits = $db->table('patient_visits pv')
            ->select("
                pv.visit_id,
                pv.patient_id,
                pv.queue_number,
                pv.status,
                pv.payment_status,
                pasien.first_name AS patient_first_name,
                pasien.last_name AS patient_last_name,
                dokter.first_name AS doctor_first_name,
                dokter.last_name AS doctor_last_name,
                COUNT(vi.visit_item_id) AS total_item
            ")
            ->join('userdata pasien', 'pasien.user_id = pv.patient_id', 'left')
            ->join('userdata dokter', 'dokter.user_id = pv.doctor_id', 'left')
            ->join('visit_items vi', 'vi.visit_id = pv.visit_id', 'left')
            ->join('billing b', 'b.visit_id = pv.visit_id', 'left')
            ->where('pv.status !=', 'Batal')
            ->where('pv.payment_status !=', 'Sudah Bayar')
            ->where('b.bill_id IS NULL')
            ->groupBy('pv.visit_id')
            ->having('total_item >', 0)
            ->orderBy('pv.visit_id', 'DESC')
            ->get()
            ->getResult();

        foreach ($visits as $v) {
            $v->items = $db->table('visit_items')
                ->where('visit_id', $v->visit_id)
                ->orderBy('item_type', 'ASC')
                ->orderBy('visit_item_id', 'ASC')
                ->get()
                ->getResult();

            $total = 0;

            foreach ($v->items as $item) {
                $total += (float) $item->subtotal;
            }

            $v->total_amount = $total;
        }

        $data['title'] = 'Buat Tagihan Pembayaran';
        $data['visits'] = $visits;
        $data['includes'] = ['billing/new'];

        return view('header', $data)
             . view('index', $data)
             . view('footer', $data);
    }

    public function save()
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $visitId = (int) $this->request->getPost('visit_id');
        $paymentMethod = $this->request->getPost('payment_method') ?: 'Cash';

        if ($visitId <= 0) {
            return redirect()->to('billing/create')
                ->with('error', 'Pilih pasien terlebih dahulu.');
        }

        $visit = $db->table('patient_visits pv')
            ->select('pv.*, u.first_name, u.last_name')
            ->join('userdata u', 'u.user_id = pv.patient_id', 'left')
            ->where('pv.visit_id', $visitId)
            ->get()
            ->getRow();

        if (!$visit) {
            return redirect()->to('billing/create')
                ->with('error', 'Data kunjungan pasien tidak ditemukan.');
        }

        $existing = $db->table('billing')
            ->where('visit_id', $visitId)
            ->get()
            ->getRow();

        if ($existing) {
            return redirect()->to('billing/receipt/' . $existing->bill_id);
        }

        $items = $db->table('visit_items')
            ->where('visit_id', $visitId)
            ->orderBy('item_type', 'ASC')
            ->orderBy('visit_item_id', 'ASC')
            ->get()
            ->getResult();

        if (empty($items)) {
            return redirect()->to('billing/create')
                ->with('error', 'Belum ada rincian biaya dari dokter untuk pasien ini.');
        }

        $totalAmount = 0;
        $serviceDetails = [];

        foreach ($items as $item) {
            $totalAmount += (float) $item->subtotal;

            $serviceDetails[] =
                $item->item_type . ' - ' .
                $item->item_name . ' x' .
                $item->qty . ' = Rp ' .
                number_format((float) $item->subtotal, 0, ',', '.');
        }

        $db->transStart();

        $db->table('billing')->insert([
            'visit_id'         => $visitId,
            'patient_id'       => $visit->patient_id,
            'user_id'          => session()->get('ba_user_id'),
            'service_details'  => implode("\n", $serviceDetails),
            'total_amount'     => $totalAmount,
            'payment_method'   => $paymentMethod,
            'payment_status'   => 'Paid',
            'create_date'      => time(),
            'paid_date'        => time(),
            'stock_reduced'    => 0
        ]);

        $billId = $db->insertID();

        $db->table('patient_visits')
            ->where('visit_id', $visitId)
            ->update([
                'payment_status' => 'Sudah Bayar',
                'status'         => 'Selesai'
            ]);

        $this->_reduceDrugStockForVisit($visitId);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('billing/create')
                ->with('error', 'Gagal membuat tagihan pembayaran.');
        }

        return redirect()->to('billing/receipt/' . $billId);
    }

    public function receipt($billId)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $billing = $db->table('billing b')
            ->select("
                b.*,
                pv.queue_number,
                pasien.first_name AS patient_first_name,
                pasien.last_name AS patient_last_name,
                kasir.first_name AS cashier_first_name,
                kasir.last_name AS cashier_last_name
            ")
            ->join('patient_visits pv', 'pv.visit_id = b.visit_id', 'left')
            ->join('userdata pasien', 'pasien.user_id = b.patient_id', 'left')
            ->join('userdata kasir', 'kasir.user_id = b.user_id', 'left')
            ->where('b.bill_id', $billId)
            ->get()
            ->getRow();

        if (!$billing) {
            return redirect()->to('billing')
                ->with('error', 'Data struk tidak ditemukan.');
        }

        $items = $db->table('visit_items')
            ->where('visit_id', $billing->visit_id)
            ->orderBy('item_type', 'ASC')
            ->orderBy('visit_item_id', 'ASC')
            ->get()
            ->getResult();

        $data['title'] = 'Struk Pembayaran';
        $data['billing'] = $billing;
        $data['items'] = $items;

        return view('billing/receipt', $data);
    }

    public function print_receipt($visitId)
    {
        $check = $this->_check_access();
        if ($check !== true) return $check;

        $db = \Config\Database::connect();

        $billing = $db->table('billing')
            ->where('visit_id', $visitId)
            ->get()
            ->getRow();

        if (!$billing) {
            return redirect()->to('billing/create')
                ->with('error', 'Tagihan belum dibuat.');
        }

        return redirect()->to('billing/receipt/' . $billing->bill_id);
    }
}