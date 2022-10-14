<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KhoaHoc extends Model
{
    use HasFactory;
    protected $table = 'khoa_hoc'; // định nghĩa tên bảng

    protected $guarded = []; // định nghĩa các trường dữ liệu muốn làm việc

    // định nghĩa các hàm muốn thao tác với cơ sở dữ liệu

    // hàm lấy tất cả các bản ghi 
    public function index($params, $pagination = true,  $perpage)
    {

        // hàm có 3 tham số truyền vào lần lượt là mảng keyword , có phần trang hay không , số bản ghi trong 1 trang 
        if ($pagination) {
            // nếu phần trang 
            $query = DB::table($this->table)
                ->join('danh_muc', $this->table . '.id_danh_muc', '=', 'danh_muc.id')
                ->select($this->table . '.*', 'khoa_hoc.*')
                ->orderBy($this->table . '.id');
            if (!empty($params['keyword'])) {
                $query =  $query->where(function ($q) use ($params) {
                    $q->orWhere($this->table . '.ten_khoa_hoc', 'like', '%' . $params['keyword']  . '%');
                });
            }
            $list = $query->paginate($perpage)->withQueryString();
        } else {
            // nếu không phần trang 
            $query = DB::table($this->table)
                ->join('danh_muc', $this->table . '.id_danh_muc', '=', 'danh_muc.id')
                ->select($this->table . '.*', 'khoa_hoc.*')
                ->orderBy($this->table . '.id');
            if (!empty($params['keyword'])) {
                $query =  $query->where(function ($q) use ($params) {
                    $q->orWhere($this->table . '.ten_khoa_hoc', 'like', '%' . $params['keyword']  . '%');
                });
            }
            $list = $query->get();
        }
        return $list;
    }

    // hàm lấy 1 bản ghi theo id - chi tiết bản ghi
    public function show($id){
        if(!empty($id)){
            $query = DB::table($this->table)
                    ->where('id' , '=' , $id)
                    ->first();
            return $query;        

        }
    }


    // hàm thêm bản ghi
    public function create($params)
    {
        $data  = array_merge($params['cols'], [
            // 'created_at' => date('Y-m-d H:i:s'),
            // 'anh_khoa_hoc' => $filename // nếu bảng nào có phần ảnh cần update thì bổ sung thêm phần code này
        ]);

        $query = DB::table($this->table)->insertGetId($data);
        return $query;
    }

    // hàm xóa bản ghi theo id 
    public function remove($id){
        if(!empty($id)) {
            $query = DB::table($this->table)->where('id', '=', $id);
            $data = [
                'delete_at' => 0
            ];
            $query = $query->update($data);
            return $query;           
        }
    }


    // hàm update bản ghi 
    public function saveupdate($id, $params)
    {
        $data = array_merge($params['cols'], [
            'updated_at' => date('Y-m-d H:i:s'),
            
        ]); 
        if(!empty($guarded)){

            $data = array_merge($params['cols'] , [
                'anh_mb'=> $guarded
            ]);
        }
        $query =  DB::table($this->table)
            ->where('id', '=', $id)
            ->update($data);
        return $query;
    }


}